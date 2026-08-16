# Bug Reports — Kost Bu Adah

## 1. Document Information

| Item                     | Details                      |
| ------------------------ | ---------------------------- |
| Project                  | Kost Bu Adah                 |
| Test Environment         | Local Development            |
| Application              | Laravel Web Application      |
| Tester                   | Project Owner / QA Portfolio |
| Document Status          | Completed                    |
| Total Documented Defects | 2                            |

This document records significant defects discovered during the restoration and QA testing of the Kost Bu Adah application.

The focus is not only on the defect itself, but also on reproduction, impact, root-cause investigation, resolution, and regression validation.

---

# BUG-001 — PAY-001: Payment Status Schema Mismatch

## Summary

| Field             | Details                                                                                      |
| ----------------- | -------------------------------------------------------------------------------------------- |
| Bug ID            | PAY-001                                                                                      |
| Title             | Uploading payment proof causes HTTP 500 because billing status is missing from database ENUM |
| Module            | Billing / Payment Verification                                                               |
| Severity          | High                                                                                         |
| Priority          | High                                                                                         |
| Type              | Functional / Database Schema                                                                 |
| Status            | Fixed                                                                                        |
| Regression Status | Pass                                                                                         |

---

## Description

When a tenant uploaded payment proof, the application attempted to change the billing status to:

```text
menunggu_verifikasi
```

The application code already supported this status, but the database schema did not.

As a result, the payment workflow failed with an HTTP 500 database error.

---

## Preconditions

* Tenant is authenticated.
* Tenant has an active billing record.
* Billing status is `belum_bayar`.
* Tenant has a valid payment-proof file available.

---

## Steps to Reproduce

1. Log in as a tenant.
2. Open an unpaid billing record.
3. Open the payment upload page.
4. Select a valid payment-proof file.
5. Submit the payment proof.

---

## Expected Result

The application should:

```text
Save payment proof
        ↓
Change billing status
        ↓
menunggu_verifikasi
        ↓
Allow administrator verification
```

No server or database error should occur.

---

## Actual Result

The application returned an HTTP 500 error.

The database rejected the attempted status update because `menunggu_verifikasi` was not included in the allowed ENUM values for the billing status column.

---

## Impact

The defect blocked a critical payment workflow.

Tenants could not complete payment-proof submission successfully, preventing administrators from continuing payment verification.

Because payment processing is part of the main business flow, the defect was classified as **High severity**.

---

## Investigation

The application code and Git history were reviewed.

The investigation showed that application logic had been updated to use:

```text
menunggu_verifikasi
```

However, the corresponding database migration had not been added.

The application and database schema therefore represented different versions of the expected billing state.

---

## Root Cause

A new application state was introduced in the billing workflow without updating the database ENUM schema.

Application logic expected:

```text
belum_bayar
menunggu_verifikasi
lunas
terlambat
```

while the existing database schema did not contain:

```text
menunggu_verifikasi
```

This caused a schema/application mismatch.

---

## Resolution

A new database migration was added to update the billing status ENUM.

The final supported states became:

```text
belum_bayar
menunggu_verifikasi
lunas
terlambat
```

The fix was committed separately as part of the project restoration work.

---

## Regression Test

The complete payment workflow was tested again after applying the migration.

### Approval Flow

```text
belum_bayar
      ↓
Upload Payment Proof
      ↓
menunggu_verifikasi
      ↓
Administrator Confirms Payment
      ↓
lunas
      ↓
PDF Receipt
```

### Result

**PASS**

No HTTP 500 error occurred.

---

## Rejection Flow

The payment rejection flow was also validated:

```text
Upload Payment Proof
        ↓
menunggu_verifikasi
        ↓
Administrator Rejects Proof
        ↓
Tenant Is Asked to Submit New Proof
        ↓
Tenant Uploads New Proof
        ↓
Administrator Approves
        ↓
lunas
```

### Result

**PASS**

The bill was not incorrectly marked as paid after rejection.

---

## Related Test Cases

* `TC-PAY-001`
* `TC-PAY-002`
* `TC-PAY-003`
* `TC-PDF-001`

All related regression tests passed.

---

## Final Status

**FIXED — REGRESSION PASSED**

---

# BUG-002 — SEC-001: Cross-Tenant Billing Authorization

## Summary

| Field             | Details                                                                               |
| ----------------- | ------------------------------------------------------------------------------------- |
| Bug ID            | SEC-001                                                                               |
| Title             | Tenant billing resources required ownership validation to prevent cross-tenant access |
| Module            | Billing / PDF Receipt / Authorization                                                 |
| Severity          | High                                                                                  |
| Priority          | High                                                                                  |
| Type              | Authorization / Data Isolation                                                        |
| Status            | Fixed                                                                                 |
| Regression Status | Pass                                                                                  |

---

## Description

Billing resources are identified using record IDs in application URLs.

Without proper ownership validation, using only a billing record ID to retrieve data can create a risk where an authenticated tenant attempts to access another tenant's billing information by changing the ID in the URL.

Tenant-owned billing resources must therefore be validated against the currently authenticated tenant.

---

## Preconditions

* Tenant A exists.
* Tenant B exists.
* Both tenants have separate rental/billing data.
* Tenant A is authenticated.

---

## Test Scenario

1. Log in as Tenant A.
2. Open Tenant A's authorized billing record.
3. Observe the billing record ID used by the application.
4. Replace the ID with a billing ID belonging to Tenant B.
5. Request the modified URL.

The same approach is tested against PDF receipt access.

---

## Security Expectation

A tenant must only access billing data belonging to their own rental/account.

Changing an ID in the URL must not expose another tenant's:

* Billing amount
* Billing status
* Payment information
* Tenant information
* PDF receipt

---

## Risk

If ownership validation is missing, the problem can become an **IDOR-style authorization issue** where authenticated users access resources that do not belong to them.

The impact is considered **High** because billing information is private tenant data.

---

## Root Cause

Resource access needed to validate both:

1. The requested billing record.
2. Ownership of that billing record through the authenticated tenant's rental relationship.

Authorization based only on the presence of a valid database record ID is not sufficient.

---

## Resolution

Billing access was updated so requested billing resources are checked against the authenticated tenant's ownership relationship.

The fix prevents a tenant from using another tenant's billing ID to access protected billing resources.

---

## Regression Test 1 — Billing Record

### Steps

1. Log in as Tenant A.
2. Open Tenant A's billing information.
3. Modify the billing ID to Tenant B's billing ID.
4. Request the modified URL.

### Expected Result

Tenant B's billing data must not be displayed.

### Actual Result

Access was blocked.

Tenant B's billing information was not exposed.

### Result

**PASS**

---

## Regression Test 2 — PDF Receipt

### Steps

1. Log in as Tenant A.
2. Identify the receipt URL pattern using an authorized billing record.
3. Replace the billing ID with a paid billing record belonging to Tenant B.
4. Request the modified receipt URL.

### Expected Result

Tenant A must not receive Tenant B's PDF receipt.

### Actual Result

Access was blocked.

Tenant B's PDF receipt and private billing information were not exposed.

### Result

**PASS**

---

## Related Test Cases

* `TC-BILL-001`
* `TC-SEC-001`
* `TC-SEC-002`
* `TC-PDF-001`

All related authorization tests passed.

---

## Final Status

**FIXED — REGRESSION PASSED**

---

# 3. Defect Summary

| Bug ID  | Module                  | Severity | Status | Regression |
| ------- | ----------------------- | -------- | ------ | ---------- |
| PAY-001 | Payment / Billing       | High     | Fixed  | Pass       |
| SEC-001 | Authorization / Billing | High     | Fixed  | Pass       |

---

# 4. QA Lessons Learned

## Application Code and Database Schema Must Stay Synchronized

PAY-001 demonstrated that a feature can appear correctly implemented in controller and UI code while still failing because the database schema was not updated.

A fresh database reconstruction helped expose this inconsistency.

---

## A Fresh Clone Is a Useful Test Environment

The project previously depended on development history from an older environment.

Rebuilding the application from:

```text
Fresh clone
    ↓
Fresh database
    ↓
All migrations
    ↓
Demo seeder
```

helped reveal issues that might remain hidden in a long-lived local database.

---

## Authentication Is Not the Same as Authorization

SEC-001 reinforces an important distinction:

```text
Authentication
= Who is the user?

Authorization
= Is this user allowed to access this resource?
```

A logged-in tenant must still be prevented from accessing resources owned by another tenant.

---

## Regression Testing Is Required After a Fix

A successful code change alone was not considered enough.

Both significant defects were retested through their related business flows after fixes were applied.

---

# 5. Final Defect Status

At the end of the current manual QA baseline:

```text
Documented defects : 2
Fixed defects      : 2
Regression passed  : 2
Open High defects  : 0
```

No documented High-severity defect remains unresolved within the current tested scope.
