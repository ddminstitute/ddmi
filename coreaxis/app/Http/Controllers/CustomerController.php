<?php
namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller {
    public function index(Request $request) {
        $query = Customer::query();
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name','like',"%{$request->search}%")
                  ->orWhere('phone','like',"%{$request->search}%")
                  ->orWhere('customer_id','like',"%{$request->search}%")
                  ->orWhere('pan_number','like',"%{$request->search}%");
            });
        }
        if ($request->status) $query->where('status', $request->status);
        $customers = $query->latest()->paginate(15)->withQueryString();
        return view('customers.index', compact('customers'));
    }
    public function create() { return view('customers.create'); }
    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'father_name'=>'nullable|string|max:100',
            'mother_name'=>'nullable|string|max:100',
            'gender'=>'required|in:male,female,other',
            'date_of_birth'=>'required|date',
            'phone'=>'required|string|max:15',
            'alternate_phone'=>'nullable|string|max:15',
            'email'=>'nullable|email',
            'address'=>'required|string',
            'city'=>'required|string|max:50',
            'state'=>'required|string|max:50',
            'pincode'=>'required|string|max:10',
            'pan_number'=>'nullable|string|max:20',
            'aadhaar_number'=>'nullable|string|max:20',
            'occupation'=>'nullable|string|max:100',
            'annual_income'=>'nullable|numeric|min:0',
            'notes'=>'nullable|string|max:500',
            'photo'=>'nullable|image|max:2048',
            'signature'=>'nullable|image|max:1024',
            'pan_document'=>'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'aadhaar_document'=>'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $data['customer_id'] = Customer::generateCustomerId();
        foreach (['photo','signature','pan_document','aadhaar_document'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('customers','public');
            }
        }
        $customer = Customer::create($data);
        return redirect()->route('customers.show', $customer)->with('success', "Customer {$customer->customer_id} created successfully.");
    }
    public function show(Customer $customer) {
        $customer->load('accounts');
        try {
            $customer->load('loans');
        } catch (\Exception $e) {
            $customer->setRelation('loans', collect());
        }
        return view('customers.show', compact('customer'));
    }
    public function edit(Customer $customer) { return view('customers.edit', compact('customer')); }
    public function update(Request $request, Customer $customer) {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'father_name'      => 'nullable|string|max:100',
            'mother_name'      => 'nullable|string|max:100',
            'gender'           => 'required|in:male,female,other',
            'date_of_birth'    => 'nullable|date',
            'phone'            => 'required|string|max:15',
            'alternate_phone'  => 'nullable|string|max:15',
            'email'            => 'nullable|email|max:100',
            'address'          => 'nullable|string',
            'city'             => 'nullable|string|max:50',
            'state'            => 'nullable|string|max:50',
            'pincode'          => 'nullable|string|max:10',
            'pan_number'       => 'nullable|string|max:20',
            'aadhaar_number'   => 'nullable|string|max:20',
            'occupation'       => 'nullable|string|max:100',
            'annual_income'    => 'nullable|numeric|min:0',
            'status'           => 'required|in:active,inactive,blacklisted',
            'notes'            => 'nullable|string',
            'photo'            => 'nullable|image|max:2048',
            'signature'        => 'nullable|image|max:1024',
            'pan_document'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'aadhaar_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        foreach (['photo','signature','pan_document','aadhaar_document'] as $field) {
            if ($request->hasFile($field)) {
                if ($customer->$field) Storage::disk('public')->delete($customer->$field);
                $data[$field] = $request->file($field)->store('customers','public');
            } else {
                unset($data[$field]);
            }
        }
        $customer->update($data);
        return redirect()->route('customers.show', $customer)->with('success', 'Customer updated successfully.');
    }
    public function createPortalAccount(Customer $customer)
    {
        if ($customer->portalUser) {
            return back()->with('error', 'Portal account already exists for this customer.');
        }

        // Default password is phone number; customer must change on first login
        $password = $customer->phone ?? 'Portal@123';
        $user = \App\Models\User::create([
            'name'        => $customer->name,
            'email'       => $customer->email ?? strtolower(str_replace(' ', '.', $customer->name)) . '.' . $customer->id . '@portal.local',
            'phone'       => $customer->phone,
            'password'    => \Illuminate\Support\Facades\Hash::make($password),
            'role'        => 'customer',
            'is_active'   => true,
            'customer_id' => $customer->id,
        ]);

        // Link existing accounts to this user
        $customer->accounts()->whereNull('user_id')->update(['user_id' => $user->id]);

        return back()->with('success', "Portal account created. Login: {$user->email} / Password: {$password} (phone number). Ask customer to change password after first login.");
    }

    public function destroy(Customer $customer) {
        if ($customer->accounts()->where('status','active')->exists()) {
            return back()->with('error', 'Cannot delete customer with active accounts.');
        }
        foreach (['photo','signature','pan_document','aadhaar_document'] as $field) {
            if ($customer->$field) Storage::disk('public')->delete($customer->$field);
        }
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
