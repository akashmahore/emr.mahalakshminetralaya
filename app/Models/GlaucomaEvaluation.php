<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlaucomaEvaluation extends Model
{
    protected $table = 'glaucoma_evaluations';

    protected $fillable = [
        'eye_examination_id',
        'patient_id',
        'doctor_id',
        'gonioscopy',
        'optic_disc',
        'rnfl_oct',
        'visual_field',
        'gccl_oct',
        'current_medications',
        'target_iop_re',
        'target_iop_le',
        'glaucoma_type',
        'laser_history',
        'surgery_history',
    ];

    protected $casts = [
        'gonioscopy' => 'array',
        'optic_disc' => 'array',
        'rnfl_oct' => 'array',
        'visual_field' => 'array',
        'gccl_oct' => 'array',
        'current_medications' => 'array',
        'glaucoma_type' => 'array',
        'laser_history' => 'array',
        'surgery_history' => 'array',
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
     * Get VF progression status.
     */
    public function getVFProgressionAttribute(): string
    {
        if (! $this->visual_field || ! is_array($this->visual_field)) {
            return 'Not available';
        }

        $vf = $this->visual_field;
        $reProg = $vf['re_progression'] ?? 'Unknown';
        $leProg = $vf['le_progression'] ?? 'Unknown';

        return "RE: {$reProg} / LE: {$leProg}";
    }
}