<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home()
    {
        return view('website.home');
    }

    public function about()
    {
        return view('website.about');
    }

    public function services()
    {
        return view('website.services');
    }

    public function accountPlans()
    {
        return view('website.account-plans');
    }

    public function contact()
    {
        return view('website.contact');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:2000',
            'subject' => 'required|string',
        ]);

        // In a real app you'd send an email here
        return redirect()->route('contact')->with('contact_success', true);
    }
}
