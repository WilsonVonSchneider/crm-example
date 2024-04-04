<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'owner_id',
        'address',
        'town_city',
        'country',
        'post_code',
        'phone'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
