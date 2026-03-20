<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    protected $fillable = ['user_id', 'type', 'credits', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(int $userId, string $type, int $credits, string $description): void
    {
        static::create([
            'user_id'     => $userId,
            'type'        => $type,
            'credits'     => $credits,
            'description' => $description,
        ]);
    }
}
