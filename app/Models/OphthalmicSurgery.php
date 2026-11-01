<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OphthalmicSurgery extends Model
{
    protected $table = 'ophthalmic_surgeries';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'assistant_doctor_id',
        'anesthetist_id',
        'cataract_evaluation_id',
        'glaucoma_evaluation_id',
        'retina_evaluation_id',
        'cornea_evaluation_id',
        'pediatric_evaluation_id',
        'oculoplasty_evaluation_id',
        'eye',
        'procedure_name',
        'procedure_code',
        'category',
        'iol_details',
        'viscoelastic',
        'instruments_used',
        'phaco_settings',
        'vitrectomy_settings',
        'operative_steps',
        'complications',
        'complication_details',
        'postop_instructions',
        'postop_medications',
        'surgery_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'anesthesia',
        'anesthesia_details',
        'status',
        'cancellation_reason',
        'theatre_number',
        'priority',
        'consent_signed',
        'consent_date',
        'consent_witness',
    ];

    protected $casts = [
        'iol_details' => 'array',
        'viscoelastic' => 'array',
        'instruments_used' => 'array',
        'phaco_settings' => 'array',
        'vitrectomy_settings' => 'array',
        'surgery_date' => 'date',
        'consent_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function surgeon(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'assistant_doctor_id');
    }

    public function anesthetist(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'anesthetist_id');
    }

    public function cataractEvaluation(): BelongsTo
    {
        return $this->belongsTo(CataractEvaluation::class);
    }

    public function glaucomaEvaluation(): BelongsTo
    {
        return $this->belongsTo(GlaucomaEvaluation::class);
    }

    public function retinaEvaluation(): BelongsTo
    {
        return $this->belongsTo(RetinaEvaluation::class);
    }

    public function corneaEvaluation(): BelongsTo
    {
        return $this->belongsTo(CorneaEvaluation::class);
    }

    public function pediatricEvaluation(): BelongsTo
    {
        return $this->belongsTo(PediatricEvaluation::class);
    }

    public function oculoplastyEvaluation(): BelongsTo
    {
        return $this->belongsTo(OculoplastyEvaluation::class);
    }

    public function followups(): HasMany
    {
        return $this->hasMany(PostopFollowup::class, 'surgery_id');
    }

    /**
     * Scope: Upcoming surgeries.
     */
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('status', 'Scheduled')
            ->where('surgery_date', '<=', now()->addDays($days))
            ->where('surgery_date', '>=', now())
            ->orderBy('surgery_date')
            ->orderBy('start_time');
    }

    /**
     * Scope: Today's surgeries.
     */
    public function scopeToday($query)
    {
        return $query->where('surgery_date', now()->toDateString())
            ->whereIn('status', ['Scheduled', 'In Progress'])
            ->orderBy('start_time');
    }

    /**
     * Get formatted procedure display.
     */
    public function getFormattedProcedureAttribute(): string
    {
        $eye = $this->eye === 'BOTH' ? 'OU' : $this->eye;
        return "{$eye}: {$this->procedure_name}";
    }
}
