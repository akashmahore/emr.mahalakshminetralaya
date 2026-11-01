<?php
/**
 * Mahalakshmi Netralaya - the staff types that share one screen.
 *
 * nurse, pharmacist, optometrist, orthoptist, optician, camp_coordinator, receptionist 
 * and pharmacy_staff are tables with the same shape: a name, contact details, a photo 
 * and the legacy ion_user_id. One controller reads this map instead.
 *
 * Doctors are deliberately not here. They carry a department, a qualification and an
 * procedure id, and other modules select from them, so they have their own screen.
 *
 * `permission` is the prefix in config/hospital_permissions.php. The catalogue has a
 * dedicated Nurse set and a general Staff set, which is why nurse differs.
 *
 * Copyright (c) Mahalakshmi Netralaya
 * Part of Mahalakshmi Netralaya EMR
 * Licensed under the GNU General Public License v3.0.
 */

return [

    'nurse' => [
        'group' => 'Nurse',
        'model' => \App\Models\Nurse::class,
        'label' => 'Nurses',
        'singular' => 'nurse',
        'permission' => 'nurse',
        'icon' => 'fas fa-user-nurse',
    ],

    'pharmacist' => [
        'group' => 'Pharmacist',
        'model' => \App\Models\Pharmacist::class,
        'label' => 'Pharmacists',
        'singular' => 'pharmacist',
        'permission' => 'staff',
        'icon' => 'fas fa-pills',
    ],

    'pharmacy_staff' => [
        'group' => 'Pharmacy Staff',
        'model' => \App\Models\Pharmacist::class,
        'label' => 'Pharmacy Staff',
        'singular' => 'pharmacy staff',
        'permission' => 'staff',
        'icon' => 'fas fa-prescription-bottle-alt',
    ],

    'receptionist' => [
        'group' => 'Receptionist',
        'model' => \App\Models\Receptionist::class,
        'label' => 'Receptionists',
        'singular' => 'receptionist',
        'permission' => 'staff',
        'icon' => 'fas fa-user-clock',
    ],

    // Ophthalmology-specific staff
    'optometrist' => [
        'group' => 'Optometrist',
        'model' => \App\Models\Optometrist::class,
        'label' => 'Optometrists',
        'singular' => 'optometrist',
        'permission' => 'staff',
        'icon' => 'fas fa-eye',
    ],

    'orthoptist' => [
        'group' => 'Orthoptist',
        'model' => \App\Models\Orthoptist::class,
        'label' => 'Orthoptists',
        'singular' => 'orthoptist',
        'permission' => 'staff',
        'icon' => 'fas fa-eye-dropper',
    ],

    'optician' => [
        'group' => 'Optician',
        'model' => \App\Models\Optician::class,
        'label' => 'Opticians',
        'singular' => 'optician',
        'permission' => 'staff',
        'icon' => 'fas fa-glasses',
    ],

    'camp_coordinator' => [
        'group' => 'Camp Coordinator',
        'model' => \App\Models\CampCoordinator::class,
        'label' => 'Camp Coordinators',
        'singular' => 'camp coordinator',
        'permission' => 'staff',
        'icon' => 'fas fa-campground',
    ],

];
