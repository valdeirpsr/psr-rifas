<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PaymentStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'is_approved' => $this->order->status === Order::STATUS_PAID && !!$this->date_approved,
            'date_approved' => $this->when(!!$this->date_approved, Carbon::parse($this->date_approved))
        ];
    }
}
