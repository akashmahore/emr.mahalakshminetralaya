<?php
/**
 * Mahalakshmi Netralaya - the permission catalogue and the roles it applies to.
 *
 * This list used to be duplicated, once in PermissionController and once inside
 * permission.blade.php. The two had to stay in the same order or the checkbox
 * grid read the wrong rows, so it now lives here and both read it from config.
 *
 * The labels replace the school catalogue inherited from ICTSchool, which granted
 * rights over students, classes, exams, marks and school fees.
 *
 * `permission_name` in the database is the label lowercased with spaces turned
 * into underscores, so changing a label changes the stored key. Add to the end of
 * the list rather than reordering it if you already have permissions saved.
 *
 * Copyright (c) Mahalakshmi Netralaya
 * Part of Mahalakshmi Netralaya EMR
 * Licensed under the GNU General Public License v3.0.
 */

return [

    /*
     * Roles the permission grid has a column for. The key is stored in
     * permission.permission_group and matched against users.group in lower case.
     */
    'groups' => [
        'admin' => 'Admin',
        'doctor' => 'Doctors',
        'nurse' => 'Nurses',
        // The front desk: registration, appointments, admissions and taking payments.
        // Existing installs pick up the column with `php artisan hospital:sync-permissions`.
        'receptionist' => 'Receptionists',
        // Their own columns so a pharmacist or pharmacy staff login can be granted access.
        // Nothing is granted by default; set it under Users and Roles, Permissions.
        'pharmacist' => 'Pharmacists',
        'pharmacy_staff' => 'Pharmacy Staff',
        // Ophthalmology-specific staff
        'optometrist' => 'Optometrists',
        'orthoptist' => 'Orthoptists',
        'optician' => 'Opticians',
        'camp_coordinator' => 'Camp Coordinators',
    ],

    'permissions' => [

        // Patients
        'Patient View',
        'Patient Add',
        'Patient Update',
        'Patient Delete',
        'Patient Medical History',
        'Patient Portal Access',
        'Patient Bulk Add',

        // Appointments
        'Appointment View',
        'Appointment Add',
        'Appointment Update',
        'Appointment Cancel',
        'Appointment Calendar',
        'View Appointment Reports',

        // Clinical - Core
        'Prescription View',
        'Prescription Add',
        'Prescription Update',
        'Prescription Delete',
        'Diagnostic Report View',
        'Diagnostic Report Add',

        // Pharmacy
        'Medicine View',
        'Medicine Add',
        'Medicine Update',
        'Medicine Delete',
        'Pharmacy Sale',
        'Pharmacy Stock View',
        'View Pharmacy Reports',

        // Staff
        'Doctor View',
        'Doctor Add',
        'Doctor Update',
        'Doctor Delete',
        'Doctor Schedule',
        'Doctor Portal Access',
        'Nurse View',
        'Nurse Add',
        'Nurse Update',
        'Nurse Delete',
        'Staff View',
        'Staff Add',
        'Staff Update',
        'Staff Delete',
        'Department View',
        'Department Add',
        'Department Update',
        'Department Delete',

        // Money - Simplified for Eye Hospital
        'Payment View',
        'Payment Add',
        'Payment Update',
        'Payment Delete',
        'Invoice View',
        'Invoice Print',
        'View Payment Reports',

        // Communication and administration
        'Send Notification',
        'Service View',
        'Service Add',
        'Service Update',
        'Service Delete',
        'Holidays View',
        'Holidays Add',
        'Holidays Delete',
        'Hospital Information Add',

        // Consultations
        'Consultation View',
        'Consultation Add',

        // Ophthalmology - Eye Examinations
        'Eye Examination View',
        'Eye Examination Add',
        'Eye Examination Update',
        'Eye Examination Delete',
        'Eye Examination Print',

        // Ophthalmology - Cataract
        'Cataract Evaluation View',
        'Cataract Evaluation Add',
        'Cataract Evaluation Update',
        'Cataract Evaluation Delete',
        'Cataract Biometry View',
        'Cataract Biometry Add',
        'Cataract IOL Calculator View',
        'Cataract Surgery Planning',

        // Ophthalmology - Glaucoma
        'Glaucoma Evaluation View',
        'Glaucoma Evaluation Add',
        'Glaucoma Evaluation Update',
        'Glaucoma Evaluation Delete',
        'Glaucoma VF Analysis',
        'Glaucoma OCT Analysis',
        'Glaucoma Medication Chart',
        'Glaucoma Laser History',

        // Ophthalmology - Retina
        'Retina Evaluation View',
        'Retina Evaluation Add',
        'Retina Evaluation Update',
        'Retina Evaluation Delete',
        'Retina OCT Macular',
        'Retina FFA ICGA',
        'Retina Injection Log',
        'Retina Laser Log',
        'Retina Surgery Log',

        // Ophthalmology - Cornea
        'Cornea Evaluation View',
        'Cornea Evaluation Add',
        'Cornea Evaluation Update',
        'Cornea Evaluation Delete',
        'Cornea Topography',
        'Cornea Pachymetry',
        'Cornea Specular Microscopy',
        'Cornea Refractive Planning',
        'Cornea CXL Protocol',
        'Cornea Transplant Planning',

        // Ophthalmology - Pediatric
        'Pediatric Evaluation View',
        'Pediatric Evaluation Add',
        'Pediatric Evaluation Update',
        'Pediatric Evaluation Delete',
        'Pediatric Squint',
        'Pediatric Amblyopia',
        'Pediatric Binocular Vision',
        'Pediatric ROP',

        // Ophthalmology - Oculoplasty
        'Oculoplasty Evaluation View',
        'Oculoplasty Evaluation Add',
        'Oculoplasty Evaluation Update',
        'Oculoplasty Evaluation Delete',
        'Oculoplasty Eyelid',
        'Oculoplasty Lacrimal',
        'Oculoplasty Orbit',

        // Ophthalmology - Surgery & Post-op
        'Ophthalmic Surgery View',
        'Ophthalmic Surgery Add',
        'Ophthalmic Surgery Update',
        'Ophthalmic Surgery Schedule',
        'Ophthalmic Surgery Operative Notes',
        'Postop Followup View',
        'Postop Followup Add',
        'Postop Followup Update',

        // Optical Shop
        'Optical Prescription View',
        'Optical Prescription Add',
        'Optical Prescription Update',
        'Optical Prescription Dispense',
        'Optical Frame View',
        'Optical Frame Add',
        'Optical Frame Update',
        'Optical Lens View',
        'Optical Lens Add',
        'Optical Lens Update',
        'Optical Inventory View',
        'Optical Inventory Manage',
        'Optical QC View',
        'Optical QC Manage',

        // Contact Lens
        'Contact Lens View',
        'Contact Lens Add',
        'Contact Lens Update',
        'Contact Lens Fitting',
        'Contact Lens Trial',
        'Contact Lens Dispense',

        // Eye Camps
        'Eye Camp View',
        'Eye Camp Add',
        'Eye Camp Update',
        'Eye Camp Screening',
        'Eye Camp Reports',
    ],
];
