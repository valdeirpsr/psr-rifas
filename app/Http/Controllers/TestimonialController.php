<?php

namespace App\Http\Controllers;

use App\Models\Winner;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Winner::latest()
            ->with([
                'order' => fn (BelongsTo $query) => $query->select('id', 'customer_fullname'),
            ])
            ->whereNotNull('testimonial')
            ->limit(20)
            ->get()
            ->setVisible([
                'testimonial',
                'order',
            ]);

        return Inertia::render('Testimonials/PsrList', [
            'testimonials' => $testimonials,
        ]);
    }
}
