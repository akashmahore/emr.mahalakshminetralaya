<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pediatric_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            // Visual Acuity (Age-appropriate)
            $table->json('visual_acuity_pediatric')->nullable(); 
            // {re_method: 'Snellen'|'Lea'|'HOTV'|'Cardiff'|'Teller', re_unaided, re_corrected,
            //  le_method, le_unaided, le_corrected, fixation_preference, age_appropriate}
            
            // Squint/Strabismus Assessment
            $table->json('squint')->nullable();             
            // {type: 'Eso'|'Exo'|'Hyper'|'Hypo'|'Cyclo'|'None',
            //  angle_re_distance, angle_re_near, angle_le_distance, angle_le_near,
            //  comitance, ductions, versions, head_posture, nystagmus, cover_test}
            
            // Binocular Vision
            $table->json('binocular_vision')->nullable();   
            // {worth_4_dot, randot_stereoacuity, titmus_stereoacuity, fusional_vergence,
            //  npc, accommodation, ac_a_ratio, suppression, amblyopia_risk}
            
            // Amblyopia Assessment
            $table->json('amblyopia')->nullable();          
            // {re_va_best_corrected, le_va_best_corrected, interocular_difference,
            //  type: 'Strabismic'|'Anisometropic'|'Deprivation'|'Mixed',
            //  treatment: 'Patching'|'Atropine'|'Bangerter'|'Vision Therapy',
            //  patching_hours, compliance, progress_notes}
            
            // Refraction (Cycloplegic)
            $table->json('cycloplegic_refraction')->nullable(); 
            // {agent: 'Cyclopentolate'|'Atropine'|'Tropicamide', concentration,
            //  re_sph, re_cyl, re_axis, le_sph, le_cyl, le_axis, add_near}
            
            // Anterior Segment (Pediatric)
            $table->json('anterior_segment_ped')->nullable(); 
            // {re_cornea, re_ac, re_iris, re_lens, le_cornea, le_ac, le_iris, le_lens,
            //  cataract: 'None'|'Congenital'|'Developmental'|'Traumatic',
            //  glaucoma: 'None'|'Primary Congenital'|'Juvenile'|'Secondary'}
            
            // Posterior Segment (Pediatric)
            $table->json('posterior_segment_ped')->nullable(); 
            // {re_fundus, le_fundus, rop_stage: 'None'|'Stage 1'|'Stage 2'|'Stage 3'|'Stage 4'|'Stage 5',
            //  rop_zone: 'I'|'II'|'III', rop_plus_disease, rop_ap_rop,
            //  retinoblastoma: 'None'|'Suspected'|'Confirmed', rb_group}
            
            // Developmental Milestones
            $table->json('developmental')->nullable();      
            // {fixation_follows, tracking, visual_behavior_concerns, ptosis, epicanthus, telecanthus}
            
            // Treatment Plan
            $table->json('treatment_plan_ped')->nullable(); 
            // {surgery: 'Squint'|'Cataract'|'Ptosis'|'Glaucoma'|'ROP Laser'|'ROP Anti-VEGF',
            //  optical_correction, patching_schedule, vision_therapy, referral, followup_interval}
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pediatric_evaluations');
    }
};
