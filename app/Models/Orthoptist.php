<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orthoptist extends Model
{
    protected $table = 'orthoptist';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'img_url',
        'qualification',
        'registration_number',
        'specialization',
        'experience_years',
        'consultation_fee',
        'x',
        'y',
        'z',
        'ion_user_id',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'consultation_fee' => 'decimal:2',
    ];
}
