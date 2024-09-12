<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\Slideshow;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial para o usuário
     */
    public function index()
    {
        $rifas = Rifa::availables()
            ->latest()
            ->limit(20)
            ->get()
            ->setHidden([
                'created_at',
                'description',
                'updated_at',
            ])
            ->map(function (Rifa $model) {
                $model->thumbnail = Storage::url($model->thumbnail);

                return $model;
            });

        $slideshows = Slideshow::orderBy('order')->get();

        return Inertia::render('Home/PsrList', [
            'values' => $rifas,
            'slideshows' => $slideshows,
        ]);
    }
}
