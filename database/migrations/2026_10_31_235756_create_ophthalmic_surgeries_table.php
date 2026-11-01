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
        Schema::create('ophthalmic_surgeries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete(); // Primary surgeon
            $table->foreignId('assistant_doctor_id')->nullable()->constrained('doctor'); // Assistant
            $table->foreignId('anesthetist_id')->nullable()->constrained('doctor');    // Anesthetist
            $table->foreignId('cataract_evaluation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('glaucoma_evaluation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('retina_evaluation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cornea_evaluation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pediatric_evaluation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('oculoplasty_evaluation_id')->nullable()->constrained()->nullOnDelete();
            
            $table->enum('eye', ['RE', 'LE', 'BOTH']);
            $table->string('procedure_name');           // e.g., 'Phacoemulsification + PCIOL'
            $table->string('procedure_code')->nullable(); // ICD-10-PCS / CPT
            $table->enum('category', ['Cataract', 'Glaucoma', 'Retina', 'Cornea', 'Pediatric', 'Oculoplasty', 'Other']);
            
            // IOL Details (for cataract)
            $table->json('iol_details')->nullable();   
            // {model, power, type: 'Monofocal'|'Multifocal'|'Toric'|'EDOF'|'Trifocal'|'Accommodating', 
            //  serial_no, manufacturer, constant, constant_type, insertion_method}
            
            // Viscoelastic
            $table->json('viscoelastic')->nullable();  
            // {name, batch, quantity, concentration, removal_technique}
            
            // Instruments & Settings
            $table->json('instruments_used')->nullable();
            $table->json('phaco_settings')->nullable(); // {power, aspiration, vacuum, bottle_height, mode}
            $table->json('vitrectomy_settings')->nullable(); // {cut_rate, vacuum, duty_cycle}
            
            // Operative Details
            $table->text('operative_steps')->nullable();
            $table->text('complications')->nullable();  // {intraoperative, postoperative}
            $table->text('complication_details')->nullable();
            $table->text('postop_instructions')->nullable();
            $table->text('postop_medications')->nullable(); // Eye drops schedule
            
            // Timing
            $table->date('surgery_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            
            // Anesthesia
            $table->enum('anesthesia', ['Topical', 'Peribulbar', 'Retrobulbar', 'General', 'Subtenon', 'Intracameral'])->default('Topical');
            $table->string('anesthesia_details')->nullable();
            
            // Status
            $table->enum('status', ['Planned', 'Scheduled', 'In Progress', 'Completed', 'Cancelled', 'Postponed'])->default('Planned');
            $table->text('cancellation_reason')->nullable();
            
            // Theatre
            $table->string('theatre_number')->nullable();
            $table->enum('priority', ['Routine', 'Semi-urgent', 'Urgent', 'Emergency'])->default('Routine');
            
            // Consent
            $table->boolean('consent_signed')->default(false);
            $table->date('consent_date')->nullable();
            $table->string('consent_witness')->nullable();
            
            $table->timestamps();
            $table->index(['patient_id', 'surgery_date']);
            $table->index(['doctor_id', 'surgery_date']);
            $table->index(['status', 'surgery_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ophthalmic_surgeries');
    }
};
