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
        Schema::create('glaucoma_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            // Gonioscopy (Shaffer / Spaeth)
            $table->json('gonioscopy')->nullable();    
            // {re_shaffer_nasal, re_shaffer_temporal, re_shaffer_superior, re_shaffer_inferior,
            //  re_spaeth_nasal, re_spaeth_temporal, re_spaeth_superior, re_spaeth_inferior,
            //  re_pas, re_iris_config, re_trabecular_meshwork,
            //  le_shaffer_nasal, le_shaffer_temporal, le_shaffer_superior, le_shaffer_inferior,
            //  le_spaeth_nasal, le_spaeth_temporal, le_spaeth_superior, le_spaeth_inferior,
            //  le_pas, le_iris_config, le_trabecular_meshwork}
            
            // Optic Disc Assessment
            $table->json('optic_disc')->nullable();    
            // {re_cdr_v, re_cdr_h, re_rim_superior, re_rim_inferior, re_rim_nasal, re_rim_temporal,
            //  re_disc_size, re_hemorrhage, re_notching, re_pallor,
            //  le_cdr_v, le_cdr_h, le_rim_superior, le_rim_inferior, le_rim_nasal, le_rim_temporal,
            //  le_disc_size, le_hemorrhage, le_notching, le_pallor}
            
            // RNFL OCT
            $table->json('rnfl_oct')->nullable();       
            // {re_avg, re_superior, re_inferior, re_nasal, re_temporal, re_tsni,
            //  le_avg, le_superior, le_inferior, le_nasal, le_temporal, le_tsni,
            //  re_symmetry, le_symmetry, re_progression, le_progression}
            
            // Visual Field (Humphrey)
            $table->json('visual_field')->nullable();   
            // {re_pattern, re_md, re_psd, re_vfi, re_gli, re_fixation_loss, re_false_pos, re_false_neg,
            //  le_pattern, le_md, le_psd, le_vfi, le_gli, le_fixation_loss, le_false_pos, le_false_neg,
            //  re_test_type: '24-2'|'30-2'|'10-2', le_test_type, re_reliability, le_reliability}
            
            // Macular OCT (for ganglion cell analysis)
            $table->json('gccl_oct')->nullable();       
            // {re_avg, re_minimum, re_sector, le_avg, le_minimum, le_sector}
            
            // Current Medications
            $table->json('current_medications')->nullable(); 
            // [{eye: 'RE'|'LE'|'OU', drug, concentration, frequency, started_date, compliance, side_effects}]
            
            // Target IOP
            $table->integer('target_iop_re')->nullable();
            $table->integer('target_iop_le')->nullable();
            
            // Glaucoma Type & Stage
            $table->json('glaucoma_type')->nullable();  
            // {re_type: 'POAG'|'PACG'|'NTG'|'Secondary'|'Suspect', re_stage: 'Early'|'Moderate'|'Advanced'|'Severe',
            //  le_type, le_stage}
            
            // Laser/Surgery History
            $table->json('laser_history')->nullable();  
            // [{eye, type: 'SLT'|'ALT'|'PI'|'CPC'|'MPL', date, outcome, complications}]
            $table->json('surgery_history')->nullable(); 
            // [{eye, type: 'Trabeculectomy'|'Tube'|'MIGS'|'Cyclophotocoagulation', date, outcome, complications}]
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glaucoma_evaluations');
    }
};