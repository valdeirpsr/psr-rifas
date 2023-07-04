<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class OrderController extends Controller
{
    /** @var int */
    private const RIFA_EXPIRE_AT_MINUTES_DEFAULT = 60;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $rifaId = $request->input('rifa');
        $rifa = Rifa::findOrFail($rifaId);

        $quantity = $request->input('quantity');

        $rifaNumbers = collect()->range(0, $rifa->total_numbers_available - 1);

        $rifaNumbersUnavailable = Order::select('numbers_reserved')
            ->where('rifa_id', $rifa->id)
            ->lazy(100)
            ->chunk(100)
            ->map(function (\Illuminate\Support\LazyCollection $orders) {
                /* numbers_reserved é um array contendo os números gerados */
                return collect($orders)->map(fn ($order) => $order->numbers_reserved);
            })
            ->flatten();

        $rifaNumbersAvailable = $rifaNumbers->diff($rifaNumbersUnavailable)
            ->shuffle();

        if ($rifaNumbersAvailable->count() < $quantity) {
            return abort(409, 'Não foi gerado todos os números');
        }

        $rifaRandomNumbers = $rifaNumbersAvailable->splice(0, $quantity)
            ->map(fn ($number) => str_pad($number, 4, "0", STR_PAD_LEFT))
            ->sort();

        $order = false;

        DB::transaction(function () use ($rifaRandomNumbers, $request, $rifa, &$order) {
            $order = new Order();
            $order->customer_fullname = $request->input('fullname');
            $order->customer_email = $request->input('email');
            $order->customer_telephone = $request->input('telephone');
            $order->rifa_id = $rifa->id;
            $order->numbers_reserved = $rifaRandomNumbers->values();
            $order->status = Order::STATUS_RESERVED;
            $order->expire_at = now()->addMinutes(env('RIFA_EXPIRE_AT_MINUTES', self::RIFA_EXPIRE_AT_MINUTES_DEFAULT));
            $order->saveOrFail();
        });

        if ($order instanceof Order) {
            return response()->json([
                'redirect' => route('payment.show', [ $order->id ])
            ], 201);
        }

        return abort(500);
    }
}
