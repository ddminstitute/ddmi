<?php
namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->paginate(20);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_code'  => 'required|string|max:10|unique:branches',
            'branch_name'  => 'required|string|max:100',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:50',
            'state'        => 'nullable|string|max:50',
            'pincode'      => 'nullable|string|max:10',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email',
            'manager_name' => 'nullable|string|max:100',
        ]);
        $b = Branch::create($data);
        ActivityLog::record('created', "Branch {$b->branch_name} ({$b->branch_code}) created", $b);
        return redirect()->route('branches.index')->with('success','Branch created.');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'branch_name'  => 'required|string|max:100',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:50',
            'state'        => 'nullable|string|max:50',
            'pincode'      => 'nullable|string|max:10',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email',
            'manager_name' => 'nullable|string|max:100',
            'is_active'    => 'nullable|boolean',
        ]);
        $branch->update($data);
        return redirect()->route('branches.index')->with('success','Branch updated.');
    }
}
