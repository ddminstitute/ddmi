<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }
    public function create() { return view('users.create'); }
    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|email|unique:users',
            'phone'=>'nullable|string|max:15',
            'role'=>'required|in:admin,manager,cashier,agent',
            'password'=>'required|min:8|confirmed',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;
        User::create($data);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
    public function edit(User $user) { return view('users.edit', compact('user')); }
    public function update(Request $request, User $user) {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'email'=>"required|email|unique:users,email,{$user->id}",
            'phone'=>'nullable|string|max:15',
            'role'=>'required|in:admin,manager,cashier,agent',
            'is_active'=>'boolean',
            'password'=>'nullable|min:8|confirmed',
        ]);
        if (!empty($data['password'])) $data['password'] = Hash::make($data['password']);
        else unset($data['password']);
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated.');
    }
    public function destroy(User $user) {
        if ($user->id === auth()->id()) return back()->with('error', 'Cannot delete your own account.');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
