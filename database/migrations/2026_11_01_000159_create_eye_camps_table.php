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
        Schema::create('eye_camps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('camp_code')->unique();           // e.g., "EC-2026-001"
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('venue');
            $table->string('venue_address')->nullable();
            $table->string('organizer')->nullable();         // NGO, Corporate, Government, Self
            $table->foreignId('doctor_id')->nullable()->constrained('doctor');
            $table->json('supporting_staff')->nullable();    // [{name, role, contact}]
            $table->string('sponsor')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->decimal('expenses', 12, 2)->nullable();
            
            // Target Population
            $table->json('target_population')->nullable();   // {age_groups, risk_groups, geographic_area, expected_count}
            
            // Screening Equipment
            $table->json('equipment')->nullable();           // {vision_charts, autorefractor, tonopen, slit_lamp, direct_ophthalmoscope, etc.}
            
            // Status
            $table->enum('status', ['Planned', 'Approved', 'In Progress', 'Completed', 'Cancelled', 'Postponed'])->default('Planned');
            
            // Outcomes
            $table->integer('patients_screened')->default(0);
            $table->integer('cataract_identified')->default(0);
            $table->integer('glaucoma_suspects')->default(0);
            $table->integer('retina_referrals')->default(0);
            $table->integer('cornea_referrals')->default(0);
            $table->integer('pediatric_cases')->default(0);
            $table->integer('refractive_errors')->default(0);
            $table->integer('presbyopia_cases')->default(0);
            $table->integer('squint_cases')->default(0);
            $table->integer('ptosis_cases')->default(0);
            $table->integer('other_referrals')->default(0);
            $table->integer('surgeries_booked')->default(0);
            $table->integer('optical_prescriptions')->default(0);
            $table->integer('contact_lens_fittings')->default(0);
            $table->integer('medicines_dispensed')->default(0);
            
            // Conversion Tracking
            $table->integer('converted_to_surgery')->default(0);
            $table->integer('converted_to_optical')->default(0);
            $table->integer('converted_to_clinic')->default(0);
            
            // Feedback
            $table->text('notes')->nullable();
            $table->json('patient_feedback')->nullable();    // [{rating, comments}]
            $table->json('staff_feedback')->nullable();      // [{rating, comments, suggestions}]
            
            // Photos/Documents
            $table->json('photos')->nullable();              // [{url, caption, type}]
            $table->json('documents')->nullable();           // [{name, url, type}]
            
            $table->timestamps();
            $table->index(['date', 'status']);
            $table->index(['camp_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_camps');
    }
};
