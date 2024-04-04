<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $contacts = $user->accounts()->with('contacts.account')->get()->flatMap->contacts;

        return inertia('Contacts/Index', ['contacts' => $contacts]);
    }

    public function show($id)
    {
        $contact = Contact::with('account')->findOrFail($id);

        return inertia('Contacts/Show', ['contact' => $contact]);
    }

    public function create()
    {
        $user = Auth::user();

        $accounts = $user->accounts()->get();

        return inertia('Contacts/Create', ['accounts' => $accounts]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required','email','max:255',
                Rule::unique('contacts')->where(function ($query) use ($request) {
                    return $query->where('account_id', $request->account_id);
                }),
            ],
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'account_id' => 'required|exists:accounts,id', 
        ]);
    
        Contact::create($data);
    
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully!');
    }

    public function edit($id)
    {
        $user = Auth::user();

        $contact = Contact::where('id', $id)->with('account')->firstOrFail();
        $accounts = $user->accounts()->get();

        return inertia('Contacts/Edit', ['contact' => $contact, 'accounts'=> $accounts]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required','email','max:255'],
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'account_id' => 'required|exists:accounts,id', 
        ]);
    
        Contact::findOrFail($id)->update($data);

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully!');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        
        $contact->delete();
        
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully');
    }
}
