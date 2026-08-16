# Regression Checklist — Kost Bu Adah

## 1. Document Information

| Item                    | Details                   |
| ----------------------- | ------------------------- |
| Project                 | Kost Bu Adah              |
| Test Type               | Manual Regression Testing |
| Environment             | Local Development         |
| Application             | Laravel Web Application   |
| Main Roles              | Administrator, Tenant     |
| Baseline Status         | Completed                 |
| Baseline Result         | Pass                      |
| Last Baseline Execution | 16 August 2026            |

---

## 2. Purpose

This checklist is used to quickly verify that important Kost Bu Adah features still work after:

* Bug fixes
* Database changes
* Controller changes
* Authentication changes
* Authorization changes
* Repository cleanup
* Dependency updates
* Future feature development

The checklist is not intended to replace detailed manual test cases.

Detailed testing procedures are available in:

```text
docs/qa/test-cases.md
```

This document acts as a faster regression baseline for critical application flows.

---

# 3. Pre-Test Checklist

Before regression testing:

* [x] Application can start successfully.
* [x] Database connection works.
* [x] `.env` configuration is valid.
* [x] Required migrations have been applied.
* [x] Storage symbolic link is available.
* [x] Demo/test accounts are available.
* [x] Test data is available.
* [x] Browser can access `http://127.0.0.1:8000`.

For a completely fresh test environment:

```bash
php artisan migrate:fresh --seed
```

> Warning: this command deletes existing database data.

---

# 4. Fresh Setup Regression

## Database & Seeder

* [x] All migrations execute successfully.
* [x] Payment status migration executes successfully.
* [x] Database seeder completes without error.
* [x] Demo administrator is created.
* [x] Demo units are created.
* [x] Demo tenants are created.
* [x] Demo bookings are created.
* [x] Demo rentals are created.
* [x] Demo billing records are created.
* [x] Seeded data appears correctly in the application.

### Baseline Result

**PASS**

---

# 5. Administrator Authentication

* [x] Administrator can open `/login`.
* [x] Valid administrator credentials are accepted.
* [x] Invalid administrator password is rejected.
* [x] Successful login redirects to the administrator area.
* [x] Administrator can log out successfully.

### Baseline Result

**PASS**

---

# 6. Tenant Authentication

* [x] Tenant can open `/penyewa/register`.
* [x] Tenant can register using valid unique data.
* [x] Newly registered tenant account can log in.
* [x] Existing tenant can open `/penyewa/login`.
* [x] Valid tenant credentials are accepted.
* [x] Invalid tenant credentials are rejected.
* [x] Tenant can log out successfully.
* [x] Tenant authentication flow remains separate from administrator authentication.

### Baseline Result

**PASS**

---

# 7. Unit Management

## Administrator

* [x] Unit list loads successfully.
* [x] Administrator can create a unit.
* [x] Administrator can edit a unit.
* [x] Updated unit information is saved correctly.
* [x] Unit price is displayed correctly.
* [x] Unit status is displayed correctly.

## Public View

* [x] Available units appear on the public page.
* [x] Public unit details can be opened.
* [x] Occupied/booking states remain consistent with the application flow.

### Baseline Result

**PASS**

---

# 8. Booking Regression

* [x] Logged-in tenant can select an available unit.
* [x] Tenant can submit a booking.
* [x] New booking receives `pending` status.
* [x] Pending booking appears in the administrator panel.
* [x] Booking displays the correct tenant.
* [x] Booking displays the correct unit.
* [x] Administrator can approve a pending booking.
* [x] Approved booking receives the correct state.
* [x] Administrator can reject a separate pending booking.
* [x] Rejected booking does not incorrectly create an active rental.
* [x] Unit availability remains consistent after approval/rejection.

### Baseline Result

**PASS**

---

# 9. Rental Regression

* [x] Active rental is associated with the correct tenant.
* [x] Active rental is associated with the correct unit.
* [x] Occupied unit shows the correct tenant.
* [x] Active rental uses status `aktif`.
* [x] Administrator can stop/end a rental.
* [x] Completed rental uses status `selesai`.
* [x] Unit becomes available after rental completion.

### Baseline Result

**PASS**

---

# 10. Billing Regression

* [x] Tenant can view their own billing information.
* [x] Billing amount is displayed correctly.
* [x] `belum_bayar` status displays correctly.
* [x] `terlambat` status displays correctly.
* [x] `lunas` status displays correctly.
* [x] Billing record belongs to the correct rental and tenant.

### Baseline Result

**PASS**

---

# 11. Payment Regression — PAY-001

This section must be rerun after changes involving:

* Billing controller
* Billing database schema
* Payment upload
* Billing status
* File upload handling
* Payment verification

## Payment Upload

* [x] Tenant can open an unpaid bill.
* [x] Tenant can select a valid payment-proof file.
* [x] Upload completes without HTTP 500.
* [x] Payment-proof file is accepted.
* [x] Billing status changes to `menunggu_verifikasi`.

## Administrator Verification

* [x] Administrator can view billing records awaiting verification.
* [x] Submitted payment proof can be reviewed.
* [x] Administrator can approve payment proof.
* [x] Approved billing status becomes `lunas`.
* [x] Status remains correct after page refresh.

## Rejection Flow

* [x] Administrator can reject payment proof.
* [x] Rejected payment is not marked as `lunas`.
* [x] Tenant is instructed/allowed to submit new proof.
* [x] Tenant can upload replacement payment proof.
* [x] Replacement proof can be approved.
* [x] Final status becomes `lunas` only after approval.

### Baseline Result

**PASS**

### Related Defect

```text
PAY-001 — Payment Status Schema Mismatch
Status: Fixed
Regression: Pass
```

---

# 12. PDF Receipt Regression

* [x] Paid billing record provides receipt access.
* [x] PDF receipt can be generated.
* [x] PDF file can be opened.
* [x] Receipt contains the correct billing information.
* [x] Receipt belongs to the correct tenant.
* [x] PDF generation does not return HTTP 500.

### Baseline Result

**PASS**

---

# 13. Tenant Authorization Regression

This section is especially important after changes involving:

* Billing controller
* Routes
* Tenant authentication
* Rental relationships
* Receipt generation
* Authorization logic

## Billing Ownership

* [x] Tenant A can access Tenant A's own billing information.
* [x] Billing URL ID can be identified for testing.
* [x] Manually changing the ID to Tenant B's billing record does not expose Tenant B's data.
* [x] Another tenant's billing amount is not exposed.
* [x] Another tenant's payment information is not exposed.

## PDF Ownership

* [x] Tenant A can access an authorized receipt.
* [x] Receipt URL ID can be identified for testing.
* [x] Manually changing the ID to Tenant B's receipt does not expose the PDF.
* [x] Another tenant's private billing information remains protected.

### Baseline Result

**PASS**

### Related Defect

```text
SEC-001 — Cross-Tenant Billing Authorization
Status: Fixed
Regression: Pass
```

---

# 14. Repository / Build Regression

Run after dependency or repository changes.

* [x] Laravel application can start.
* [x] Route list can be generated.
* [x] Frontend dependencies install successfully.
* [x] Production frontend build completes successfully.
* [x] Nested unused Laravel project is no longer present.
* [x] Unused API test routes are removed.
* [x] `.env.example` remains safe for public repository use.
* [x] Real `.env` file is not committed.
* [x] Demo seeder remains available.

Useful commands:

```bash
php artisan route:list
npm run build
git status
```

### Baseline Result

**PASS**

---

# 15. Critical Smoke Test

When time is limited, run at least this smaller regression set:

* [ ] Administrator login works.
* [ ] Tenant login works.
* [ ] Available unit can be viewed.
* [ ] Tenant can create booking.
* [ ] Administrator can approve booking.
* [ ] Active rental is created/displayed correctly.
* [ ] Tenant can view own bill.
* [ ] Payment proof can be uploaded.
* [ ] Status becomes `menunggu_verifikasi`.
* [ ] Administrator can confirm payment.
* [ ] Status becomes `lunas`.
* [ ] PDF receipt can be opened.
* [ ] Tenant cannot access another tenant's billing record.
* [ ] Tenant cannot access another tenant's PDF receipt.

This smaller checklist should be used for quick regression after minor changes.

The complete 26-case manual baseline should be rerun after larger or riskier changes.

---

# 16. Regression Execution Rules

A checklist item should only be marked as passed when the feature has actually been tested.

Do not assume a feature still works because:

* It worked previously.
* The code change appears unrelated.
* No error appears during development.
* The application successfully starts.

If a regression fails:

1. Stop and record the failure.
2. Identify the related test case.
3. Capture the actual result.
4. Create or update a bug report.
5. Fix the defect separately.
6. Retest the failed scenario.
7. Rerun related regression tests.

---

# 17. Baseline Regression Summary

| Area                             | Result |
| -------------------------------- | ------ |
| Fresh Setup                      | Pass   |
| Administrator Authentication     | Pass   |
| Tenant Authentication            | Pass   |
| Unit Management                  | Pass   |
| Booking                          | Pass   |
| Rental                           | Pass   |
| Billing                          | Pass   |
| Payment Verification             | Pass   |
| Payment Rejection / Resubmission | Pass   |
| PDF Receipt                      | Pass   |
| Tenant Billing Authorization     | Pass   |
| Tenant PDF Authorization         | Pass   |
| Repository / Build               | Pass   |

### Overall Baseline

**PASS**

The current local portfolio baseline has no unresolved Critical or High defect within the tested scope.

---

# 18. Related QA Documentation

```text
docs/qa/
├── test-plan.md
├── test-cases.md
├── bug-reports.md
└── regression-checklist.md
```

Related results:

```text
Manual Test Cases : 26
Passed            : 26
Failed            : 0
Blocked           : 0
Pass Rate         : 100%

Documented Bugs   : 2
Fixed             : 2
Regression Passed : 2
```

---

# 19. Next QA Phase

After completing this regression baseline:

1. Add selected screenshots as test evidence.
2. Commit the initial QA documentation.
3. Continue with SQL fundamentals.
4. Create database-validation queries from the Kost Bu Adah database.
5. Later continue to HTTP/API and Postman testing.
6. Add automation only after the manual testing foundation is stable.
