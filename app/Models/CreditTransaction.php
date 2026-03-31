<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    protected $fillable = ['user_id', 'type', 'credits', 'description', 'midtrans_transaction_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(int $userId, string $type, int $credits, string $description, ?string $midtransTransactionId = null): void
    {
        static::create([
            'user_id'                  => $userId,
            'type'                     => $type,
            'credits'                  => $credits,
            'description'              => $description,
            'midtrans_transaction_id'  => $midtransTransactionId,
        ]);
    }
}
