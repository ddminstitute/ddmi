<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Nominee;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NomineeController extends Controller
{
    public function index(Account $account)
    {
        $nominees = $account->nominees()->get();
        return view('nominees.index', compact('account','nominees'));
    }

    public function create(Account $account)
    {
        return view('nominees.create', compact('account'));
    }

    public function store(Request $request, Account $account)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'relation'      => 'required|string|max:50',
            'date_of_birth' => 'nullable|date',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'share_percent' => 'required|integer|min:1|max:100',
            'is_minor'      => 'nullable|boolean',
            'guardian_name' => 'nullable|string|max:100',
        ]);

        $existing = $account->nominees()->sum('share_percent');
        if ($existing + $data['share_percent'] > 100) {
            return back()->with('error','Total nominee share cannot exceed 100%.')->withInput();
        }

        $nominee = $account->nominees()->create($data);
        ActivityLog::record('created', "Nominee {$nominee->name} added to account {$account->account_number}", $nominee);

        return redirect()->route('accounts.nominees.index', $account)->with('success','Nominee added successfully.');
    }

    public function destroy(Account $account, Nominee $nominee)
    {
        $nominee->delete();
        ActivityLog::record('deleted', "Nominee {$nominee->name} removed from account {$account->account_number}");
        return redirect()->route('accounts.nominees.index', $account)->with('success','Nominee removed.');
    }
}
