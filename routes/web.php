<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\EyeExaminationController;
use App\Http\Controllers\CataractController;
use App\Http\Controllers\GlaucomaController;
use App\Http\Controllers\RetinaController;
use App\Http\Controllers\CorneaController;
use App\Http\Controllers\PediatricController;
use App\Http\Controllers\OculoplastyController;
use App\Http\Controllers\OphthalmicSurgeryController;
use App\Http\Controllers\PostopFollowupController;
use App\Http\Controllers\OpticalPrescriptionController;
use App\Http\Controllers\ContactLensController;
use App\Http\Controllers\OpticalShopController;
use App\Http\Controllers\EyeCampController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('login');
Route::post('/users/login', [UsersController::class, 'postSignin']);
Route::get('/verification_code', [UsersController::class, 'codeverify']);
Route::post('/users/code_check', [UsersController::class, 'code_check']);
Route::get('/verify_code', [UsersController::class, 'verify_code']);
Route::post('/verified', [UsersController::class, 'verified']);
Route::get('/users/logout', [UsersController::class, 'getLogout']);

// Protected routes (authenticated users)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings', [SettingsController::class, 'save']);
    
    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search', [SearchController::class, 'search'])->name('search.query');
    
    // ==================== PATIENTS ====================
    Route::resource('patients', PatientController::class)->middleware('checkPermission:patient_view');
    
    // ==================== DOCTORS ====================
    Route::resource('doctors', DoctorController::class)->middleware('checkPermission:doctor_view');
    
    // ==================== DEPARTMENTS ====================
    Route::resource('departments', DepartmentController::class)->middleware('checkPermission:department_view');
    
    // ==================== APPOINTMENTS ====================
    Route::resource('appointments', AppointmentController::class)->middleware('checkPermission:appointment_view');
    
    // ==================== OPD (Outpatient) ====================
    Route::prefix('opd')->middleware('checkPermission:appointment_view')->group(function () {
        Route::get('/queue', [OpdController::class, 'queue'])->name('opd.queue');
        Route::post('/walk-in', [OpdController::class, 'walkIn'])->name('opd.walkin');
        Route::post('/{id}/check-in', [OpdController::class, 'checkIn'])->name('opd.checkin');
        Route::post('/{id}/call-in', [OpdController::class, 'callIn'])->name('opd.callin');
        Route::post('/{id}/done', [OpdController::class, 'done'])->name('opd.done');
        Route::post('/{id}/bill', [OpdController::class, 'billVisit'])->name('opd.bill');
        Route::get('/{id}/token', [OpdController::class, 'token'])->name('opd.token');
        Route::get('/history', [OpdController::class, 'history'])->name('opd.history');
        Route::get('/visit-fees', [OpdController::class, 'fees'])->name('opd.fees');
        Route::post('/visit-fees', [OpdController::class, 'storeFee'])->name('opd.fees.store');
        Route::delete('/visit-fees/{id}', [OpdController::class, 'destroyFee'])->name('opd.fees.destroy');
        Route::get('/free-slots', [OpdController::class, 'freeSlots'])->name('opd.free-slots');
        Route::get('/doctors/{id}/slots', [OpdController::class, 'slots'])->name('opd.doctor.slots');
        Route::post('/doctors/{id}/slots', [OpdController::class, 'storeSlot'])->name('opd.doctor.slots.store');
        Route::post('/doctors/{id}/blocks', [OpdController::class, 'storeBlock'])->name('opd.doctor.blocks.store');
        Route::post('/slots/{id}/toggle', [OpdController::class, 'toggleSlot'])->name('opd.slots.toggle');
        Route::delete('/slots/{id}', [OpdController::class, 'destroySlot'])->name('opd.slots.destroy');
        Route::delete('/blocks/{id}', [OpdController::class, 'destroyBlock'])->name('opd.blocks.destroy');
    });
    
    // ==================== EYE EXAMINATIONS ====================
    Route::resource('eye-examinations', EyeExaminationController::class)->middleware('checkPermission:eye_examination_view');
    Route::get('eye-examinations/{eyeExamination}/print', [EyeExaminationController::class, 'print'])->name('eye-examinations.print');
    
    // ==================== CATARACT ====================
    Route::prefix('cataract')->name('cataract.')->group(function () {
        Route::get('/', [CataractController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [CataractController::class, 'create'])->name('create');
        Route::post('/', [CataractController::class, 'store'])->name('store');
        Route::get('/{cataract}', [CataractController::class, 'show'])->name('show');
        Route::get('/{cataract}/edit', [CataractController::class, 'edit'])->name('edit');
        Route::put('/{cataract}', [CataractController::class, 'update'])->name('update');
        Route::delete('/{cataract}', [CataractController::class, 'destroy'])->name('destroy');
        Route::get('/{cataract}/biometry', [CataractController::class, 'biometry'])->name('biometry');
        Route::post('/{cataract}/biometry', [CataractController::class, 'storeBiometry'])->name('biometry.store');
        Route::get('/{cataract}/iol-calculator', [CataractController::class, 'iolCalculator'])->name('iol-calculator');
        Route::post('/{cataract}/iol-calculator', [CataractController::class, 'calculateIOL'])->name('iol-calculator.calculate');
        Route::get('/{cataract}/surgery-plan', [CataractController::class, 'surgeryPlan'])->name('surgery-plan');
        Route::post('/{cataract}/surgery-plan', [CataractController::class, 'storeSurgeryPlan'])->name('surgery-plan.store');
    });
    
    // ==================== GLAUCOMA ====================
    Route::prefix('glaucoma')->name('glaucoma.')->group(function () {
        Route::get('/', [GlaucomaController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [GlaucomaController::class, 'create'])->name('create');
        Route::post('/', [GlaucomaController::class, 'store'])->name('store');
        Route::get('/{glaucoma}', [GlaucomaController::class, 'show'])->name('show');
        Route::get('/{glaucoma}/edit', [GlaucomaController::class, 'edit'])->name('edit');
        Route::put('/{glaucoma}', [GlaucomaController::class, 'update'])->name('update');
        Route::delete('/{glaucoma}', [GlaucomaController::class, 'destroy'])->name('destroy');
        Route::get('/{glaucoma}/vf-analysis', [GlaucomaController::class, 'vfAnalysis'])->name('vf-analysis');
        Route::get('/{glaucoma}/oct-rnfl', [GlaucomaController::class, 'octRnfl'])->name('oct-rnfl');
        Route::get('/{glaucoma}/medications', [GlaucomaController::class, 'medications'])->name('medications');
    });
    
    // ==================== RETINA ====================
    Route::prefix('retina')->name('retina.')->group(function () {
        Route::get('/', [RetinaController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [RetinaController::class, 'create'])->name('create');
        Route::post('/', [RetinaController::class, 'store'])->name('store');
        Route::get('/{retina}', [RetinaController::class, 'show'])->name('show');
        Route::get('/{retina}/edit', [RetinaController::class, 'edit'])->name('edit');
        Route::put('/{retina}', [RetinaController::class, 'update'])->name('update');
        Route::delete('/{retina}', [RetinaController::class, 'destroy'])->name('destroy');
        Route::get('/{retina}/oct-macular', [RetinaController::class, 'octMacular'])->name('oct-macular');
        Route::get('/{retina}/ffa-icga', [RetinaController::class, 'ffaIcga'])->name('ffa-icga');
        Route::get('/{retina}/injections', [RetinaController::class, 'injections'])->name('injections');
        Route::post('/{retina}/injections', [RetinaController::class, 'storeInjection'])->name('injections.store');
        Route::get('/{retina}/laser', [RetinaController::class, 'laser'])->name('laser');
        Route::post('/{retina}/laser', [RetinaController::class, 'storeLaser'])->name('laser.store');
    });
    
    // ==================== CORNEA ====================
    Route::prefix('cornea')->name('cornea.')->group(function () {
        Route::get('/', [CorneaController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [CorneaController::class, 'create'])->name('create');
        Route::post('/', [CorneaController::class, 'store'])->name('store');
        Route::get('/{cornea}', [CorneaController::class, 'show'])->name('show');
        Route::get('/{cornea}/edit', [CorneaController::class, 'edit'])->name('edit');
        Route::put('/{cornea}', [CorneaController::class, 'update'])->name('update');
        Route::delete('/{cornea}', [CorneaController::class, 'destroy'])->name('destroy');
        Route::get('/{cornea}/topography', [CorneaController::class, 'topography'])->name('topography');
        Route::get('/{cornea}/refractive-plan', [CorneaController::class, 'refractivePlan'])->name('refractive-plan');
        Route::get('/{cornea}/cxl', [CorneaController::class, 'cxl'])->name('cxl');
    });
    
    // ==================== PEDIATRIC ====================
    Route::prefix('pediatric')->name('pediatric.')->group(function () {
        Route::get('/', [PediatricController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [PediatricController::class, 'create'])->name('create');
        Route::post('/', [PediatricController::class, 'store'])->name('store');
        Route::get('/{pediatric}', [PediatricController::class, 'show'])->name('show');
        Route::get('/{pediatric}/edit', [PediatricController::class, 'edit'])->name('edit');
        Route::put('/{pediatric}', [PediatricController::class, 'update'])->name('update');
        Route::delete('/{pediatric}', [PediatricController::class, 'destroy'])->name('destroy');
        Route::get('/{pediatric}/squint', [PediatricController::class, 'squint'])->name('squint');
        Route::get('/{pediatric}/amblyopia', [PediatricController::class, 'amblyopia'])->name('amblyopia');
        Route::get('/{pediatric}/binocular-vision', [PediatricController::class, 'binocularVision'])->name('binocular-vision');
        Route::get('/{pediatric}/rop', [PediatricController::class, 'rop'])->name('rop');
    });
    
    // ==================== OCULOPLASTY ====================
    Route::prefix('oculoplasty')->name('oculoplasty.')->group(function () {
        Route::get('/', [OculoplastyController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [OculoplastyController::class, 'create'])->name('create');
        Route::post('/', [OculoplastyController::class, 'store'])->name('store');
        Route::get('/{oculoplasty}', [OculoplastyController::class, 'show'])->name('show');
        Route::get('/{oculoplasty}/edit', [OculoplastyController::class, 'edit'])->name('edit');
        Route::put('/{oculoplasty}', [OculoplastyController::class, 'update'])->name('update');
        Route::delete('/{oculoplasty}', [OculoplastyController::class, 'destroy'])->name('destroy');
        Route::get('/{oculoplasty}/eyelid', [OculoplastyController::class, 'eyelid'])->name('eyelid');
        Route::get('/{oculoplasty}/lacrimal', [OculoplastyController::class, 'lacrimal'])->name('lacrimal');
        Route::get('/{oculoplasty}/orbit', [OculoplastyController::class, 'orbit'])->name('orbit');
    });
    
    // ==================== SURGERY & OT ====================
    Route::prefix('surgeries')->name('surgeries.')->group(function () {
        Route::get('/', [OphthalmicSurgeryController::class, 'index'])->name('index');
        Route::get('/calendar', [OphthalmicSurgeryController::class, 'calendar'])->name('calendar');
        Route::get('/today', [OphthalmicSurgeryController::class, 'today'])->name('today');
        Route::get('/create', [OphthalmicSurgeryController::class, 'create'])->name('create');
        Route::post('/', [OphthalmicSurgeryController::class, 'store'])->name('store');
        Route::get('/{surgery}', [OphthalmicSurgeryController::class, 'show'])->name('show');
        Route::get('/{surgery}/edit', [OphthalmicSurgeryController::class, 'edit'])->name('edit');
        Route::put('/{surgery}', [OphthalmicSurgeryController::class, 'update'])->name('update');
        Route::delete('/{surgery}', [OphthalmicSurgeryController::class, 'destroy'])->name('destroy');
        Route::get('/{surgery}/operative-notes', [OphthalmicSurgeryController::class, 'operativeNotes'])->name('operative-notes');
        Route::post('/{surgery}/operative-notes', [OphthalmicSurgeryController::class, 'storeOperativeNotes'])->name('operative-notes.store');
        Route::get('/{surgery}/consent', [OphthalmicSurgeryController::class, 'consent'])->name('consent');
        Route::post('/{surgery}/consent', [OphthalmicSurgeryController::class, 'storeConsent'])->name('consent.store');
    });
    
    // ==================== POST-OP FOLLOWUP ====================
    Route::prefix('postop')->name('postop.')->group(function () {
        Route::get('/', [PostopFollowupController::class, 'index'])->name('index');
        Route::get('/due', [PostopFollowupController::class, 'due'])->name('due');
        Route::get('/create/{surgery}', [PostopFollowupController::class, 'create'])->name('create');
        Route::post('/', [PostopFollowupController::class, 'store'])->name('store');
        Route::get('/{postop}', [PostopFollowupController::class, 'show'])->name('show');
        Route::get('/{postop}/edit', [PostopFollowupController::class, 'edit'])->name('edit');
        Route::put('/{postop}', [PostopFollowupController::class, 'update'])->name('update');
        Route::delete('/{postop}', [PostopFollowupController::class, 'destroy'])->name('destroy');
    });
    
    // ==================== OPTICAL PRESCRIPTIONS ====================
    Route::prefix('optical')->name('optical.')->group(function () {
        Route::get('/', [OpticalPrescriptionController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [OpticalPrescriptionController::class, 'create'])->name('create');
        Route::post('/', [OpticalPrescriptionController::class, 'store'])->name('store');
        Route::get('/{optical}', [OpticalPrescriptionController::class, 'show'])->name('show');
        Route::get('/{optical}/edit', [OpticalPrescriptionController::class, 'edit'])->name('edit');
        Route::put('/{optical}', [OpticalPrescriptionController::class, 'update'])->name('update');
        Route::delete('/{optical}', [OpticalPrescriptionController::class, 'destroy'])->name('destroy');
        Route::post('/{optical}/dispense', [OpticalPrescriptionController::class, 'dispense'])->name('dispense');
        Route::get('/{optical}/print', [OpticalPrescriptionController::class, 'print'])->name('print');
        
        // Frames
        Route::prefix('frames')->name('frames.')->group(function () {
            Route::get('/', [OpticalShopController::class, 'framesIndex'])->name('index');
            Route::get('/create', [OpticalShopController::class, 'framesCreate'])->name('create');
            Route::post('/', [OpticalShopController::class, 'framesStore'])->name('store');
            Route::get('/{frame}', [OpticalShopController::class, 'framesShow'])->name('show');
            Route::get('/{frame}/edit', [OpticalShopController::class, 'framesEdit'])->name('edit');
            Route::put('/{frame}', [OpticalShopController::class, 'framesUpdate'])->name('update');
            Route::delete('/{frame}', [OpticalShopController::class, 'framesDestroy'])->name('destroy');
        });
        
        // Lenses
        Route::prefix('lenses')->name('lenses.')->group(function () {
            Route::get('/', [OpticalShopController::class, 'lensesIndex'])->name('index');
            Route::get('/create', [OpticalShopController::class, 'lensesCreate'])->name('create');
            Route::post('/', [OpticalShopController::class, 'lensesStore'])->name('store');
            Route::get('/{lens}', [OpticalShopController::class, 'lensesShow'])->name('show');
            Route::get('/{lens}/edit', [OpticalShopController::class, 'lensesEdit'])->name('edit');
            Route::put('/{lens}', [OpticalShopController::class, 'lensesUpdate'])->name('update');
            Route::delete('/{lens}', [OpticalShopController::class, 'lensesDestroy'])->name('destroy');
        });
        
        // Inventory
        Route::get('/inventory', [OpticalShopController::class, 'inventory'])->name('inventory');
    });
    
    // ==================== CONTACT LENS ====================
    Route::prefix('contact-lens')->name('contact-lens.')->group(function () {
        Route::get('/', [ContactLensController::class, 'index'])->name('index');
        Route::get('/create/{patient}', [ContactLensController::class, 'create'])->name('create');
        Route::post('/', [ContactLensController::class, 'store'])->name('store');
        Route::get('/{contactLens}', [ContactLensController::class, 'show'])->name('show');
        Route::get('/{contactLens}/edit', [ContactLensController::class, 'edit'])->name('edit');
        Route::put('/{contactLens}', [ContactLensController::class, 'update'])->name('update');
        Route::delete('/{contactLens}', [ContactLensController::class, 'destroy'])->name('destroy');
        Route::get('/{contactLens}/fitting', [ContactLensController::class, 'fitting'])->name('fitting');
        Route::post('/{contactLens}/fitting', [ContactLensController::class, 'storeFitting'])->name('fitting.store');
        Route::get('/{contactLens}/trial', [ContactLensController::class, 'trial'])->name('trial');
        Route::post('/{contactLens}/trial', [ContactLensController::class, 'storeTrial'])->name('trial.store');
        Route::get('/{contactLens}/followup', [ContactLensController::class, 'followup'])->name('followup');
    });
    
    // ==================== OPTICAL SHOP ====================
    Route::prefix('optical-shop')->name('optical-shop.')->group(function () {
        Route::get('/', [OpticalShopController::class, 'index'])->name('index');
        Route::get('/pos', [OpticalShopController::class, 'pos'])->name('pos');
        Route::post('/pos', [OpticalShopController::class, 'posStore'])->name('pos.store');
        Route::get('/inventory', [OpticalShopController::class, 'inventory'])->name('inventory');
        Route::get('/reports/sales', [OpticalShopController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/inventory', [OpticalShopController::class, 'inventoryReport'])->name('reports.inventory');
    });
    
    // ==================== CONTACT LENS MANAGEMENT ====================
    Route::prefix('cl-management')->name('cl-management.')->group(function () {
        Route::get('/', [ContactLensController::class, 'managementIndex'])->name('index');
        Route::get('/inventory', [ContactLensController::class, 'inventory'])->name('inventory');
        Route::get('/orders', [ContactLensController::class, 'orders'])->name('orders');
        Route::post('/orders', [ContactLensController::class, 'storeOrder'])->name('orders.store');
        Route::get('/suppliers', [ContactLensController::class, 'suppliers'])->name('suppliers');
    });
    
    // ==================== EYE CAMPS ====================
    Route::prefix('eye-camps')->name('eye-camps.')->group(function () {
        Route::get('/', [EyeCampController::class, 'index'])->name('index');
        Route::get('/create', [EyeCampController::class, 'create'])->name('create');
        Route::post('/', [EyeCampController::class, 'store'])->name('store');
        Route::get('/{eyeCamp}', [EyeCampController::class, 'show'])->name('show');
        Route::get('/{eyeCamp}/edit', [EyeCampController::class, 'edit'])->name('edit');
        Route::put('/{eyeCamp}', [EyeCampController::class, 'update'])->name('update');
        Route::delete('/{eyeCamp}', [EyeCampController::class, 'destroy'])->name('destroy');
        Route::get('/{eyeCamp}/screening', [EyeCampController::class, 'screening'])->name('screening');
        Route::post('/{eyeCamp}/screening', [EyeCampController::class, 'storeScreening'])->name('screening.store');
        Route::get('/{eyeCamp}/referrals', [EyeCampController::class, 'referrals'])->name('referrals');
        Route::get('/{eyeCamp}/reports', [EyeCampController::class, 'reports'])->name('reports');
        Route::get('/{eyeCamp}/patients', [EyeCampController::class, 'patients'])->name('patients');
    });
    
    // ==================== CONSULTATIONS ====================
    Route::prefix('consultations')->name('consultations.')->group(function () {
        Route::get('/', [ConsultationController::class, 'index'])->name('index');
        Route::get('/visit/{appointment}', [ConsultationController::class, 'visit'])->name('visit');
        Route::post('/visit/{appointment}', [ConsultationController::class, 'save'])->name('visit.save');
        Route::get('/{consultation}/print', [ConsultationController::class, 'printout'])->name('print');
        Route::delete('/templates/{template}', [ConsultationController::class, 'destroyTemplate'])->name('templates.destroy');
    });
    
    // ==================== PRESCRIPTIONS ====================
    Route::resource('prescriptions', PrescriptionController::class);
    
    // ==================== MEDICINES ====================
    Route::resource('medicines', MedicineController::class);
    Route::get('medicines/categories', [MedicineController::class, 'categories'])->name('medicines.categories');
    Route::post('medicines/categories', [MedicineController::class, 'storeCategory'])->name('medicines.categories.store');
    Route::delete('medicines/categories/{category}', [MedicineController::class, 'destroyCategory'])->name('medicines.categories.destroy');
    
    // ==================== PHARMACY ====================
    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/', [PharmacyController::class, 'index'])->name('index');
        Route::get('/create', [PharmacyController::class, 'create'])->name('create');
        Route::post('/', [PharmacyController::class, 'store'])->name('store');
        Route::get('/{pharmacy}', [PharmacyController::class, 'show'])->name('show');
        Route::delete('/{pharmacy}', [PharmacyController::class, 'destroy'])->name('destroy');
        Route::get('/inventory', [PharmacyController::class, 'inventory'])->name('inventory');
        Route::get('/reports/sales', [PharmacyController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/stock', [PharmacyController::class, 'stockReport'])->name('reports.stock');
    });
    
    // ==================== STAFF ====================
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/{type}', [StaffController::class, 'index'])->name('index');
        Route::get('/{type}/create', [StaffController::class, 'create'])->name('create');
        Route::post('/{type}', [StaffController::class, 'store'])->name('store');
        Route::get('/{type}/{id}/edit', [StaffController::class, 'edit'])->name('edit');
        Route::put('/{type}/{id}', [StaffController::class, 'update'])->name('update');
        Route::delete('/{type}/{id}', [StaffController::class, 'destroy'])->name('destroy');
    });
    
    // ==================== DEPARTMENTS ====================
    Route::resource('departments', DepartmentController::class);
    
    // ==================== PRICING ====================
    Route::prefix('pricing')->name('pricing.')->group(function () {
        Route::get('/patient-types', [PricingController::class, 'types'])->name('types');
        Route::post('/patient-types', [PricingController::class, 'storeType'])->name('types.store');
        Route::put('/patient-types/{patientType}', [PricingController::class, 'updateType'])->name('types.update');
        Route::delete('/patient-types/{patientType}', [PricingController::class, 'destroyType'])->name('types.destroy');
        Route::get('/', [PricingController::class, 'index'])->name('index');
        Route::post('/', [PricingController::class, 'save'])->name('save');
        Route::post('/copy', [PricingController::class, 'copy'])->name('copy');
        Route::post('/adjust', [PricingController::class, 'adjust'])->name('adjust');
        Route::get('/export', [PricingController::class, 'export'])->name('export');
    });
    
    // ==================== PATIENT TYPES ====================
    Route::resource('patient-types', PatientTypeController::class);
    
    // ==================== MEDICAL HISTORY ====================
    Route::resource('medical-history', MedicalHistoryController::class);
    
    // ==================== SETTINGS ====================
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'save'])->name('settings.save');
    
    // ==================== DOCTORS ====================
    Route::resource('doctors', DoctorController::class);
    
    // ==================== APPOINTMENTS ====================
    Route::resource('appointments', AppointmentController::class);
    
    // ==================== PATIENTS ====================
    Route::resource('patients', PatientController::class);
    
    // ==================== DEPARTMENTS ====================
    Route::resource('departments', DepartmentController::class);
    
    // ==================== PRICING ====================
    Route::resource('pricing', PricingController::class)->only(['index', 'store', 'update', 'destroy']);
    
    // ==================== DOCTORS ====================
    Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'schedule'])->name('doctors.schedule');
    Route::post('doctors/{doctor}/schedule', [DoctorController::class, 'storeSchedule'])->name('doctors.schedule.store');
    Route::post('doctors/{doctor}/blocks', [DoctorController::class, 'storeBlock'])->name('doctors.blocks.store');
    Route::delete('slots/{slot}', [DoctorController::class, 'destroySlot'])->name('slots.destroy');
    Route::delete('blocks/{block}', [DoctorController::class, 'destroyBlock'])->name('blocks.destroy');
    
    // ==================== PATIENT TYPES ====================
    Route::get('patient-types', [PricingController::class, 'types'])->name('patient-types');
    Route::post('patient-types', [PricingController::class, 'storeType'])->name('patient-types.store');
    Route::put('patient-types/{patientType}', [PricingController::class, 'updateType'])->name('patient-types.update');
    Route::delete('patient-types/{patientType}', [PricingController::class, 'destroyType'])->name('patient-types.destroy');
    
    // ==================== SEARCH ====================
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search', [SearchController::class, 'search'])->name('search.query');
    
    // ==================== PROFILE ====================
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'save'])->name('settings.save');
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    // Users & Permissions
    Route::resource('users', UsersController::class);
    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::post('/permission/create', [PermissionController::class, 'store'])->name('permission.store');
    
    // Institute/Hospital Settings
    Route::get('/institute', [InstituteController::class, 'index'])->name('institute');
    Route::post('/institute', [InstituteController::class, 'save'])->name('institute.save');
    
    // Barcode
    Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode');
    Route::post('/barcode', [BarcodeController::class, 'generate'])->name('barcode.generate');
    
    // Departments (Admin)
    Route::resource('departments', DepartmentController::class);
    
    // Staff Management (Admin)
    Route::get('/staff/{type}', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/{type}/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff/{type}', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{type}/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{type}/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{type}/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

/*
|--------------------------------------------------------------------------
| API Routes (for AJAX)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware(['auth'])->group(function () {
    // Patient search
    Route::get('/patients/search', [PatientController::class, 'search']);
    Route::get('/patients/{patient}/chart', [PatientController::class, 'chart']);
    
    // Doctor slots
    Route::get('/doctors/{doctor}/slots', [DoctorController::class, 'getSlots']);
    
    // Medicine search
    Route::get('/medicines/search', [MedicineController::class, 'search']);
    
    // Optical calculations
    Route::post('/optical/calculate', [OpticalPrescriptionController::class, 'calculate']);
    
    // Contact lens calculations
    Route::post('/contact-lens/calculate', [ContactLensController::class, 'calculate']);
    
    // IOL Calculator
    Route::post('/cataract/iol-calculate', [CataractController::class, 'calculateIOL']);
    
    // Patient types pricing
    Route::get('/pricing/{service}/{patientType}', [PricingController::class, 'getPrice']);
});

/*
|--------------------------------------------------------------------------
| Public Routes (no auth)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('login');
Route::post('/users/login', [UsersController::class, 'postSignin']);
Route::get('/verification_code', [UsersController::class, 'codeverify']);
Route::post('/users/code_check', [UsersController::class, 'code_check']);
Route::get('/verify_code', [UsersController::class, 'verify_code']);
Route::post('/verified', [UsersController::class, 'verified']);
Route::get('/users/logout', [UsersController::class, 'getLogout']);

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});