# Manual Test Cases — Kost Bu Adah

## 1. Document Information

| Item             | Details                                |
| ---------------- | -------------------------------------- |
| Project          | Kost Bu Adah                           |
| Test Type        | Manual Functional & Regression Testing |
| Application Type | Web Application                        |
| Environment      | Local Development                      |
| Base URL         | `http://127.0.0.1:8000`                |
| Main Roles       | Administrator, Tenant                  |
| Total Test Cases | 26                                     |
| Executed         | 26                                     |
| Passed           | 26                                     |
| Failed           | 0                                      |
| Blocked          | 0                                      |
| Execution Date   | 16 August 2026                         |
| Final Status     | **PASS**                               |

---

## 2. Test Result Definition

* **Pass** — Actual result matches the expected result.
* **Fail** — Actual result does not match the expected result.
* **Blocked** — Test cannot be completed because another issue prevents execution.
* **Not Run** — Test has not yet been executed.

All test cases in this document have been manually executed.

---

# A. Authentication

## TC-AUTH-001 — Administrator Login with Valid Credentials

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

* Application is running.
* Demo administrator account exists.

### Test Data

```text
Email: admin@kostbuadah.test
Password: password
```

### Steps

1. Open `/login`.
2. Enter the valid administrator email.
3. Enter the valid password.
4. Submit the login form.

### Expected Result

* Login succeeds.
* Administrator is redirected to the admin dashboard.
* Administrator navigation is available.

### Actual Result

Administrator successfully logged in and the admin dashboard was displayed.

---

## TC-AUTH-002 — Administrator Login with Invalid Password

**Priority:** High
**Type:** Negative
**Status:** **Pass**

### Steps

1. Open `/login`.
2. Enter `admin@kostbuadah.test`.
3. Enter an incorrect password.
4. Submit the login form.

### Expected Result

* Login is rejected.
* User remains unauthenticated.
* Authentication error is displayed.

### Actual Result

Invalid credentials were rejected and the user was not allowed to enter the administrator dashboard.

---

## TC-AUTH-003 — Tenant Login with Valid Credentials

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Test Data

```text
Phone: 081200000002
Password: password
```

### Steps

1. Open `/penyewa/login`.
2. Enter a valid demo tenant phone number.
3. Enter the valid password.
4. Submit the form.

### Expected Result

* Tenant login succeeds.
* Tenant is redirected to the tenant portal.
* Tenant dashboard is displayed.

### Actual Result

Tenant successfully logged in and the tenant dashboard was displayed.

---

## TC-AUTH-004 — Tenant Login with Invalid Credentials

**Priority:** High
**Type:** Negative
**Status:** **Pass**

### Steps

1. Open `/penyewa/login`.
2. Enter a valid tenant phone number.
3. Enter an incorrect password.
4. Submit the form.

### Expected Result

* Login is rejected.
* Tenant remains unauthenticated.
* Authentication error is displayed.

### Actual Result

Invalid credentials were rejected and the tenant could not access the tenant portal.

---

## TC-AUTH-005 — Tenant Registration with Valid Data

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

Use a phone number and identity number that do not already exist.

### Steps

1. Open `/penyewa/register`.
2. Fill all required fields with valid data.
3. Enter a unique phone number.
4. Enter a unique identity number.
5. Enter a valid password.
6. Submit the registration form.
7. Log in using the newly registered tenant account.

### Expected Result

* Registration succeeds.
* New tenant data is stored.
* New tenant account can be used to log in.

### Actual Result

New tenant registration completed successfully and the newly created account could log in afterward.

---

# B. Unit Management

## TC-UNIT-001 — Administrator Views Unit List

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

Administrator is logged in.

### Steps

1. Open `/units`.
2. Review the unit list.

### Expected Result

* Unit page loads successfully.
* Unit records are displayed.
* Unit name, price, and status are visible.

### Actual Result

The unit list loaded successfully and unit information was displayed correctly.

---

## TC-UNIT-002 — Administrator Creates a New Unit

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

Administrator is logged in.

### Steps

1. Open `/units/create`.
2. Enter valid unit information.
3. Enter a valid price.
4. Select an allowed unit status.
5. Submit the form.
6. Return to the unit list.

### Expected Result

* Unit is created successfully.
* New unit appears in the unit list.
* Stored values match the submitted values.

### Actual Result

The new unit was successfully created and appeared in the unit list with the correct information.

---

## TC-UNIT-003 — Administrator Edits Existing Unit

**Priority:** Medium
**Type:** Positive
**Status:** **Pass**

### Preconditions

Administrator is logged in and at least one unit exists.

### Steps

1. Open the unit list.
2. Select an existing unit.
3. Open the edit page.
4. Modify unit information.
5. Save the changes.
6. Review the unit again.

### Expected Result

* Update succeeds.
* Updated values are displayed correctly.

### Actual Result

Unit information was successfully updated and the new values were displayed correctly.

---

## TC-UNIT-004 — Public User Views Available Unit

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

At least one unit has status `tersedia`.

### Steps

1. Open the public application page.
2. Find an available unit.
3. Open the unit details.

### Expected Result

* Available unit is displayed publicly.
* Unit details can be opened.
* Unit information is correct.

### Actual Result

Available unit information and details were displayed successfully on the public page.

---

# C. Booking

## TC-BOOK-001 — Tenant Submits Booking for Available Unit

**Priority:** Critical
**Type:** Positive
**Status:** **Pass**

### Preconditions

* Tenant is logged in.
* Selected unit has status `tersedia`.

### Steps

1. Open an available unit.
2. Start the booking process.
3. Enter a valid move-in date.
4. Submit the booking.

### Expected Result

* Booking is created.
* Booking status becomes `pending`.
* Booking belongs to the logged-in tenant.
* Unit enters the correct booking state.

### Actual Result

Booking was successfully created with `pending` status and linked to the correct tenant and unit.

---

## TC-BOOK-002 — Pending Booking Appears in Administrator Panel

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

A pending booking exists.

### Steps

1. Log in as administrator.
2. Open `/bookings`.
3. Locate the submitted booking.

### Expected Result

* Booking appears in the administrator panel.
* Correct tenant is displayed.
* Correct unit is displayed.
* Status is `pending`.

### Actual Result

The pending booking appeared correctly in the administrator booking list.

---

## TC-BOOK-003 — Administrator Approves Pending Booking

**Priority:** Critical
**Type:** Positive
**Status:** **Pass**

### Preconditions

A pending booking exists.

### Steps

1. Log in as administrator.
2. Open the booking list.
3. Select a pending booking.
4. Approve the booking.

### Expected Result

* Booking status becomes `approved`.
* Rental-related processing completes correctly.
* Unit is no longer freely available.

### Actual Result

Booking approval completed successfully and the unit/rental state was updated correctly.

---

## TC-BOOK-004 — Administrator Rejects Pending Booking

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

A separate pending booking exists.

### Steps

1. Log in as administrator.
2. Open the booking list.
3. Select the pending booking.
4. Reject the booking.

### Expected Result

* Booking status becomes `rejected`.
* No incorrect active rental is created.
* Unit availability remains consistent.

### Actual Result

Booking rejection completed successfully and the unit remained in the correct availability state.

---

# D. Rental

## TC-SEWA-001 — Active Rental Displays Correct Tenant and Unit

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

An active rental exists.

### Steps

1. Log in as administrator.
2. Locate the occupied unit.
3. Review the tenant and rental information.

### Expected Result

* Correct tenant is associated with the unit.
* Rental status is `aktif`.
* Unit status is `terisi`.

### Actual Result

The active rental displayed the correct tenant and unit, and the occupied unit status was correct.

---

## TC-SEWA-002 — Administrator Ends Active Rental

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

An active rental exists.

### Steps

1. Log in as administrator.
2. Locate an active rental.
3. Use the stop/end rental action.
4. Confirm the action when required.

### Expected Result

* Rental becomes `selesai`.
* Rental end information is updated.
* Unit becomes available again.

### Actual Result

The rental was successfully ended and the unit returned to the available state.

---

# E. Billing

## TC-BILL-001 — Tenant Views Own Billing Information

**Priority:** Critical
**Type:** Positive
**Status:** **Pass**

### Preconditions

Tenant has billing data.

### Steps

1. Log in as tenant.
2. Open the tenant dashboard.
3. Locate billing information.
4. Open the bill.

### Expected Result

* Tenant can view their own bill.
* Amount and status are correct.
* Billing information belongs to the logged-in tenant.

### Actual Result

The tenant successfully viewed their own billing information with the correct data.

---

## TC-BILL-002 — Unpaid Bill Displays Correct Status

**Priority:** High
**Type:** Positive
**Status:** **Pass**

### Preconditions

A billing record with status `belum_bayar` exists.

### Steps

1. Open the billing page.
2. Locate the unpaid bill.

### Expected Result

* Bill is displayed.
* Status represents `belum_bayar`.
* Payment action is available when applicable.

### Actual Result

The unpaid billing record displayed the correct status and payment action.

---

## TC-BILL-003 — Overdue Bill Displays Correct Status

**Priority:** Medium
**Type:** Positive
**Status:** **Pass**

### Preconditions

A billing record with status `terlambat` exists.

### Steps

1. Open the billing page.
2. Locate the overdue bill.

### Expected Result

* Bill is displayed.
* Status represents an overdue bill.

### Actual Result

The overdue bill was displayed correctly with the expected status.

---

# F. Payment Verification

## TC-PAY-001 — Tenant Uploads Valid Payment Proof

**Priority:** Critical
**Type:** Positive / Regression
**Status:** **Pass**

### Preconditions

* Tenant is logged in.
* Tenant has an unpaid bill.
* A valid test payment-proof file is available.

### Steps

1. Open the unpaid bill.
2. Open the payment upload page.
3. Select a valid test file.
4. Submit the upload.

### Expected Result

* Upload succeeds without HTTP 500.
* Payment proof is stored.
* Billing status changes to `menunggu_verifikasi`.
* Administrator can review the submitted proof.

### Actual Result

Payment proof was successfully uploaded and the bill status changed to `menunggu_verifikasi` without database errors.

### Regression Reference

This test validates the fix for **PAY-001 — Payment Status Schema Mismatch**.

---

## TC-PAY-002 — Administrator Confirms Payment

**Priority:** Critical
**Type:** Positive / Regression
**Status:** **Pass**

### Preconditions

A billing record has status `menunggu_verifikasi`.

### Steps

1. Log in as administrator.
2. Open the billing page.
3. Locate the bill awaiting verification.
4. Review the submitted payment proof.
5. Confirm the payment.

### Expected Result

* Confirmation succeeds.
* Billing status changes to `lunas`.
* No database or HTTP error occurs.

### Actual Result

Administrator confirmation succeeded and the billing status changed to `lunas`.

---

## TC-PAY-003 — Administrator Rejects Payment Proof

**Priority:** High
**Type:** Negative / Functional
**Status:** **Pass**

### Preconditions

A billing record is waiting for payment verification.

### Steps

1. Log in as administrator.
2. Open the billing record.
3. Reject the submitted payment proof.
4. Log in again as the tenant.
5. Review the billing/payment state.
6. Upload a new payment proof.
7. Approve the new proof as administrator.

### Expected Result

* Rejected proof is not treated as a successful payment.
* Tenant is allowed or instructed to submit another payment proof.
* Billing status does not become `lunas` until payment is approved.

### Actual Result

The payment proof was successfully rejected.

The tenant was instructed to submit a new payment proof. After a new proof was uploaded and approved by the administrator, the billing status changed to `lunas`.

---

# G. PDF Receipt

## TC-PDF-001 — Download Receipt for Paid Bill

**Priority:** High
**Type:** Positive / Regression
**Status:** **Pass**

### Preconditions

A billing record has status `lunas`.

### Steps

1. Open the paid billing record.
2. Select the receipt download action.
3. Open the generated PDF.

### Expected Result

* PDF generation succeeds.
* File can be opened.
* Receipt contains the correct billing and tenant information.
* No HTTP 500 occurs.

### Actual Result

The PDF receipt was successfully generated and opened with the expected billing information.

---

# H. Authorization & Security

## TC-SEC-001 — Tenant Cannot Access Another Tenant's Billing Record

**Priority:** Critical
**Type:** Negative / Authorization / Regression
**Status:** **Pass**

### Preconditions

* Tenant A and Tenant B exist.
* Tenant B has a billing record.
* Tenant A is logged in.

### Steps

1. Log in as Tenant A.
2. Open Tenant A's own billing information.
3. Observe the billing record URL.
4. Replace the billing ID with an ID belonging to Tenant B.
5. Request the modified URL.

### Expected Result

* Tenant A cannot view Tenant B's billing information.
* Private data belonging to Tenant B is not exposed.

### Actual Result

Access to another tenant's billing record was blocked after manually changing the record ID in the URL.

Tenant B's billing information was not displayed.

### Regression Reference

This validates the tenant billing ownership/authorization fix.

---

## TC-SEC-002 — Tenant Cannot Access Another Tenant's PDF Receipt

**Priority:** Critical
**Type:** Negative / Authorization / Regression
**Status:** **Pass**

### Preconditions

* Tenant A is logged in.
* Tenant B has a paid billing record with a receipt.

### Steps

1. Open an authorized receipt to identify the URL pattern.
2. Replace the billing/receipt ID with an ID belonging to Tenant B.
3. Request the modified URL.

### Expected Result

* Tenant A cannot access Tenant B's receipt.
* Tenant B's private billing information is not exposed.

### Actual Result

Access to another tenant's PDF receipt was blocked after manually changing the billing ID.

The other tenant's receipt and private billing data were not exposed.

---

# I. Fresh Setup & Demo Data

## TC-SETUP-001 — Fresh Database Migration and Seeding

**Priority:** Critical
**Type:** Installation / Regression
**Status:** **Pass**

### Steps

Run:

```bash
php artisan migrate:fresh --seed
```

### Expected Result

* Existing development tables are removed.
* All migrations complete successfully.
* Database seeding completes successfully.
* No migration or database exception occurs.

### Actual Result

All migrations completed successfully and the demo database seeder ran without errors.

### Result

**Pass**

---

## TC-SETUP-002 — Demo Data Available After Fresh Seed

**Priority:** High
**Type:** Data Validation
**Status:** **Pass**

### Preconditions

`php artisan migrate:fresh --seed` completed successfully.

### Expected Seed Data

* 1 administrator
* 4 units
* 3 tenants
* 2 bookings
* 2 rentals
* 3 billing records

### Expected Result

Seeded data is available and displayed correctly by the application.

### Actual Result

Demo administrator, units, tenants, bookings, rentals, and billing records were successfully created.

Seeded unit and tenant data was also verified through the application UI.

### Result

**Pass**

---

# 3. Test Case Summary

| Module                   | Test Cases | Passed | Failed |
| ------------------------ | ---------: | -----: | -----: |
| Authentication           |          5 |      5 |      0 |
| Unit Management          |          4 |      4 |      0 |
| Booking                  |          4 |      4 |      0 |
| Rental                   |          2 |      2 |      0 |
| Billing                  |          3 |      3 |      0 |
| Payment Verification     |          3 |      3 |      0 |
| PDF Receipt              |          1 |      1 |      0 |
| Authorization & Security |          2 |      2 |      0 |
| Fresh Setup              |          2 |      2 |      0 |
| **Total**                |     **26** | **26** |  **0** |

---

# 4. Final Execution Summary

| Result  |  Count |
| ------- | -----: |
| Pass    | **26** |
| Fail    |  **0** |
| Blocked |  **0** |
| Not Run |  **0** |
| Total   | **26** |

### Pass Rate

```text
26 / 26 = 100%
```

All manual test cases defined in the current baseline were successfully executed.

No unresolved defect blocked the tested core business workflow during final execution.

---

# 5. Critical Flow Result

The main end-to-end boarding house workflow successfully passed manual testing:

```text
Tenant Registration
        ↓
Tenant Login
        ↓
View Available Unit
        ↓
Submit Booking
        ↓
Admin Reviews Booking
        ↓
Booking Approved
        ↓
Active Rental
        ↓
Billing
        ↓
Upload Payment Proof
        ↓
menunggu_verifikasi
        ↓
Admin Review
        ↓
lunas
        ↓
PDF Receipt
```

The rejection flow was also validated:

```text
Upload Payment Proof
        ↓
menunggu_verifikasi
        ↓
Admin Rejects Proof
        ↓
Tenant Submits New Proof
        ↓
Admin Approves
        ↓
lunas
```

---

# 6. Security Validation Result

Tenant data isolation was manually verified.

The following attempts were tested:

```text
Tenant A
   ↓
Open Own Billing Record
   ↓
Modify Billing ID in URL
   ↓
Attempt to Access Tenant B
```

**Result: Access blocked.**

The same validation was performed against PDF receipt access.

**Result: Access blocked.**

Tenant A was unable to view Tenant B's billing information or PDF receipt through manual URL ID manipulation.

---

# 7. Regression Result — PAY-001

## Original Defect

Uploading payment proof previously caused an HTTP 500 error because the application attempted to save:

```text
menunggu_verifikasi
```

while the database ENUM did not support that value.

## Regression Test

The following flow was executed after the migration fix:

```text
belum_bayar
      ↓
Upload Proof
      ↓
menunggu_verifikasi
      ↓
Admin Confirmation
      ↓
lunas
      ↓
PDF Receipt
```

### Result

**PASS**

The HTTP 500 database error did not reoccur.

The rejection and resubmission flow was also tested successfully.

---

# 8. Test Environment

| Component             | Version / Environment   |
| --------------------- | ----------------------- |
| Operating System      | Windows                 |
| Application Framework | Laravel 12              |
| PHP                   | 8.4                     |
| Database              | MySQL 8                 |
| Browser               | Google Chrome           |
| Application URL       | `http://127.0.0.1:8000` |
| Version Control       | Git / GitHub            |
| Test Type             | Manual                  |

---

# 9. Test Data Note

Testing used synthetic/demo information.

No real tenant identity documents, payment credentials, or production secrets are required for this test suite.

Demo data can be recreated using:

```bash
php artisan migrate:fresh --seed
```

---

# 10. Final Test Conclusion

The current manual regression baseline for Kost Bu Adah has been completed successfully.

**26 of 26 test cases passed.**

The tested scope confirms that the following core areas are functioning as expected in the local portfolio environment:

* Administrator authentication
* Tenant authentication and registration
* Unit management
* Booking approval and rejection
* Rental management
* Billing
* Payment-proof upload
* Payment verification and rejection
* PDF receipt generation
* Tenant billing authorization
* Tenant receipt authorization
* Fresh database migration
* Reproducible demo data

This result represents the current **manual QA baseline**.

Future changes to the application should rerun relevant test cases as regression tests rather than assuming previously working features remain unaffected.

---

# 11. Next QA Deliverables

The next documentation activities are:

1. Formalize the **PAY-001 bug report**.
2. Create a reusable **regression checklist**.
3. Store selected testing evidence.
4. Commit the QA documentation to the `qa-documentation` branch.
5. Later expand testing into SQL, API/Postman, and automated tests according to the portfolio roadmap.
