<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendContactRequest;
use App\Mail\ContactMailable;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact/PsrShow');
    }

    public function send(SendContactRequest $request): Response
    {
        Mail::to(config('mail.from.address'))->send(new ContactMailable($request));

        return Inertia::render('Contact/PsrShow', [
            'success' => true,
        ]);
    }
}
