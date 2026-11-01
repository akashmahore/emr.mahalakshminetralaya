<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetinaEvaluation extends Model
{
    protected $table = 'retina_evaluations';

    protected $fillable = [
        'eye_examination_id',
        'patient_id',
        'doctor_id',
        'retina_findings',
        'oct_macular',
        'ffa',
        'icga',
        'octa',
        'b_scan',
        'diagnosis_classification',
        'injection_log',
        'laser_log',
        'surgery_log',
    ];

    protected $casts = [
        'retina_findings' => 'array',
        'oct_macular' => 'array',
        'ffa' => 'array',
        'icga' => 'array',
        'octa' => 'array',
        'b_scan' => 'array',
        'diagnosis_classification' => 'array',
        'injection_log' => 'array',
        'laser_log' => 'array',
        'surgery_log' => 'array',
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

    /**
     * Get next injection due date.
     */
    public function getNextInjectionDueAttribute(): ?string
    {
        if (! $this->injection_log || ! is_array($this->injection_log)) {
            return null;
        }

        $lastInjection = collect($this->injection_log)->sortByDesc('date')->first();
        if (! $lastInjection || ! isset($lastInjection['next_due'])) {
            return null;
        }

        return $lastInjection['next_due'];
    }
}