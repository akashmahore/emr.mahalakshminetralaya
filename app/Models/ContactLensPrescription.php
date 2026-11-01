<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactLensPrescription extends Model
{
    protected $table = 'contact_lens_prescriptions';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'eye_examination_id',
        're_specs',
        'le_specs',
        'fitting_eval',
        'trial_lenses',
        'wearing_schedule',
        'replacement_days',
        'wearing_hours_per_day',
        'care_system',
        'first_followup',
        'second_followup',
        'followup_schedule',
        'patient_education',
        'compliance_issues',
        'status',
        'lens_price',
        'fitting_fee',
        'solution_price',
        'total_price',
        'amount_paid',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        're_specs' => 'array',
        'le_specs' => 'array',
        'fitting_eval' => 'array',
        'trial_lenses' => 'array',
        'care_system' => 'array',
        'followup_schedule' => 'array',
        'patient_education' => 'array',
        'compliance_issues' => 'array',
        'lens_price' => 'decimal:2',
        'fitting_fee' => 'decimal:2',
        'solution_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'first_followup' => 'date',
        'second_followup' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function eyeExamination(): BelongsTo
    {
        return $this->belongsTo(EyeExamination::class);
    }

    /**
     * Scope: Due for followup.
     */
    public function scopeDueForFollowup($query)
    {
        return $query->where('status', 'Follow-up Due')
            ->whereNotNull('first_followup')
            ->where('first_followup', '<=', now()->toDateString());
    }
}
