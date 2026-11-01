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
        Schema::create('eye_camp_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_camp_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patient'); // Null if not registered
            
            // Temp patient info (for walk-ins not yet registered)
            $table->string('temp_name');
            $table->integer('age');
            $table->enum('sex', ['M', 'F', 'O']);
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('id_proof')->nullable();          // Aadhar, Voter ID, etc.
            $table->string('id_number')->nullable();
            
            // Screening Results
            $table->json('visual_acuity')->nullable();       // {re_unaided, re_pinhole, le_unaided, le_pinhole, near}
            $table->json('refraction_screening')->nullable(); // {re_sph, re_cyl, re_axis, le_sph, le_cyl, le_axis}
            $table->json('iop_screening')->nullable();       // {re, le, method}
            $table->json('anterior_segment')->nullable();    // {cataract, pterygium, corneal_opacity, etc.}
            $table->json('posterior_segment')->nullable();   // {disc, macula, vessels, etc.}
            
            // Findings Summary
            $table->json('findings')->nullable();            // [{category, condition, eye, severity, description}]
            
            // Referral
            $table->json('referral')->nullable();            // {type: 'Cataract'|'Glaucoma'|'Retina'|'Cornea'|'Pediatric'|'Oculoplasty'|'Optical'|'Contact Lens'|'Medical'|'None',
            //  urgency: 'Routine'|'Semi-urgent'|'Urgent'|'Emergency',
            //  referred_to: 'Hospital'|'Specialist'|'Camp Doctor'|'Other',
            //  notes, preferred_date}
            
            // Conversion Tracking
            $table->boolean('registered_as_patient')->default(false);
            $table->date('registration_date')->nullable();
            $table->boolean('followed_up')->default(false);
            $table->date('followup_date')->nullable();
            $table->string('followup_outcome')->nullable();  // 'Surgery Booked', 'Optical Ordered', 'Medication Started', 'No Show', etc.
            
            // Consent
            $table->boolean('consent_screening')->default(false);
            $table->boolean('consent_data')->default(false);
            $table->boolean('consent_referral')->default(false);
            $table->date('consent_date')->nullable();
            
            // Staff who screened
            $table->string('screened_by')->nullable();
            $table->string('screened_by_role')->nullable();  // Doctor, Optometrist, Nurse, Technician
            
            $table->timestamps();
            $table->index(['eye_camp_id', 'registered_as_patient']);
            $table->index(['phone']);
            $table->index(['referral']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_camp_patients');
    }
};