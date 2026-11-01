<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CataractEvaluation extends Model
{
    protected $table = 'cataract_evaluations';

    protected $fillable = [
        'eye_examination_id',
        'patient_id',
        'doctor_id',
        'lens_grading',
        'biometry',
        'keratometry',
        'iol_calculations',
        'selected_iol',
        'surgery_eye',
        'anesthesia_type',
        'incision_type',
        'surgical_notes',
        'tentative_surgery_date',
        'priority',
        'preop_checklist',
    ];

    protected $casts = [
        'lens_grading' => 'array',
        'biometry' => 'array',
        'keratometry' => 'array',
        'iol_calculations' => 'array',
        'selected_iol' => 'array',
        'preop_checklist' => 'array',
        'tentative_surgery_date' => 'date',
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
     * Get IOL calculation summary.
     */
    public function getIOLSummaryAttribute(): string
    {
        if (! $this->iol_calculations || ! is_array($this->iol_calculations)) {
            return 'Not calculated';
        }

        $summary = [];
        foreach ($this->iol_calculations as $calc) {
            $eye = $calc['eye'] ?? '?';
            $formula = $calc['formula'] ?? '?';
            $power = $calc['iol_power'] ?? '?';
            $model = $calc['iol_model'] ?? '?';
            $summary[] = "{$eye}: {$formula} - {$power}D ({$model})";
        }

        return implode('; ', $summary);
    }
}
