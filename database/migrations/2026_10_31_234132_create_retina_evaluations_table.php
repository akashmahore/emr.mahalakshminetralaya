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
        Schema::create('retina_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patient')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctor')->cascadeOnDelete();
            
            // Retina Findings (Posterior Segment Detail)
            $table->json('retina_findings')->nullable(); 
            // {re_vitreous, re_macula, re_optic_disc, re_vessels, re_periphery, re_retinal_breaks,
            //  le_vitreous, le_macula, le_optic_disc, le_vessels, le_periphery, le_retinal_breaks}
            
            // OCT Macular
            $table->json('oct_macular')->nullable();     
            // {re_cft, re_cmt, re_erm, re_mh, re_cme, re_srd, re_pvd, re_vmt, re_hrf, re_ez,
            //  le_cft, le_cmt, le_erm, le_mh, le_cme, le_srd, le_pvd, le_vmt, le_hrf, le_ez,
            //  re_volume, le_volume}
            // CFT = Central Foveal Thickness, CMT = Central Macular Thickness
            
            // FFA (Fundus Fluorescein Angiography)
            $table->json('ffa')->nullable();             
            // {re_phase, re_leakage, re_staining, re_cnv, re_ischemia, re_capillary_nonperfusion,
            //  le_phase, le_leakage, le_staining, le_cnv, le_ischemia, le_capillary_nonperfusion}
            
            // ICGA (Indocyanine Green Angiography)
            $table->json('icga')->nullable();            
            // {re_hot_spots, re_cnv_type, le_hot_spots, le_cnv_type}
            
            // OCT-A (Angiography)
            $table->json('octa')->nullable();            
            // {re_scp_density, re_dcp_density, re_faz_area, re_faz_circularity,
            //  le_scp_density, le_dcp_density, le_faz_area, le_faz_circularity}
            
            // B-Scan Ultrasound
            $table->json('b_scan')->nullable();          
            // {re_retinal_detachment, re_choroidal_detachment, re_vitreous_hemorrhage, re_tumor,
            //  le_retinal_detachment, le_choroidal_detachment, le_vitreous_hemorrhage, le_tumor}
            
            // Diagnosis Classification
            $table->json('diagnosis_classification')->nullable(); 
            // {re: {amd: 'None'|'Dry'|'Wet', dr: 'No'|'Mild'|'Moderate'|'Severe'|'PDR', rvo: 'BRVO'|'CRVO'|'HRVO', rd: 'None'|'Rhegmatogenous'|'Tractional'|'Exudative', macula: 'Normal'|'ERM'|'MH'|'VMT'|'CME'|'CSR'},
            //  le: {...}}
            
            // Anti-VEGF Injection Log
            $table->json('injection_log')->nullable();   
            // [{eye, agent: 'Aflibercept'|'Ranibizumab'|'Bevacizumab'|'Brolucizumab'|'Faricimab', 
            //   dose, date, indication, next_due, complications, iop_post}]
            
            // Laser Treatment Log
            $table->json('laser_log')->nullable();       
            // [{eye, type: 'Focal'|'Grid'|'PRP'|'Barrage', spots, power, duration, date, indication, complications}]
            
            // Surgery Log
            $table->json('surgery_log')->nullable();     
            // [{eye, type: 'Vitrectomy'|'Scleral Buckle'|'Pneumatic Retinopexy'|'Membrane Peel'|'Oil Injection'|'Gas Injection', 
            //   date, surgeon, tamponade: 'Air'|'SF6'|'C3F8'|'Silicone Oil', outcome, complications}]
            
            $table->timestamps();
            $table->index(['patient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retina_evaluations');
    }
};
