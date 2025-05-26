<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'description',
    'location',
    'datetime',
    'category_id',
    'user_id',
    'capacity',
];


    protected $casts = [
    'datetime' => 'datetime',
];


    // Relations

    /**
     * L'utilisateur (organisateur) qui a créé l'événement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La catégorie de l'événement.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Les réservations liées à cet événement.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
