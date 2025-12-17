<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
