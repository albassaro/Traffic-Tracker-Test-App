<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'transition_cost',
        'url',
    ];

    public function subscribers()
    {
        $this->hasMany(Subscription::class)->orderBy('created_at');
    }
}
