<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorneaEvaluation extends Model
{
    protected $table = 'cornea_evaluations';

    protected $fillable = [
        'eye_examination_id',
        'patient_id',
        'doctor_id',
        'topography',
        'pachymetry',
        'specular_microscopy',
        'tear_film',
        'aberrations',
        'keratoconus',
        'refractive_plan',
        'cxl_protocol',
        'transplant_plan',
    ];

    protected $casts = [
        'topography' => 'array',
        'pachymetry' => 'array',
        'specular_microscopy' => 'array',
        'tear_film' => 'array',
        'aberrations' => 'array',
        'keratoconus' => 'array',
        'refractive_plan' => 'array',
        'cxl_protocol' => 'array',
        'transplant_plan' => 'array',
    ];

    public function eyeExamination(): BelongsTo
    {
        return $this->belongsTo(EyeExamination::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
