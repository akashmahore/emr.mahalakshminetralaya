{{--
  Mahalakshmi Netralaya - main navigation.

  Every entry here points at a route that exists. The menu this replaces was the
  ICTSchool one: students, teachers, classes, sections, exams, marks, GPA rules,
  promotion, timetables, question papers, academic years and family fee vouchers.
  Those controllers and routes were removed during the hospital rebuild, so every
  one of those links was a dead end.

  Hospital modules still being built (patients, appointments, wards, pharmacy,
  laboratory) will be added here as their controllers land.

  Copyright (c) Mahalakshmi Netralaya
  Part of Mahalakshmi Netralaya EMR
  Licensed under the GNU General Public License v3.0.
--}}
<?php
$here = request()->path();

/**
 * Hide what the signed in role cannot open.
 *
 * Without this the menu lists every clinical screen to everyone and a role that
 * is missing the permission finds out by being redirected, which reads as a
 * broken link rather than as a deliberate restriction.
 */
$may = function ($permission) {
    return \App\Support\Permissions::allows($permission);
};

/** Same rule as the admin middleware on those routes. */
$isAdmin = optional(auth()->user())->group === 'Admin';

/** Mark the open branch so the right group starts expanded. */
$active = function ($prefixes) use ($here) {
    foreach ((array) $prefixes as $p) {
        if ($here === $p || strpos($here, $p . '/') === 0) {
            return true;
        }
    }
    return false;
};
?>

<div class="sidebar-nav nav-canvas-thumb">
  <a class="ih-brand" href="{{ url('/dashboard') }}" aria-label="Mahalakshmi Netralaya home"><img src="{{ 'https://bumpy-turquoise-lorzytfb.edgeone.dev/logo.svg' }}" alt="Mahalakshmi Netralaya"></a>
  <ul class="nav nav-pills nav-stacked main-menu">

    <li class="nav-header">Main</li>

    <li>
      <a class="{{ $active('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
        <i class="fas fa-th-large"></i><span class="hidden-tablet"> Dashboard</span>
      </a>
    </li>

    <li class="nav-header">Clinical</li>

    @if ($may('patient_view'))
    <li>
      <a class="{{ $active('patients') ? 'active' : '' }}" href="{{ url('/patients') }}">
        <i class="fas fa-user-injured"></i><span class="hidden-tablet"> Patients</span>
      </a>
    </li>
    @endif
    @if ($may('patient_medical_history'))
    <li>
      <a class="{{ $active('history') ? 'active' : '' }}" href="{{ url('/history') }}">
        <i class="fas fa-notes-medical"></i><span class="hidden-tablet"> Medical History</span>
      </a>
    </li>
    @endif
    @if ($may('doctor_view'))
    <li>
      <a class="{{ $active('doctors') ? 'active' : '' }}" href="{{ url('/doctors') }}">
        <i class="fas fa-user-md"></i><span class="hidden-tablet"> Doctors</span>
      </a>
    </li>
    @endif
    @if ($may('appointment_view'))
    <li>
      <a class="{{ $active('appointments') ? 'active' : '' }}" href="{{ url('/appointments') }}">
        <i class="fas fa-calendar-check"></i><span class="hidden-tablet"> Appointments</span>
      </a>
    </li>
    @endif
    @if ($may('appointment_view'))
    <li>
      <a class="dropmenu {{ $active('opd') ? 'active' : '' }}" href="#">
        <i class="fas fa-stethoscope"></i><span class="hidden-tablet"> OPD</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active(['opd', 'consultations']) ? '' : 'display:none;' }}">
        <li><a href="{{ url('/opd/queue') }}"><i class="glyphicon glyphicon-sort-by-order"></i> Token Queue</a></li>
        <li><a href="{{ url('/opd/history') }}"><i class="glyphicon glyphicon-time"></i> OPD History</a></li>
        @if ($may('consultation_view'))<li><a href="{{ url('/consultations') }}"><i class="glyphicon glyphicon-heart"></i> Consultations</a></li>@endif
        @if ($may('service_view'))<li><a href="{{ url('/opd/visit-fees') }}"><i class="glyphicon glyphicon-usd"></i> Visit Fees</a></li>@endif
      </ul>
    </li>
    @endif
    @if ($may('admission_view'))
    <li>
      <a class="{{ $active('admissions') ? 'active' : '' }}" href="{{ url('/admissions') }}">
        <i class="fas fa-procedures"></i><span class="hidden-tablet"> Admissions</span>
      </a>
    </li>
    @endif
    @if ($may('bed_view'))
    <li>
      <a class="{{ $active('beds') ? 'active' : '' }}" href="{{ url('/beds') }}">
        <i class="fas fa-bed"></i><span class="hidden-tablet"> Beds</span>
      </a>
    </li>
    @endif
    @if ($may('prescription_view'))
    <li>
      <a class="{{ $active('prescriptions') ? 'active' : '' }}" href="{{ url('/prescriptions') }}">
        <i class="fas fa-file-prescription"></i><span class="hidden-tablet"> Prescriptions</span>
      </a>
    </li>
    @endif
    @if ($may('medicine_view'))
    <li>
      <a class="{{ $active('medicines') ? 'active' : '' }}" href="{{ url('/medicines') }}">
        <i class="fas fa-pills"></i><span class="hidden-tablet"> Medicines</span>
      </a>
    </li>
    @endif
    @if ($may('lab_test_view'))
    <li>
      <a class="{{ $active('lab') ? 'active' : '' }}" href="{{ url('/lab') }}">
        <i class="fas fa-flask"></i><span class="hidden-tablet"> Laboratory</span>
      </a>
    </li>
    @endif
    @if ($may('diagnostic_report_view'))
    <li>
      <a class="{{ $active('radiology') ? 'active' : '' }}" href="{{ url('/radiology') }}">
        <i class="fas fa-x-ray"></i><span class="hidden-tablet"> Radiology</span>
      </a>
    </li>
    @endif
    @if ($may('pharmacy_stock_view'))
    <li>
      <a class="{{ $active('pharmacy') ? 'active' : '' }}" href="{{ url('/pharmacy') }}">
        <i class="fas fa-clinic-medical"></i><span class="hidden-tablet"> Pharmacy</span>
      </a>
    </li>
    @endif
    @if ($may('invoice_view'))
    <li>
      <a class="{{ $active('invoices') ? 'active' : '' }}" href="{{ url('/invoices') }}">
        <i class="fas fa-file-invoice-dollar"></i><span class="hidden-tablet"> Invoices</span>
      </a>
    </li>
    @endif
    @if ($may('service_view'))
    <li>
      <a class="{{ $active('services') ? 'active' : '' }}" href="{{ url('/services') }}">
        <i class="fas fa-tags"></i><span class="hidden-tablet"> Services and Prices</span>
      </a>
    </li>
    <li>
      <a class="{{ $active(['pricing', 'patient-types']) ? 'active' : '' }}" href="{{ url('/pricing') }}">
        <i class="fas fa-hand-holding-usd"></i><span class="hidden-tablet"> Pricing by Patient Type</span>
      </a>
    </li>
    @endif
    <li>
      <a class="{{ $active('search') ? 'active' : '' }}" href="{{ url('/search') }}">
        <i class="fas fa-search"></i><span class="hidden-tablet"> Search Patients</span>
      </a>
    </li>

    <li>
      <a class="{{ $active('reports') ? 'active' : '' }}" href="{{ url('/reports') }}">
        <i class="fas fa-chart-bar"></i><span class="hidden-tablet"> Reports</span>
      </a>
    </li>

    <li class="nav-header">Ophthalmology</li>

    @if ($may('eye_examination_view'))
    <li>
      <a class="dropmenu {{ $active('eye-examinations') ? 'active' : '' }}" href="#">
        <i class="fas fa-eye"></i><span class="hidden-tablet"> Eye Examinations</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('eye-examinations') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/eye-examinations') }}"><i class="fas fa-list"></i> All Examinations</a></li>
        <li><a href="{{ url('/eye-examinations/create') }}"><i class="fas fa-plus"></i> New Examination</a></li>
        <li><a href="{{ url('/eye-examinations/today') }}"><i class="fas fa-calendar-day"></i> Today's Examinations</a></li>
      </ul>
    </li>
    @endif

    @if ($may('cataract_evaluation_view'))
    <li>
      <a class="dropmenu {{ $active('cataract') ? 'active' : '' }}" href="#">
        <i class="fas fa-circle"></i><span class="hidden-tablet"> Cataract</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('cataract') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/cataract/evaluations') }}"><i class="fas fa-clipboard-check"></i> Evaluations</a></li>
        <li><a href="{{ url('/cataract/biometry') }}"><i class="fas fa-ruler-combined"></i> Biometry / IOL Calc</a></li>
        <li><a href="{{ url('/cataract/surgery-plan') }}"><i class="fas fa-calendar-plus"></i> Surgery Planning</a></li>
      </ul>
    </li>
    @endif

    @if ($may('glaucoma_evaluation_view'))
    <li>
      <a class="dropmenu {{ $active('glaucoma') ? 'active' : '' }}" href="#">
        <i class="fas fa-tint"></i><span class="hidden-tablet"> Glaucoma</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('glaucoma') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/glaucoma/evaluations') }}"><i class="fas fa-clipboard-check"></i> Evaluations</a></li>
        <li><a href="{{ url('/glaucoma/vf-analysis') }}"><i class="fas fa-chart-area"></i> VF Analysis</a></li>
        <li><a href="{{ url('/glaucoma/oct-rnfl') }}"><i class="fas fa-chart-line"></i> RNFL OCT</a></li>
        <li><a href="{{ url('/glaucoma/medications') }}"><i class="fas fa-pills"></i> Medication Chart</a></li>
      </ul>
    </li>
    @endif

    @if ($may('retina_evaluation_view'))
    <li>
      <a class="dropmenu {{ $active('retina') ? 'active' : '' }}" href="#">
        <i class="fas fa-project-diagram"></i><span class="hidden-tablet"> Retina</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('retina') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/retina/evaluations') }}"><i class="fas fa-clipboard-check"></i> Evaluations</a></li>
        <li><a href="{{ url('/retina/oct-macular') }}"><i class="fas fa-layer-group"></i> OCT Macular</a></li>
        <li><a href="{{ url('/retina/ffa-icga') }}"><i class="fas fa-image"></i> FFA / ICGA</a></li>
        <li><a href="{{ url('/retina/injections') }}"><i class="fas fa-syringe"></i> Anti-VEGF Log</a></li>
        <li><a href="{{ url('/retina/laser') }}"><i class="fas fa-bolt"></i> Laser Log</a></li>
      </ul>
    </li>
    @endif

    @if ($may('cornea_evaluation_view'))
    <li>
      <a class="dropmenu {{ $active('cornea') ? 'active' : '' }}" href="#">
        <i class="fas fa-shapes"></i><span class="hidden-tablet"> Cornea & Refractive</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('cornea') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/cornea/evaluations') }}"><i class="fas fa-clipboard-check"></i> Evaluations</a></li>
        <li><a href="{{ url('/cornea/topography') }}"><i class="fas fa-map-marked-alt"></i> Topography</a></li>
        <li><a href="{{ url('/cornea/refractive') }}"><i class="fas fa-magic"></i> Refractive Planning</a></li>
        <li><a href="{{ url('/cornea/cxl') }}"><i class="fas fa-link"></i> Cross-Linking</a></li>
      </ul>
    </li>
    @endif

    @if ($may('pediatric_evaluation_view'))
    <li>
      <a class="dropmenu {{ $active('pediatric') ? 'active' : '' }}" href="#">
        <i class="fas fa-child"></i><span class="hidden-tablet"> Pediatric</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('pediatric') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/pediatric/evaluations') }}"><i class="fas fa-clipboard-check"></i> Evaluations</a></li>
        <li><a href="{{ url('/pediatric/squint') }}"><i class="fas fa-compress-alt"></i> Squint</a></li>
        <li><a href="{{ url('/pediatric/amblyopia') }}"><i class="fas fa-eye-slash"></i> Amblyopia</a></li>
        <li><a href="{{ url('/pediatric/rop') }}"><i class="fas fa-baby"></i> ROP Screening</a></li>
      </ul>
    </li>
    @endif

    @if ($may('oculoplasty_evaluation_view'))
    <li>
      <a class="dropmenu {{ $active('oculoplasty') ? 'active' : '' }}" href="#">
        <i class="fas fa-hand-paper"></i><span class="hidden-tablet"> Oculoplasty</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('oculoplasty') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/oculoplasty/evaluations') }}"><i class="fas fa-clipboard-check"></i> Evaluations</a></li>
        <li><a href="{{ url('/oculoplasty/eyelid') }}"><i class="fas fa-eye"></i> Eyelid</a></li>
        <li><a href="{{ url('/oculoplasty/lacrimal') }}"><i class="fas fa-tint"></i> Lacrimal</a></li>
        <li><a href="{{ url('/oculoplasty/orbit') }}"><i class="fas fa-globe"></i> Orbit</a></li>
      </ul>
    </li>
    @endif

    @if ($may('ophthalmic_surgery_view'))
    <li>
      <a class="dropmenu {{ $active('surgeries') ? 'active' : '' }}" href="#">
        <i class="fas fa-procedures"></i><span class="hidden-tablet"> Surgery & OT</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('surgeries') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/surgeries') }}"><i class="fas fa-list"></i> All Surgeries</a></li>
        <li><a href="{{ url('/surgeries/today') }}"><i class="fas fa-calendar-day"></i> Today's OT List</a></li>
        <li><a href="{{ url('/surgeries/schedule') }}"><i class="fas fa-calendar-week"></i> OT Schedule</a></li>
        <li><a href="{{ url('/surgeries/operative-notes') }}"><i class="fas fa-file-medical"></i> Operative Notes</a></li>
      </ul>
    </li>
    @endif

    @if ($may('postop_followup_view'))
    <li>
      <a class="{{ $active('postop') ? 'active' : '' }}" href="{{ url('/postop-followups') }}">
        <i class="fas fa-calendar-check"></i><span class="hidden-tablet"> Post-op Followup</span>
      </a>
    </li>
    @endif

    <li class="nav-header">Optical Shop & Contact Lens</li>

    @if ($may('optical_prescription_view'))
    <li>
      <a class="dropmenu {{ $active('optical') ? 'active' : '' }}" href="#">
        <i class="fas fa-glasses"></i><span class="hidden-tablet"> Optical Shop</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('optical') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/optical/prescriptions') }}"><i class="fas fa-clipboard-list"></i> Prescriptions</a></li>
        <li><a href="{{ url('/optical/frames') }}"><i class="fas fa-boxes"></i> Frames Inventory</a></li>
        <li><a href="{{ url('/optical/lenses') }}"><i class="fas fa-circle"></i> Lens Inventory</a></li>
        <li><a href="{{ url('/optical/dispensing') }}"><i class="fas fa-hand-holding"></i> Dispensing</a></li>
        <li><a href="{{ url('/optical/qc') }}"><i class="fas fa-check-double"></i> Quality Check</a></li>
      </ul>
    </li>
    @endif

    @if ($may('contact_lens_view'))
    <li>
      <a class="dropmenu {{ $active('contact-lens') ? 'active' : '' }}" href="#">
        <i class="fas fa-circle"></i><span class="hidden-tablet"> Contact Lens</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('contact-lens') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/contact-lens/prescriptions') }}"><i class="fas fa-clipboard-list"></i> Prescriptions</a></li>
        <li><a href="{{ url('/contact-lens/fitting') }}"><i class="fas fa-search"></i> Fitting & Trial</a></li>
        <li><a href="{{ url('/contact-lens/followup') }}"><i class="fas fa-calendar-check"></i> Follow-ups</a></li>
      </ul>
    </li>
    @endif

    <li class="nav-header">Eye Camps</li>

    @if ($may('eye_camp_view'))
    <li>
      <a class="dropmenu {{ $active('eye-camps') ? 'active' : '' }}" href="#">
        <i class="fas fa-campground"></i><span class="hidden-tablet"> Eye Camps</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('eye-camps') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/eye-camps') }}"><i class="fas fa-list"></i> All Camps</a></li>
        <li><a href="{{ url('/eye-camps/create') }}"><i class="fas fa-plus"></i> New Camp</a></li>
        <li><a href="{{ url('/eye-camps/screening') }}"><i class="fas fa-clipboard-check"></i> Screening</a></li>
        <li><a href="{{ url('/eye-camps/reports') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
      </ul>
    </li>
    @endif

    @if ($may('cash_close') || $may('view_payment_reports') || $may('payment_add'))
    <li>
      <a class="dropmenu {{ $active(['cash-close', 'day-book', 'settlement']) ? 'active' : '' }}" href="#">
        <i class="fas fa-cash-register"></i><span class="hidden-tablet"> Front Desk Cash</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active(['cash-close', 'day-book', 'settlement']) ? '' : 'display:none;' }}">
        @if ($may('cash_close'))<li><a href="{{ url('/cash-close') }}"><i class="glyphicon glyphicon-lock"></i> Sale Close</a></li>@endif
        @if ($may('view_payment_reports'))<li><a href="{{ url('/day-book') }}"><i class="glyphicon glyphicon-book"></i> Day Book</a></li>@endif
        @if ($may('payment_add'))<li><a href="{{ url('/settlement') }}"><i class="glyphicon glyphicon-check"></i> Final Settlement</a></li>@endif
      </ul>
    </li>
    @endif
    @if ($may('doctor_share_view'))
    <li>
      <a class="{{ $active('doctor-share') ? 'active' : '' }}" href="{{ url('/doctor-share') }}">
        <i class="fas fa-handshake"></i><span class="hidden-tablet"> Doctor Share</span>
      </a>
    </li>
    @endif

    <li class="nav-header">People</li>

    @if ($may('department_view'))
    <li>
      <a class="{{ $active('departments') ? 'active' : '' }}" href="{{ url('/departments') }}">
        <i class="fas fa-hospital"></i><span class="hidden-tablet"> Departments</span>
      </a>
    </li>
    @endif
    @if ($may('nurse_view'))
    <li>
      <a class="{{ $active('staff/nurse') ? 'active' : '' }}" href="{{ url('/staff/nurse') }}">
        <i class="fas fa-user-nurse"></i><span class="hidden-tablet"> Nurses</span>
      </a>
    </li>
    @endif
    @if ($may('staff_view'))
    <li>
      <a class="dropmenu {{ $active('staff') ? 'active' : '' }}" href="#">
        <i class="fas fa-id-badge"></i><span class="hidden-tablet"> Other Staff</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('staff') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/staff/pharmacist') }}"><i class="glyphicon glyphicon-shopping-cart"></i> Pharmacists</a></li>
        <li><a href="{{ url('/staff/laboratorist') }}"><i class="glyphicon glyphicon-tint"></i> Laboratory Staff</a></li>
        <li><a href="{{ url('/staff/receptionist') }}"><i class="glyphicon glyphicon-bell"></i> Receptionists</a></li>
        <li><a href="{{ url('/staff/accountant') }}"><i class="glyphicon glyphicon-usd"></i> Accountants</a></li>
      </ul>
    </li>
    @endif

    @if ($may('staff_view'))
    <li>
      <a class="dropmenu {{ $active('staff/ophthalmology') ? 'active' : '' }}" href="#">
        <i class="fas fa-eye"></i><span class="hidden-tablet"> Ophthalmology Staff</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('staff/ophthalmology') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/staff/optometrist') }}"><i class="fas fa-eye"></i> Optometrists</a></li>
        <li><a href="{{ url('/staff/orthoptist') }}"><i class="fas fa-eye-dropper"></i> Orthoptists</a></li>
        <li><a href="{{ url('/staff/optician') }}"><i class="fas fa-glasses"></i> Opticians</a></li>
        <li><a href="{{ url('/staff/camp-coordinator') }}"><i class="fas fa-campground"></i> Camp Coordinators</a></li>
      </ul>
    </li>
    @endif

    @if ($may('accounting'))
    <li class="nav-header">Money</li>

    <li>
      <a class="dropmenu {{ $active('accounting') ? 'active' : '' }}" href="#">
        <i class="fas fa-calculator"></i><span class="hidden-tablet"> Accounting</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('accounting') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/accounting/income') }}"><i class="glyphicon glyphicon-plus"></i> Add Income</a></li>
        <li><a href="{{ url('/accounting/incomelist') }}"><i class="glyphicon glyphicon-list"></i> Income List</a></li>
        <li><a href="{{ url('/accounting/expence') }}"><i class="glyphicon glyphicon-minus"></i> Add Expense</a></li>
        <li><a href="{{ url('/accounting/expencelist') }}"><i class="glyphicon glyphicon-list"></i> Expense List</a></li>
        <li><a href="{{ url('/accounting/sectors') }}"><i class="glyphicon glyphicon-tags"></i> Sectors</a></li>
        <li><a href="{{ url('/accounting/report') }}"><i class="glyphicon glyphicon-stats"></i> Report</a></li>
        <li><a href="{{ url('/accounting/reportsum') }}"><i class="glyphicon glyphicon-stats"></i> Summary Report</a></li>
      </ul>
    </li>
    @endif

    @if ($isAdmin)
    <li class="nav-header">Communication</li>

    <li>
      <a class="dropmenu {{ $active(['message', 'template', 'smslog', 'notification_type']) ? 'active' : '' }}" href="#">
        <i class="fas fa-comment-dots"></i><span class="hidden-tablet"> Messaging</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active(['message', 'template', 'smslog', 'notification_type']) ? '' : 'display:none;' }}">
        <li><a href="{{ url('/message') }}"><i class="glyphicon glyphicon-send"></i> Send Message</a></li>
        <li><a href="{{ url('/template/create') }}"><i class="glyphicon glyphicon-plus"></i> Add Template</a></li>
        <li><a href="{{ url('/template/list') }}"><i class="glyphicon glyphicon-list"></i> Templates</a></li>
        <li><a href="{{ url('/smslog') }}"><i class="glyphicon glyphicon-list-alt"></i> SMS and Voice Log</a></li>
        <li><a href="{{ url('/notification_type') }}"><i class="glyphicon glyphicon-bell"></i> Notification Types</a></li>
      </ul>
    </li>

    <li>
      <a class="dropmenu {{ $active('ictcore') ? 'active' : '' }}" href="#">
        <i class="fas fa-phone-alt"></i><span class="hidden-tablet"> ICTCore</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active('ictcore') ? '' : 'display:none;' }}">
        <li><a href="{{ url('/ictcore') }}"><i class="glyphicon glyphicon-cog"></i> Integration</a></li>
        <li><a href="{{ url('/ictcore/attendance') }}"><i class="glyphicon glyphicon-calendar"></i> Appointment Reminders</a></li>
        <li><a href="{{ url('/ictcore/fees') }}"><i class="glyphicon glyphicon-credit-card"></i> Payment Reminders</a></li>
      </ul>
    </li>

    <li class="nav-header">Administration</li>

    <li>
      <a class="dropmenu {{ $active(['users', 'permission', 'useredit', 'verification_code', 'verify_code']) ? 'active' : '' }}" href="#">
        <i class="fas fa-users-cog"></i><span class="hidden-tablet"> Users and Roles</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active(['users', 'permission']) ? '' : 'display:none;' }}">
        <li><a href="{{ url('/users') }}"><i class="glyphicon glyphicon-list"></i> Users</a></li>
        <li><a href="{{ url('/permission') }}"><i class="glyphicon glyphicon-lock"></i> Permissions</a></li>
      </ul>
    </li>

    <li>
      <a class="dropmenu {{ $active(['institute', 'branches', 'hospital-branches', 'schedule', 'barcode', 'gateways']) ? 'active' : '' }}" href="#">
        <i class="fas fa-cog"></i><span class="hidden-tablet"> Settings</span>
        <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
      </a>
      <ul style="{{ $active(['institute', 'branches', 'hospital-branches', 'schedule', 'barcode', 'gateways']) ? '' : 'display:none;' }}">
        <li><a href="{{ url('/institute') }}"><i class="glyphicon glyphicon-tower"></i> Hospital Information</a></li>
        <li><a href="{{ url('/hospital-branches') }}"><i class="glyphicon glyphicon-map-marker"></i> Branches</a></li>
        <li><a href="{{ url('/gateways') }}"><i class="glyphicon glyphicon-credit-card"></i> Payment Gateways</a></li>
        <li><a href="{{ url('/schedule') }}"><i class="glyphicon glyphicon-time"></i> Schedule</a></li>
        <li><a href="{{ url('/barcode') }}"><i class="glyphicon glyphicon-barcode"></i> Barcode</a></li>
      </ul>
    </li>

    <li>
      <a class="{{ $active('activity') ? 'active' : '' }}" href="{{ url('/activity') }}">
        <i class="fas fa-history"></i><span class="hidden-tablet"> Activity Log</span>
      </a>
    </li>
    @endif

    <li class="nav-header">Account</li>

    <li>
      <a class="{{ $active('settings') ? 'active' : '' }}" href="{{ url('/settings') }}">
        <i class="fas fa-user-circle"></i><span class="hidden-tablet"> My Profile</span>
      </a>
    </li>

    <li>
      <a href="{{ url('/users/logout') }}">
        <i class="fas fa-sign-out-alt"></i><span class="hidden-tablet"> Sign out</span>
      </a>
    </li>

  </ul>
</div>
<script>
  // Fold-out groups (Other Staff, Accounting, Messaging...). The theme script only opens
  // menus marked .accordion, so these links did nothing when clicked.
  (function ($) {
    if (!$) return;
    $(document).off('click.ichMenu').on('click.ichMenu', 'ul.main-menu a.dropmenu', function (e) {
      e.preventDefault();
      $(this).siblings('ul').stop(true, true).slideToggle(150);
    });
  })(window.jQuery);
</script>
