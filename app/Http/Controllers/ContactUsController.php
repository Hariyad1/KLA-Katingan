<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->get(); // Fetch all contacts
        return view('kontak.index', compact('contacts')); // Pass data to the view
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'subjek' => 'required|string|max:100',
            'isi' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Contact::create($request->all());
        return redirect()->route('kontak')->with('success', 'Kontak berhasil dikirim!');
    }
}
