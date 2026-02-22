<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationReason extends Model
{
    protected $fillable = ['specialty_id', 'name'];
}
