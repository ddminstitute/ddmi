<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CollectionPlan;
use App\Models\CompanyExpense;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\RoleFeaturePermission;
use App\Models\SavingPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\FeatureService;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'        => User::count(),
            'customers'    => Customer::count(),
            'accounts'     => Account::count(),
            'loans'        => Loan::count(),
            'transactions' => Transaction::count(),
            'employees'    => Employee::count(),
            'expenses'     => CompanyExpense::count(),
            'collections'  => CollectionPlan::count(),
            'saving_plans' => SavingPlan::count(),
        ];

        $usersByRole = User::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        return view('super-admin.dashboard', compact('stats', 'usersByRole'));
    }

    public function permissions()
    {
        $roles = ['admin', 'manager', 'cashier', 'agent'];
        $features = FeatureService::allFeatures();
        $permissions = [];
        foreach ($roles as $role) {
            $permissions[$role] = RoleFeaturePermission::where('role', $role)->get();
        }
        return view('super-admin.permissions', compact('roles', 'features', 'permissions'));
    }

    public function updatePermissions(Request $request)
    {
        $roles = ['admin', 'manager', 'cashier', 'agent'];
        $features = array_keys(FeatureService::allFeatures());
        $perm = $request->input('perm', []);
        foreach ($roles as $role) {
            foreach ($features as $featureKey) {
                $enabled = isset($perm[$role][$featureKey]);
                RoleFeaturePermission::updateOrCreate(
                    ['role' => $role, 'feature_key' => $featureKey],
                    ['is_enabled' => $enabled]
                );
            }
        }
        return redirect()->route('super-admin.permissions')->with('success', 'Permissions updated successfully.');
    }

    public function seedSuperAdmin()
    {
        $user = User::first();
        if ($user) {
            $user->update(['role' => 'super_admin']);
            return redirect()->route('dashboard')->with('success', 'User "'.$user->name.'" promoted to Super Admin.');
        }
        return back()->with('error', 'No users found. Please register first.');
    }
}
