<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'full_name',
        'document',
        'email',
        'phone',
    ];

    /**
     * Relación: una suscripción tiene muchos reportes
     */
    public function reports()
    {
        return $this->hasMany(SubscriptionReport::class);
    }
}
