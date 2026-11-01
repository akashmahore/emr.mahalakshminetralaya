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
        Schema::create('cataract_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            // Lens Grading (LOCS III)
            $table->json('lens_grading')->nullable(); 
            // {re_nuclear, re_cortical, re_psc, le_nuclear, le_cortical, le_psc}
            // Nuclear: 1-6, Cortical: 1-5, PSC: 1-5
            
            // Biometry (IOL Master / A-Scan)
            $table->json('biometry')->nullable();      
            // {re_al, re_k1, re_k1_axis, re_k2, re_acd, re_lt, re_wtw, re_cct, 
            //  le_al, le_k1, le_k1_axis, le_k2, le_acd, le_lt, le_wtw, le_cct}
            
            // Keratometry
            $table->json('keratometry')->nullable();   
            // {re_k_flat, re_k_steep, re_axis, le_k_flat, le_k_steep, le_axis}
            
            // IOL Calculation Results
            $table->json('iol_calculations')->nullable(); 
            // [{eye: 'RE'|'LE', formula: 'SRK-T'|'Haigis'|'Holladay1'|'Barrett'|'HofferQ', 
            //   target_ref, iol_power, iol_model, iol_type, iol_constant, surgeon_factor, predicted_ref}]
            
            // Selected IOL
            $table->json('selected_iol')->nullable();    
            // {re: {model, power, type: 'Monofocal'|'Multifocal'|'Toric'|'EDOF', manufacturer, constant}, 
            //  le: {...}}
            
            // Surgery Planning
            $table->enum('surgery_eye', ['RE', 'LE', 'BOTH', 'None'])->default('None');
            $table->enum('anesthesia_type', ['Topical', 'Peribulbar', 'Retrobulbar', 'General'])->nullable();
            $table->enum('incision_type', ['Clear Corneal', 'Scleral Tunnel', 'Femtosecond'])->nullable();
            $table->text('surgical_notes')->nullable();
            $table->date('tentative_surgery_date')->nullable();
            $table->enum('priority', ['Routine', 'Semi-urgent', 'Urgent'])->default('Routine');
            
            // Pre-op Checklist
            $table->json('preop_checklist')->nullable(); 
            // {biometry_done, a_scan_done, b_scan_done, specular_done, topog_done, 
            //  medical_clearance, blood_work, ecg, consent_signed, iol_ordered}
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cataract_evaluations');
    }
};
