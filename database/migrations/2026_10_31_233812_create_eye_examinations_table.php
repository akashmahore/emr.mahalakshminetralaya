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
        Schema::create('eye_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->nullable()->constrained('consultation')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            // Visual Acuity
            $table->json('visual_acuity')->nullable(); 
            // {re_unaided, re_pinhole, re_corrected, le_unaided, le_pinhole, le_corrected, re_near, le_near}
            
            // Refraction
            $table->json('refraction')->nullable();    
            // {re_sph, re_cyl, re_axis, le_sph, le_cyl, le_axis, add_near, pd_distance, pd_near}
            
            // IOP (Intraocular Pressure)
            $table->json('iop')->nullable();           
            // {re_value, re_method, re_time, le_value, le_method, le_time, cct_re, cct_le, cct_method}
            
            // Slit Lamp (Anterior Segment)
            $table->json('slit_lamp')->nullable();     
            // {re_lids, re_lashes, re_conjunctiva, re_cornea, re_ac, re_iris, re_lens, le_lids, le_lashes, le_conjunctiva, le_cornea, le_ac, le_iris, le_lens}
            
            // Fundus (Posterior Segment)
            $table->json('fundus')->nullable();        
            // {re_vitreous, re_optic_disc_cdr_v, re_optic_disc_cdr_h, re_macula, re_vessels, re_periphery, le_vitreous, le_optic_disc_cdr_v, le_optic_disc_cdr_h, le_macula, le_vessels, le_periphery}
            
            // Diagnosis (ICD-10 + custom)
            $table->json('diagnoses')->nullable();     
            // [{eye: 'RE'|'LE'|'OU', icd10, description, severity, laterality, confidence}]
            
            // Management Plan
            $table->text('management_plan')->nullable();
            $table->json('investigations_ordered')->nullable();  // Lab, OCT, VF, FFA, etc.
            $table->json('medications_prescribed')->nullable(); // Eye drops, oral meds
            $table->json('surgery_planned')->nullable();       // Cataract, glaucoma, retina, etc.
            $table->date('next_review')->nullable();
            $table->enum('review_frequency', ['1_day', '1_week', '2_weeks', '1_month', '3_months', '6_months', '1_year'])->nullable();
            
            // Additional notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
            $table->index(['doctor_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_examinations');
    }
};
