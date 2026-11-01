<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EyeCamp extends Model
{
    protected $table = 'eye_camps';

    protected $fillable = [
        'name',
        'camp_code',
        'date',
        'start_time',
        'end_time',
        'venue',
        'venue_address',
        'organizer',
        'doctor_id',
        'supporting_staff',
        'sponsor',
        'budget',
        'expenses',
        'target_population',
        'equipment',
        'status',
        'patients_screened',
        'cataract_identified',
        'glaucoma_suspects',
        'retina_referrals',
        'cornea_referrals',
        'pediatric_cases',
        'refractive_errors',
        'presbyopia_cases',
        'squint_cases',
        'ptosis_cases',
        'other_referrals',
        'surgeries_booked',
        'optical_prescriptions',
        'contact_lens_fittings',
        'medicines_dispensed',
        'converted_to_surgery',
        'converted_to_optical',
        'converted_to_clinic',
        'notes',
        'patient_feedback',
        'staff_feedback',
        'photos',
        'documents',
    ];

    protected $casts = [
        'supporting_staff' => 'array',
        'target_population' => 'array',
        'equipment' => 'array',
        'patient_feedback' => 'array',
        'staff_feedback' => 'array',
        'photos' => 'array',
        'documents' => 'array',
        'date' => 'date',
        'budget' => 'decimal:2',
        'expenses' => 'decimal:2',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(EyeCampPatient::class, 'eye_camp_id');
    }

    /**
     * Scope: Upcoming camps.
     */
    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('status', '!=', 'Completed')
            ->where('date', '>=', now())
            ->where('date', '<=', now()->addDays($days))
            ->orderBy('date');
    }

    /**
     * Get conversion rates.
     */
    public function getConversionRatesAttribute(): array
    {
        if ($this->patients_screened == 0) {
            return ['surgery' => 0, 'optical' => 0, 'clinic' => 0];
        }

        return [
            'surgery' => round(($this->converted_to_surgery / $this->patients_screened) * 100, 1),
            'optical' => round(($this->converted_to_optical / $this->patients_screened) * 100, 1),
            'clinic' => round(($this->converted_to_clinic / $this->patients_screened) * 100, 1),
        ];
    }
}
