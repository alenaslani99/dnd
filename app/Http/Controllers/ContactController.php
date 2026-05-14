<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreRequest;
use App\Models\ContactSubmission;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Contact/Index');
    }

    public function store(StoreRequest $request)
    {
        ContactSubmission::create($request->validated());

        return redirect()->route('contact.create')->with('success', 'Tvoja poruka je uspešno poslata.');
    }
}
