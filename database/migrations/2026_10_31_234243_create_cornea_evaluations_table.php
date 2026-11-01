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
        Schema::create('cornea_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            // Corneal Topography
            $table->json('topography')->nullable();      
            // {re_k_max, re_k_min, re_k_avg, re_astigmatism, re_axis, re_q_value, re_isi, re_sai,
            //  le_k_max, le_k_min, le_k_avg, le_astigmatism, le_axis, le_q_value, le_isi, le_sai}
            // ISI = Irregularity Index, SAI = Surface Asymmetry Index
            
            // Pachymetry
            $table->json('pachymetry')->nullable();      
            // {re_center, re_thinnest, re_superior, re_inferior, re_nasal, re_temporal,
            //  le_center, le_thinnest, le_superior, le_inferior, le_nasal, le_temporal}
            
            // Specular Microscopy
            $table->json('specular_microscopy')->nullable(); 
            // {re_ecd, re_cv, re_hex, re_av_cell_area, re_min_cell_area, re_max_cell_area,
            //  le_ecd, le_cv, le_hex, le_av_cell_area, le_min_cell_area, le_max_cell_area}
            // ECD = Endothelial Cell Density, CV = Coefficient of Variation
            
            // Tear Film Assessment
            $table->json('tear_film')->nullable();       
            // {re_tbut, re_schirmer, re_meniscus, re_osdi_score, re_mg_glands,
            //  le_tbut, le_schirmer, le_meniscus, le_osdi_score, le_mg_glands}
            // TBUT = Tear Break-Up Time
            
            // Corneal Aberrations
            $table->json('aberrations')->nullable();     
            // {re_hoa, re_coma, re_spherical, re_trefoil, le_hoa, le_coma, le_spherical, le_trefoil}
            // HOA = Higher Order Aberrations
            
            // Keratoconus Classification
            $table->json('keratoconus')->nullable();     
            // {re_stage: 'Normal'|'Suspect'|'Fruste'|'Mild'|'Moderate'|'Severe', re_belongs_class,
            //  le_stage, le_belongs_class}
            
            // Refractive Surgery Planning
            $table->json('refractive_plan')->nullable(); 
            // {procedure: 'LASIK'|'PRK'|'SMILE'|'ICL'|'RLE'|'CXL', 
            //  re_sph_eq, re_cyl, le_sph_eq, le_cyl, 
            //  optical_zone, ablation_depth, flap_thickness, hinge_position,
            //  iol_power, iol_model, iol_type, target_refraction}
            
            // Cross-Linking Protocol
            $table->json('cxl_protocol')->nullable();    
            // {protocol: 'Standard'|'Accelerated'|'Epi-on'|'Epi-off', 
            //  riboflavin_type, uv_power, uv_time, total_energy, oxygen_goggles}
            
            // Corneal Transplant Planning
            $table->json('transplant_plan')->nullable(); 
            // {type: 'PK'|'DALK'|'DSEK'|'DMEK'|'DSAEK', 
            //  donor_tissue_type, donor_age, endothelial_count, scleral_rim}
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cornea_evaluations');
    }
};