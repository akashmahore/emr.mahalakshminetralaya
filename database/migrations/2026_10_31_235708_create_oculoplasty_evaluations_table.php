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
        Schema::create('oculoplasty_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            // Eyelid Assessment
            $table->json('eyelid')->nullable();           
            // {re_ptosis_mm, re_margin_reflex_distance, re_levator_function, re_eyelid_retraction,
            //  re_entropion, re_ectropion, re_lagophthalmos, re_trichiasis, re_madate,
            //  le_ptosis_mm, le_margin_reflex_distance, le_levator_function, le_eyelid_retraction,
            //  le_entropion, le_ectropion, le_lagophthalmos, le_trichiasis, le_madate,
            //  brow_position, dermatochalasis, blepharochalasis}
            
            // Lacrimal System
            $table->json('lacrimal')->nullable();         
            // {re_syringing, re_jones_test, re_dacryocystitis, re_punctal_stenosis, re_canalicular_block, re_nldo,
            //  le_syringing, le_jones_test, le_dacryocystitis, le_punctal_stenosis, le_canalicular_block, le_nldo,
            //  tear_film_breakup, schirmer_test, dye_disappearance}
            
            // Orbit Assessment
            $table->json('orbit')->nullable();            
            // {re_proptosis_mm, re_herte_exophthalmometry, re_orbital_rim, re_extraocular_motility,
            //  re_diplopia_field, re_orbital_mass, re_enophthalmos,
            //  le_proptosis_mm, le_herte_exophthalmometry, le_orbital_rim, le_extraocular_motility,
            //  le_diplopia_field, le_orbital_mass, le_enophthalmos,
            //  orbital_ct_mri_findings, thyroid_eye_disease_activity}
            
            // Facial Assessment
            $table->json('facial')->nullable();           
            // {facial_nerve_palsy: 'None'|'Grade 1-6 House-Brackmann', 
            //  facial_asymmetry, midface_deficiency, malar_flattening}
            
            // Tumors/Lesions
            $table->json('lesions')->nullable();          
            // [{location: 'Eyelid'|'Conjunctiva'|'Orbit'|'Lacrimal', eye, type, size, margins, mobility, ulceration, biopsy_done, pathology}]
            
            // Surgical Planning
            $table->json('surgical_plan')->nullable();    
            // {procedure: 'Ptosis Repair'|'Blepharoplasty'|'Entropion/Ectropion Repair'|'DCR'|'CDCR'|'Orbital Decompression'|'Tumor Excision'|'Evisceration'|'Enucleation',
            //  approach, technique, graft_material, estimated_time, anesthesia_type}
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oculoplasty_evaluations');
    }
};
