<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'offer_id',
        'subscriber_email',
        'url_redirector',
    ];

    public function offers()
    {
        $this->belongsTo(Offer::class);
    }
    public function users()
    {
        $this->belongsTo(User::class);
    }
}
