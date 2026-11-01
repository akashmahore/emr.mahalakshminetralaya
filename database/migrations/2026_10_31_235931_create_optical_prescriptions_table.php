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
        Schema::create('optical_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            $table->foreignId('eye_examination_id')->nullable()->constrained()->nullOnDelete();
            
            // Distance Prescription
            $table->json('distance_rx')->nullable();    
            // {re_sph, re_cyl, re_axis, le_sph, le_cyl, le_axis, pd_distance}
            
            // Near Add
            $table->json('near_add')->nullable();       
            // {add_power, pd_near, working_distance}
            
            // Intermediate Prescription
            $table->json('intermediate_rx')->nullable(); 
            // {re_sph, re_cyl, re_axis, le_sph, le_cyl, le_axis, pd_intermediate}
            
            // Prism
            $table->json('prism')->nullable();          
            // {re_horizontal, re_vertical, re_base, le_horizontal, le_vertical, le_base}
            
            // Lens Type
            $table->enum('lens_type', ['Single Vision', 'Bifocal', 'Trifocal', 'Progressive', 'Office/Computer', 'Relax'])->nullable();
            $table->string('lens_material')->nullable();  // CR39, Polycarbonate, Trivex, High Index 1.60, 1.67, 1.74
            $table->string('lens_design')->nullable();    // Standard, Digital/Freeform, Aspheric, Atoric
            
            // Coatings
            $table->json('coatings')->nullable();       
            // {anti_reflective: true/false, blue_cut: true/false, photochromic: true/false, 
            //  scratch_resistant: true/false, uv_protection: true/false, hydrophobic: true/false, 
            //  anti_fog: true/false, anti_static: true/false, mirror: 'None'|'Silver'|'Gold'|'Blue'|'Green'}
            
            // Tint
            $table->json('tint')->nullable();           
            // {color: 'Gray'|'Brown'|'Green'|'Blue'|'Pink'|'Yellow', density: 'Light'|'Medium'|'Dark', 
            //  gradient: true/false, transition: 'None'|'Photochromic'|'Drivewear'}
            
            // Frame
            $table->string('frame_model')->nullable();
            $table->string('frame_brand')->nullable();
            $table->string('frame_color')->nullable();
            $table->string('frame_size')->nullable();     // e.g., "54-18-140"
            $table->string('frame_shape')->nullable();    // Round, Square, Rectangle, Oval, Cat-eye, Aviator
            $table->string('frame_material')->nullable(); // Acetate, Metal, Titanium, TR90, Ultem, Wood
            $table->decimal('frame_price', 10, 2)->nullable();
            
            // Lens Measurements
            $table->json('measurements')->nullable();   
            // {seg_height, fitting_height, vertex_distance, pantoscopic_tilt, wrap_angle, 
            //  optical_center_decentration, inset}
            
            // Pricing
            $table->decimal('lens_price', 10, 2)->nullable();
            $table->decimal('coating_price', 10, 2)->nullable();
            $table->decimal('tint_price', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2)->nullable();
            
            // Payment
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->nullable();
            $table->enum('payment_status', ['Pending', 'Partial', 'Paid', 'Refunded'])->default('Pending');
            
            // Status Workflow
            $table->enum('status', ['Prescribed', 'Quoted', 'Ordered', 'In Lab', 'Ready for QC', 'QC Passed', 'Ready for Collection', 'Dispensed', 'Cancelled', 'Remake'])->default('Prescribed');
            $table->date('ordered_date')->nullable();
            $table->date('expected_ready_date')->nullable();
            $table->date('actual_ready_date')->nullable();
            $table->date('dispensed_date')->nullable();
            $table->string('lab_name')->nullable();
            $table->string('lab_order_number')->nullable();
            
            // Quality Check
            $table->json('qc_results')->nullable();     
            // {power_verified, axis_verified, prism_verified, coating_verified, frame_alignment, 
            //  checked_by, checked_date, passed}
            
            // Dispensing
            $table->text('dispensing_notes')->nullable();
            $table->string('dispensed_by')->nullable();
            $table->boolean('warranty_issued')->default(false);
            $table->date('warranty_expiry')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
            $table->index(['status', 'expected_ready_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('optical_prescriptions');
    }
};