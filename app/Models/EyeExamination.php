<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EyeExamination extends Model
{
    protected $table = 'eye_examinations';

    protected $fillable = [
        'consultation_id',
        'patient_id',
        'doctor_id',
        'visual_acuity',
        'refraction',
        'iop',
        'slit_lamp',
        'fundus',
        'diagnoses',
        'management_plan',
        'investigations_ordered',
        'medications_prescribed',
        'surgery_planned',
        'next_review',
        'review_frequency',
        'notes',
    ];

    protected $casts = [
        'visual_acuity' => 'array',
        'refraction' => 'array',
        'iop' => 'array',
        'slit_lamp' => 'array',
        'fundus' => 'array',
        'diagnoses' => 'array',
        'investigations_ordered' => 'array',
        'medications_prescribed' => 'array',
        'surgery_planned' => 'array',
        'next_review' => 'date',
    ];

    /**
     * Get the consultation this examination belongs to.
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Get the patient this examination belongs to.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor who performed this examination.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the cataract evaluation for this examination.
     */
    public function cataractEvaluation(): HasOne
    {
        return $this->hasOne(CataractEvaluation::class);
    }

    /**
     * Get the glaucoma evaluation for this examination.
     */
    public function glaucomaEvaluation(): HasOne
    {
        return $this->hasOne(GlaucomaEvaluation::class);
    }

    /**
     * Get the retina evaluation for this examination.
     */
    public function retinaEvaluation(): HasOne
    {
        return $this->hasOne(RetinaEvaluation::class);
    }

    /**
     * Get the cornea evaluation for this examination.
     */
    public function corneaEvaluation(): HasOne
    {
        return $this->hasOne(CorneaEvaluation::class);
    }

    /**
     * Get the pediatric evaluation for this examination.
     */
    public function pediatricEvaluation(): HasOne
    {
        return $this->hasOne(PediatricEvaluation::class);
    }

    /**
     * Get the oculoplasty evaluation for this examination.
     */
    public function oculoplastyEvaluation(): HasOne
    {
        return $this->hasOne(OculoplastyEvaluation::class);
    }

    /**
     * Scope: Filter by patient.
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope: Filter by doctor.
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope: Recent examinations.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted visual acuity for display.
     */
    public function getFormattedVAAttribute(): string
    {
        if (! $this->visual_acuity) {
            return 'Not recorded';
        }

        $va = $this->visual_acuity;
        $re = ($va['re_corrected'] ?? $va['re_unaided'] ?? '-') . ($va['re_pinhole'] ? " (PH: {$va['re_pinhole']})" : '');
        $le = ($va['le_corrected'] ?? $va['le_unaided'] ?? '-') . ($va['le_pinhole'] ? " (PH: {$va['le_pinhole']})" : '');

        return "RE: {$re} / LE: {$le}";
    }

    /**
     * Get formatted IOP for display.
     */
    public function getFormattedIOPAttribute(): string
    {
        if (! $this->iop) {
            return 'Not recorded';
        }

        $iop = $this->iop;
        $re = $iop['re_value'] ?? '-';
        $le = $iop['le_value'] ?? '-';

        return "RE: {$re} mmHg / LE: {$le} mmHg";
    }
}
