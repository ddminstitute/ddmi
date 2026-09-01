<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Loan;
use App\Models\FixedDeposit;
use App\Models\RecurringDeposit;
use App\Models\Grievance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    public function dashboard()
    {
        $user     = auth()->user();
        $accounts = Account::where('user_id',$user->id)->with('customer')->get();
        $loans    = Loan::where('user_id',$user->id)->where('status','active')->get();
        $fds      = FixedDeposit::whereIn('account_id',$accounts->pluck('id'))->where('status','active')->get();
        $totalBalance = $accounts->sum('balance');
        return view('portal.dashboard', compact('user','accounts','loans','fds','totalBalance'));
    }

    public function accounts()
    {
        $accounts = Account::where('user_id',auth()->id())->with('customer')->latest()->get();
        return view('portal.accounts', compact('accounts'));
    }

    public function transactions(Request $request)
    {
        $accounts = Account::where('user_id',auth()->id())->get();
        $selectedAccount = null; $transactions = collect();
        if ($request->account_id) {
            $selectedAccount = $accounts->firstWhere('id',$request->account_id);
            if ($selectedAccount) {
                $query = Transaction::where('account_id',$selectedAccount->id);
                if ($request->from) $query->whereDate('created_at','>=',$request->from);
                if ($request->to)   $query->whereDate('created_at','<=',$request->to);
                $transactions = $query->latest()->paginate(20)->withQueryString();
            }
        }
        return view('portal.transactions', compact('accounts','selectedAccount','transactions'));
    }

    public function loans()
    {
        $loans = Loan::where('user_id',auth()->id())->with('account')->latest()->get();
        return view('portal.loans', compact('loans'));
    }

    public function deposits()
    {
        $accountIds = Account::where('user_id',auth()->id())->pluck('id');
        $fds = FixedDeposit::whereIn('account_id',$accountIds)->latest()->get();
        $rds = RecurringDeposit::whereIn('account_id',$accountIds)->latest()->get();
        return view('portal.deposits', compact('fds','rds'));
    }

    public function grievances()
    {
        $grievances = Grievance::where('customer_id',auth()->user()->customer_id)->latest()->paginate(10);
        return view('portal.grievances', compact('grievances'));
    }

    public function raiseGrievance(Request $request)
    {
        $data = $request->validate(['subject'=>'required|string|max:150','description'=>'required|string|max:1000','category'=>'required|in:account,loan,service,other']);
        Grievance::create([...$data,'customer_id'=>auth()->user()->customer_id,'status'=>'open','submitted_at'=>now()]);
        return back()->with('success','Grievance submitted. We will respond within 3 working days.');
    }

    public function profileForm() { return view('portal.profile'); }

    public function changePassword(Request $request)
    {
        $request->validate(['current_password'=>'required','password'=>'required|min:8|confirmed']);
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password'=>'Current password is incorrect.']);
        }
        auth()->user()->update(['password'=>Hash::make($request->password)]);
        return back()->with('success','Password changed successfully.');
    }
}
