<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $accounts = $user->accounts()->get();

        return inertia('Accounts/Index', ['accounts' => $accounts]);
    }

    public function show($id)
    {
        $account = Account::with('owner', 'contacts')->findOrFail($id);

        return inertia('Accounts/Show', ['account' => $account]);
    }

    public function create()
    {
        return inertia('Accounts/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'town_city' => 'required|string|max:255',
            'post_code' => 'required|string|max:20',
        ]);
        
        $user = Auth::user();
        
        $user->accounts()->create($data);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully!');
    }

    public function edit($id)
    {
        $account = Account::where('id', $id)->where('owner_id', auth()->id()) ->firstOrFail();
        
        return inertia('Accounts/Edit', ['account' => $account]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'town_city' => 'required|string|max:255',
            'post_code' => 'required|string|max:20',
        ]);
        
        Account::findOrFail($id)->update($data);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully!');
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        
        $account->delete();
        
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully');
    }
}