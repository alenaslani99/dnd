<?php

namespace App\Http\Controllers;

use App\Http\Requests\Newsletter\StoreRequest;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller
{
    public function store(StoreRequest $request)
    {
        NewsletterSubscriber::create($request->validated());

        return redirect()->back()->with('success', 'Uspešno ste se prijavili na newsletter.');
    }
}
