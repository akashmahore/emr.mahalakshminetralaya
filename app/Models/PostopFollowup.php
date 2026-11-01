<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostopFollowup extends Model
{
    protected $table = 'postop_followups';

    protected $fillable = [
        'surgery_id',
        'patient_id',
        'doctor_id',
        'postop_day',
        'visit_date',
        'visit_type',
        'visual_acuity',
        'iop',
        'slit_lamp',
        'fundus',
        'complications',
        'medications',
        'medication_changes',
        'investigations_ordered',
        'refraction',
        'patient_reported',
        'advice',
        'next_review',
        'next_review_type',
    ];

    protected $casts = [
        'visual_acuity' => 'array',
        'iop' => 'array',
        'slit_lamp' => 'array',
        'fundus' => 'array',
        'complications' => 'array',
        'medication_changes' => 'array',
        'investigations_ordered' => 'array',
        'refraction' => 'array',
        'patient_reported' => 'array',
        'visit_date' => 'date',
        'next_review' => 'date',
    ];

    public function surgery(): BelongsTo
    {
        return $this->belongsTo(OphthalmicSurgery::class, 'surgery_id');
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
     * Scope: Overdue followups.
     */
    public function scopeOverdue($query)
    {
        return $query->where('next_review', '<', now()->toDateString())
            ->where('next_review_type', '!=', 'Discharge');
    }
}
