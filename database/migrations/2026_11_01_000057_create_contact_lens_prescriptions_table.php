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
        Schema::create('contact_lens_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            $table->foreignId('eye_examination_id')->nullable()->constrained()->nullOnDelete();
            
            // Right Eye Specifications
            $table->json('re_specs')->nullable();       
            // {brand, base_curve, diameter, power_sph, power_cyl, power_axis, add_power, 
            //  material: 'Hydrogel'|'Silicone Hydrogel'|'RGP'|'Hybrid'|'Scleral',
            //  modality: 'Daily'|'Biweekly'|'Monthly'|'Quarterly'|'Annual'|'Ortho-K'|'Extended Wear',
            //  design: 'Spherical'|'Toric'|'Multifocal'|'Toric Multifocal'|'Ortho-K'|'Scleral',
            //  color: 'Clear'|'Enhancement'|'Opaque'|'Custom',
            //  uv_blocking: true/false, handling_tint: true/false}
            
            // Left Eye Specifications
            $table->json('le_specs')->nullable();        // Same structure as re_specs
            
            // Fitting Evaluation
            $table->json('fitting_eval')->nullable();   
            // {re_centering, re_movement, re_rotation, re_coverage, re_lag, re_tightness,
            //  le_centering, le_movement, le_rotation, le_coverage, le_lag, le_tightness,
            //  re_fluorescein_pattern, le_fluorescein_pattern}
            
            // Trial Lenses
            $table->json('trial_lenses')->nullable();   
            // [{eye, brand, bc, dia, power, cyl, axis, add, material, wearing_time, comfort, vision}]
            
            // Wearing Schedule
            $table->enum('wearing_schedule', ['Daily', 'Biweekly', 'Monthly', 'Quarterly', 'Annual', 'Ortho-K', 'Extended Wear', 'Flexible'])->nullable();
            $table->integer('replacement_days')->nullable();
            $table->string('wearing_hours_per_day')->nullable(); // e.g., "8-12", "All waking hours"
            
            // Solution & Care
            $table->json('care_system')->nullable();    
            // {solution_brand, solution_type: 'Multipurpose'|'Hydrogen Peroxide'|'Saline'|'Enzymatic',
            //  cleaning_method: 'Rub & Rinse'|'No-rub'|'Case cleaning', replacement_schedule}
            
            // Follow-up
            $table->date('first_followup')->nullable();
            $table->date('second_followup')->nullable();
            $table->json('followup_schedule')->nullable(); // [{interval_days, purpose}]
            
            // Patient Education
            $table->json('patient_education')->nullable(); 
            // {insertion_removal_taught, care_instructions_given, wearing_schedule_explained, 
            //  warning_signs_explained, emergency_contact_given, written_material_provided}
            
            // Compliance & Issues
            $table->json('compliance_issues')->nullable(); 
            // [{date, issue: 'Discomfort'|'Redness'|'Blurred Vision'|'Infection'|'Deposit'|'Tear',
            //   action_taken, resolved}]
            
            // Status
            $table->enum('status', ['Fitting', 'Trial', 'Prescribed', 'Ordered', 'Dispensed', 'Follow-up Due', 'Completed', 'Discontinued', 'Remake'])->default('Fitting');
            
            // Pricing
            $table->decimal('lens_price', 10, 2)->nullable();
            $table->decimal('fitting_fee', 10, 2)->nullable();
            $table->decimal('solution_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->enum('payment_status', ['Pending', 'Partial', 'Paid'])->default('Pending');
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
            $table->index(['status', 'first_followup']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_lens_prescriptions');
    }
};
