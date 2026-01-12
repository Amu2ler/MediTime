<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DoctorProfile;

class Specialty extends Model
{
    protected $fillable = ['name'];
    
    public function doctorProfiles()
    {
        return $this->hasMany(DoctorProfile::class);
    }

    public function consultationReasons()
    {
        return $this->hasMany(ConsultationReason::class);
    }
}



