<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OculoplastyEvaluation extends Model
{
    protected $table = 'oculoplasty_evaluations';

    protected $fillable = [
        'eye_examination_id',
        'patient_id',
        'doctor_id',
        'eyelid',
        'lacrimal',
        'orbit',
        'facial',
        'lesions',
        'surgical_plan',
    ];

    protected $casts = [
        'eyelid' => 'array',
        'lacrimal' => 'array',
        'orbit' => 'array',
        'facial' => 'array',
        'lesions' => 'array',
        'surgical_plan' => 'array',
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
