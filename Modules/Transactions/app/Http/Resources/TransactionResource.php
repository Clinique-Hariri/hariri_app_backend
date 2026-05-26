<?php

namespace Modules\Transactions\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Transactions\Models\Transaction;

/** @mixin Transaction */
class TransactionResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'transaction_number' => $this->transaction_number,
      'amount' => $this->amount,
      'details' => $this->details,
      'type' => $this->type,
      'status' => $this->status,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,

      'user_id' => $this->user_id,

      'transactionable' => $this->whenLoaded('transactionable', function () {
        return [
          'type' => class_basename($this->transactionable_type),
          'id' => $this->transactionable_id,
          // 'data' => $this->transactionable, // optionally include full related model data here
        ];
      }),
    ];
  }
}
