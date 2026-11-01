<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PediatricEvaluation extends Model
{
    protected $table = 'pediatric_evaluations';

    protected $fillable = [
        'eye_examination_id',
        'patient_id',
        'doctor_id',
        'visual_acuity_pediatric',
        'squint',
        'binocular_vision',
        'amblyopia',
        'cycloplegic_refraction',
        'anterior_segment_ped',
        'posterior_segment_ped',
        'developmental',
        'treatment_plan_ped',
    ];

    protected $casts = [
        'visual_acuity_pediatric' => 'array',
        'squint' => 'array',
        'binocular_vision' => 'array',
        'amblyopia' => 'array',
        'cycloplegic_refraction' => 'array',
        'anterior_segment_ped' => 'array',
        'posterior_segment_ped' => 'array',
        'developmental' => 'array',
        'treatment_plan_ped' => 'array',
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