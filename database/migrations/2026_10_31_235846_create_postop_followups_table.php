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
        Schema::create('postop_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surgery_id')->constrained('ophthalmic_surgeries')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            $table->integer('postop_day');             // 1, 7, 30, 90, 180, 365
            $table->date('visit_date');
            $table->enum('visit_type', ['Scheduled', 'Unscheduled', 'Emergency', 'Phone', 'Video']);
            
            // Visual Acuity
            $table->json('visual_acuity')->nullable(); // {re_unaided, re_corrected, re_near, le_unaided, le_corrected, le_near}
            
            // IOP
            $table->json('iop')->nullable();           // {re, le, method, time}
            
            // Slit Lamp (Anterior Segment)
            $table->json('slit_lamp')->nullable();     // {re_cornea, re_ac, re_iris, re_lens, re_wound, re_sutures,
            //  le_cornea, le_ac, le_iris, le_lens, le_wound, le_sutures}
            
            // Fundus (Posterior Segment)
            $table->json('fundus')->nullable();        // {re_vitreous, re_optic_disc, re_macula, re_vessels, re_periphery,
            //  le_vitreous, le_optic_disc, le_macula, le_vessels, le_periphery}
            
            // Complications
            $table->json('complications')->nullable(); 
            // [{type: 'Infection'|'Inflammation'|'Pressure Spike'|'Hyphema'|'CME'|'RD'|'IOL Dislocation'|'Wound Leak'|'Endophthalmitis',
            //   eye, severity: 'Mild'|'Moderate'|'Severe', action_taken, resolved}]
            
            // Medications
            $table->text('medications')->nullable();   // Current eye drops schedule
            $table->json('medication_changes')->nullable(); // {started, stopped, changed}
            
            // Additional Tests Ordered
            $table->json('investigations_ordered')->nullable(); // OCT, VF, B-scan, etc.
            
            // Refraction (Post-cataract)
            $table->json('refraction')->nullable();    // {re_sph, re_cyl, re_axis, le_sph, le_cyl, le_axis, add_near}
            
            // Patient Reported Outcomes
            $table->json('patient_reported')->nullable(); // {pain_score, vision_quality, glare, halos, dry_eye, satisfaction}
            
            // Advice & Next Review
            $table->text('advice')->nullable();
            $table->date('next_review')->nullable();
            $table->enum('next_review_type', ['Routine', 'Urgent', 'SOS', 'Discharge']);
            
            $table->timestamps();
            $table->unique(['surgery_id', 'postop_day']);
            $table->index(['patient_id', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postop_followups');
    }
};