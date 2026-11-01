# Mahalakshmi Netralaya

**Ophthalmology Electronic Medical Records (EMR) System**

Advanced eye care management software for Mahalakshmi Netralaya. Handles the day-to-day running of an eye hospital, including patient records, appointments, eye examinations, cataract surgery planning, glaucoma management, retina care, cornea and refractive surgery, pediatric ophthalmology, oculoplasty, optical shop management, contact lens fitting, eye camps, pharmacy, and billing.

Built and maintained by **Mahalakshmi Netralaya**.

Product site: **[www.mahalakshminetralaya.com](https://mahalakshminetralaya.com)**

## Community and Enterprise

Everything in this repository is the Community edition: open source under GPL-3.0 and free to run on your own server.

Enterprise is the same code, set up and looked after by our team: installation in your eye hospital's name and logo, support, and our communication products as add-ons with their own licences. Plans and prices are on [mahalakshminetralaya.com](https://mahalakshminetralaya.com/#enterprise-plans).

---

## Status: the core system works

Every screen an eye hospital needs day to day is built, tested and documented. If you want to see it rather than read about it, there is a **[user guide with screenshots of every screen](docs/USER-GUIDE.md)**.

Mahalakshmi Netralaya previously ran on CodeIgniter 2.2.2, which reached end of life in 2017 and will not run on PHP 8. Rather than patch an unsupported framework carrying patient data, the application was rebuilt on **Laravel 12**.

Where it stands:

- **Ready to try.** Install it, load the demonstration data, and the whole workflow runs end to end: register a patient, book them in, perform eye examination, plan cataract surgery, manage glaucoma, track retina cases, manage optical shop, dispense contact lenses, run eye camps, dispense medications, bill, and read the reports
- **Tested.** 255+ feature tests covering the clinical modules, the permission rules and the writes that used to race. CI runs them on every push, alongside a dependency advisory check
- **Not yet proven in production.** The screens are new and have automated coverage rather than years of use behind them. Read the two sections near the end of this file before you put real patients in it

**What works today**

- Full eye hospital data model: 50+ tables covering patients, appointments, doctors, eye examinations, cataract evaluations, glaucoma management, retina care, cornea evaluations, pediatric ophthalmology, oculoplasty, surgeries, post-op followups, optical prescriptions, contact lens prescriptions, optical shop inventory, contact lens inventory, eye camps, pharmacy, payments
- Authentication, users, and an eye hospital role catalogue: 70+ permissions across Admin, Doctor, Nurse, Optometrist, Orthoptist, Optician, Camp Coordinator, Receptionist, Pharmacist, Pharmacy Staff, Accountant, grouped by patients, appointments, clinical, optical, contact lens, pharmacy, surgery, camps, staff, billing and administration
- Eye hospital dashboard: patient and appointment counts, surgery occupancy, clinical activity, income against expenses, recent patients and appointments
- Global search across patients and doctors
- Two scheduled jobs, `hospital:payment-reminder` and `hospital:appointment-reminder`, which notify patients who owe money or who are due in
- Settings, hospital information, barcode generation and an activity log
- A schema moderniser, described below

**Clinical and front desk**

- Patient registration, search and records. Only a name is required, so a walk-in is registerable, and age is derived from the date of birth rather than typed
- Appointment booking, as a day view by doctor and status, refusing to double book a doctor while allowing back to back slots
- Eye examinations with visual acuity, refraction, IOP, slit lamp, fundus examination
- Cataract evaluation with LOCS III grading, biometry, IOL calculation (SRK-T, Haigis, Holladay, Barrett), surgery planning
- Glaucoma evaluation with gonioscopy, optic disc analysis, RNFL OCT, visual field analysis, medication chart
- Retina evaluation with OCT macular, FFA/ICGA, anti-VEGF injection log, laser log
- Cornea evaluation with topography, pachymetry, specular microscopy, refractive surgery planning, CXL protocol
- Pediatric evaluation with squint measurement, amblyopia management, binocular vision, ROP screening
- Oculoplasty evaluation with eyelid, lacrimal, orbit assessment
- Cataract and other eye surgeries with operative notes, post-op followup protocol
- Optical prescriptions with frames, lenses, coatings, dispensing workflow
- Contact lens fitting, trial lenses, follow-up scheduling, inventory
- Optical shop inventory: frames, lenses, coatings, dispensing workflow
- Contact lens inventory: fitting, trial, dispensing, ordering
- Eye camps with screening, referrals, conversion tracking
- Pharmacy dispensing for eye drops and medications, stock management
- Patient invoicing with part payment, where the settlement state is derived from the payments rather than typed
- A doctor register, and a billable service and price list. Both feed the selects on the clinical screens, so a fresh install can be made usable without touching the database
- Reports over the new tables: outstanding lab work, prescribed against dispensed, aged debtors, surgery occupancy with length of stay. Every figure is derived when the report is opened, so none of it can drift out of step with the records
- Staff records for nurses, pharmacists, optometrists, orthoptists, opticians, camp coordinators, receptionists, and a department list
- Patient medical history: allergies, past operations and chronic conditions, with a scan or report attached where you have one
- Payment gateway credentials, stored ready for the online payment work still to come. Nothing in this release charges a card, and the screen says so
- Patient types (general, senior citizen, charity, corporate/panel, insurance/TPA, staff/doctor family, camp patients, govt scheme) with a price list per type for outpatients and inpatients; registration with Aadhaar, guardian MR number, medico-legal flag, old MR number and a webcam photo; health card and wristband printing
- OPD token queue with waiting and treatment times, walk-ins, visit fees per doctor, visit type and shift, OPD history, and weekly booking slots with blocked dates
- OPD consultation screen: examination by system, diagnosis, plan, follow-up, lab and radiology orders, cataract, glaucoma, retina, cornea, pediatric, oculoplasty sections, reusable templates
- A demonstration data seeder, so an evaluator sees a working eye hospital rather than empty tables: `php artisan db:seed --class=DemoDataSeeder`

**What changed in the schema**

The original tables packed repeating data into single columns: every drug on a prescription in one varchar, every test on a lab request in another, every charge on an invoice in a third with the prices in a fourth, matched by position. Four child tables replace those, and each legacy column is still written with a readable summary so anything reading it keeps working:

`prescription_medicine`, `lab_test`, `pharmacy_sale_item`, and `invoice_item` with `invoice_payment` alongside it.

Names and prices are copied onto each line rather than only referenced. A prescription, a lab result and an invoice are records of what was written, measured and charged on a given day, and they must not change when a catalogue or price list is next edited.

**Permissions**

The permission catalogue in `config/hospital_permissions.php` covers Admin, Doctor, Nurse, Optometrist, Orthoptist, Optician, Camp Coordinator, Receptionist, Pharmacist, Pharmacy Staff, Accountant, and the clinical routes check it. Admin is granted everything by the seeder; the other roles start with nothing and are granted what they need on the permissions screen. The menu hides what a role cannot open, so nobody is offered a link that bounces them.

If you are upgrading an install that came from ICTSchool, its permission table still holds the old student and class rights and none of the hospital ones. Run this once after migrating, or every role including Admin will be locked out of every screen:

```bash
php artisan hospital:sync-permissions          # add what is missing, grant it to Admin
php artisan hospital:sync-permissions --prune  # also clear the school leftovers
```

It never re-grants a right that was deliberately taken away, so it is safe to re-run.

**Concurrency**

Every check that guards a write now holds a row lock for the life of the transaction that does the writing: appointment slots, surgery slots, pharmacy stock, invoice balances and patient id allocation. Two people pressing the same button at the same moment get one success and one refusal rather than two successes.

The test suite runs on sqlite, where row locks are a no-op, so it cannot demonstrate the races themselves. It covers the transaction boundaries and the arithmetic around them; the locks are exercised by MySQL and MariaDB.

**What is not finished**

- Patient deposits and operation theatre payments have tables and models but no screens. Both are planned
- Reporting covers outstanding lab work, drug usage, debtors, surgery occupancy, the day book, doctor shares and OPD history. Anything else still means querying the database
- Payroll, a patient self-booking portal and thumb-scanner registration are not built
- Video visits open rooms on a Jitsi Meet server; the patient link is copied and sent by hand

**Before you put real patients in it**

The clinical screens are new. They have automated coverage rather than production miles, and a hospital system is not the place to find out what that difference is worth. If you are weighing Mahalakshmi Netralaya up for live use, please [get in touch](https://mahalakshminetralaya.com) so we can talk about your data and your migration.

Two practical things to do first. Change the `admin` password, which ships as `123456`. And if you are upgrading an install that came from ICTSchool, run `php artisan hospital:sync-permissions` after migrating, or every role including Admin will be locked out of every screen.

## Requirements

- PHP 8.2 or newer
- MySQL or MariaDB
- Composer

## Installation

```bash
git clone https://github.com/akashmahore/emr.mahalakshminetralaya.git
cd emr.mahalakshminetralaya
composer install
cp .env.example .env
php artisan key:generate
php artisan passport:keys
```

Set your database in `.env`, then:

```bash
php artisan migrate --seed
php artisan serve
```

`passport:keys` writes `storage/oauth-private.key` and `storage/oauth-public.key`. They are secrets, they are in `.gitignore`, and the API guard returns a server error until they exist.

### Docker

A development environment is included:

```bash
docker compose up -d
docker compose exec app php artisan migrate --seed
```

The application is then on http://localhost:8082.

## Schema modernisation

The schema was carried over from the original application exactly as it was, which means dates stored as `varchar(100)` and relationships stored as a loose string holding the parent row's id. Nothing stopped a bad date or an appointment pointing at a patient who had been deleted.

`hospital:modernise-schema` fixes both, and refuses to do either one blindly:

```bash
php artisan hospital:modernise-schema           # report only, changes nothing
php artisan hospital:modernise-schema --apply   # make the safe changes
```

A date column is converted to `DATE` or `DATETIME` only if every value in it parses. A relationship becomes a real foreign key only if every value is numeric and matches a row in the parent table. Anything else is skipped and reported with the reason, so you can clean the data and run it again. On a clean database it converts 41 date columns and adds 16 foreign keys.

The same work runs from a migration, so `php artisan migrate` upgrades an existing install. Read the report before applying it to live data, and take a backup.

### The school tables are gone

Thirty-three tables came across with the fork and describe a school rather than a hospital: students, classes, sections, subjects, exams, marks, GPA rules, timetables, admissions, library books, dormitories, and the family fee voucher chain. Their migrations have been removed, so a fresh install never creates them, and a migration drops them from a database that already has them.

```bash
php artisan hospital:remove-school-tables           # report only
php artisan hospital:remove-school-tables --apply   # drop the empty ones
```

A table is only dropped when it is empty. Anything holding rows is left alone and reported, so no migration quietly deletes data. Add `--force` if you want them gone regardless. A clean install is 84 tables, down from 117.

## Communications

SMS and notifications can be integrated with your preferred provider (Twilio, AWS SNS, Vonage, Plivo, etc.). Two scheduled jobs use it:

```bash
php artisan hospital:payment-reminder                  # patients with an outstanding balance
php artisan hospital:appointment-reminder              # patients due in tomorrow
php artisan hospital:appointment-reminder --days=0     # patients due in today
```

Both report why they did nothing rather than failing, so they are safe to put on cron before SMS is configured.

## Contributing

Issues and pull requests are welcome. For bug reports, the PHP version, database version and the exact error message help more than anything else.

Run the route smoke test after any significant change. It signs in, requests every route that takes no parameters, and reports the status codes:

```bash
bash smoke-test.sh
```

The current baseline is 33 pages serving, no server errors.

## Related projects

- [Mahalakshmi Netralaya](https://mahalakshminetralaya.com) - Eye Hospital Website

## Licence

GNU General Public License v3.0 - see [LICENSE](LICENSE).

Copyright (c) Mahalakshmi Netralaya.

---

Developed by Mahalakshmi Netralaya, [Mahalakshmi Netralaya](https://mahalakshminetralaya.com).
