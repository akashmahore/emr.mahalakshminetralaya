<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EyeCampPatient extends Model
{
    protected $table = 'eye_camp_patients';

    protected $fillable = [
        'eye_camp_id',
        'patient_id',
        'temp_name',
        'age',
        'sex',
        'phone',
        'address',
        'id_proof',
        'id_number',
        'visual_acuity',
        'refraction_screening',
        'iop_screening',
        'anterior_segment',
        'posterior_segment',
        'findings',
        'referral',
        'registered_as_patient',
        'registration_date',
        'followed_up',
        'followup_date',
        'followup_outcome',
        'consent_screening',
        'consent_data',
        'consent_referral',
        'consent_date',
        'screened_by',
        'screened_by_role',
    ];

    protected $casts = [
        'visual_acuity' => 'array',
        'refraction_screening' => 'array',
        'iop_screening' => 'array',
        'anterior_segment' => 'array',
        'posterior_segment' => 'array',
        'findings' => 'array',
        'referral' => 'array',
        'registered_as_patient' => 'boolean',
        'followed_up' => 'boolean',
        'consent_screening' => 'boolean',
        'consent_data' => 'boolean',
        'consent_referral' => 'boolean',
        'consent_date' => 'date',
        'registration_date' => 'date',
        'followup_date' => 'date',
    ];

    public function eyeCamp(): BelongsTo
    {
        return $this->belongsTo(EyeCamp::class, 'eye_camp_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Scope: Patients with referrals.
     */
    public function scopeWithReferral($query)
    {
        return $query->whereNotNull('referral')
            ->where('referral', '!=', '[]');
    }

    /**
     * Scope: Converted patients.
     */
    public function scopeConverted($query)
    {
        return $query->where('registered_as_patient', true);
    }
}
