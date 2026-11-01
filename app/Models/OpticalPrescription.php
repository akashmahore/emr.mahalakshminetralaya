<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpticalPrescription extends Model
{
    protected $table = 'optical_prescriptions';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'eye_examination_id',
        'distance_rx',
        'near_add',
        'intermediate_rx',
        'prism',
        'lens_type',
        'lens_material',
        'lens_design',
        'coatings',
        'tint',
        'frame_model',
        'frame_brand',
        'frame_color',
        'frame_size',
        'frame_shape',
        'frame_material',
        'frame_price',
        'measurements',
        'lens_price',
        'coating_price',
        'tint_price',
        'subtotal',
        'discount',
        'tax',
        'final_price',
        'amount_paid',
        'balance_due',
        'payment_status',
        'status',
        'ordered_date',
        'expected_ready_date',
        'actual_ready_date',
        'dispensed_date',
        'lab_name',
        'lab_order_number',
        'qc_results',
        'dispensing_notes',
        'dispensed_by',
        'warranty_issued',
        'warranty_expiry',
        'notes',
    ];

    protected $casts = [
        'distance_rx' => 'array',
        'near_add' => 'array',
        'intermediate_rx' => 'array',
        'prism' => 'array',
        'coatings' => 'array',
        'tint' => 'array',
        'measurements' => 'array',
        'qc_results' => 'array',
        'frame_price' => 'decimal:2',
        'lens_price' => 'decimal:2',
        'coating_price' => 'decimal:2',
        'tint_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'final_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'ordered_date' => 'date',
        'expected_ready_date' => 'date',
        'actual_ready_date' => 'date',
        'dispensed_date' => 'date',
        'warranty_expiry' => 'date',
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
     * Scope: Ready for collection.
     */
    public function scopeReadyForCollection($query)
    {
        return $query->where('status', 'Ready for Collection');
    }

    /**
     * Scope: Pending QC.
     */
    public function scopePendingQC($query)
    {
        return $query->whereIn('status', ['In Lab', 'Ready for QC']);
    }
}