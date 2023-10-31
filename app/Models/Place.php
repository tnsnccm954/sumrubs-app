<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = "places";

    protected $casts = [
        'photo_references' => \App\Casts\Json::class,
        'opening_hours' => \App\Casts\Json::class,
        'periods' => \App\Casts\Json::class,
        'weekday_text' => \App\Casts\Json::class,
        'location' => \App\Casts\Json::class,
    ];

    protected $fillable = [
        'google_rating',
        // 'users_rating',
        'place_id',
        'name',
        'vicinity',
        'formatted_address',
        'url',
        'photo_references',
        'opening_hours',
        'periods',
        'weekday_text',
        'location',
        'business_status_id',
    ];

    // Define the relationship with the BusinessStatus model
    public function businessStatus()
    {
        return $this->belongsTo(LookupBussinessStatus::class, 'business_status_id');
    }
}
