# Test Plan — Kost Bu Adah

## 1. Document Information

| Item             | Details                                |
| ---------------- | -------------------------------------- |
| Project          | Kost Bu Adah                           |
| Test Type        | Manual Functional & Regression Testing |
| Application Type | Web Application                        |
| Test Environment | Local Development                      |
| Main Roles       | Administrator, Tenant                  |
| Document Version | 1.0                                    |
| Status           | In Progress                            |

---

## 2. Purpose

The purpose of this test plan is to validate the main business flows of the **Kost Bu Adah** web application and ensure that important features work correctly after project restoration and cleanup.

The testing activities also serve as practical Software QA portfolio evidence, including:

* Functional testing
* Regression testing
* Negative testing
* Authorization testing
* Data validation
* Bug reporting
* Test evidence collection

---

## 3. Test Objectives

The main testing objectives are:

1. Verify that the core boarding house management workflow works correctly.
2. Verify that administrator and tenant functions are separated properly.
3. Verify that booking, rental, billing, and payment flows work as expected.
4. Verify that tenants cannot access data belonging to other tenants.
5. Verify that important fixes do not introduce regression issues.
6. Document bugs, test results, and testing evidence in a structured format.

---

## 4. Scope

### 4.1 In Scope

The following areas are included in the current testing scope.

#### Authentication

* Administrator login
* Administrator logout
* Tenant registration
* Tenant login
* Tenant logout
* Separation between administrator and tenant authentication flows

#### Unit Management

* View unit list
* Create unit
* Edit unit
* Delete unit
* Display unit price
* Display unit status
* Display available units on the public page

#### Tenant Management

* View tenant list
* Create tenant data
* Edit tenant data
* Delete tenant data
* Tenant profile update

#### Booking

* Tenant views available units
* Tenant submits booking
* Booking appears in administrator panel
* Administrator approves booking
* Administrator rejects booking
* Unit status changes according to booking state

#### Rental

* Create an active rental
* Display active tenant on occupied unit
* Stop/end rental
* Unit becomes available after rental completion

#### Billing

* Generate/display tenant bills
* View bill status
* Display unpaid bills
* Display overdue bills
* Display paid bills
* Tenant can only access their own billing records

#### Payment Verification

* Tenant uploads payment proof
* Bill status changes to `menunggu_verifikasi`
* Administrator reviews payment proof
* Administrator confirms payment
* Bill status changes to `lunas`
* Administrator rejects invalid payment proof when applicable

#### PDF Receipt

* Paid bill can generate/download a PDF receipt
* Receipt contains relevant billing information
* Unauthorized tenant cannot access another tenant's receipt

#### Expenses and Reports

* View expense records
* Add expense record
* Edit expense record
* Delete expense record
* View available reports
* Verify export features at a basic functional level

#### Fresh Setup / Demo Data

* Database migrations complete successfully
* Demo seeder completes successfully
* Seeded data appears correctly in the application
* Demo administrator can log in
* Demo tenant accounts can log in

---

## 5. Out of Scope

The following areas are not the main focus of this testing phase:

* Production deployment
* Production server configuration
* Performance/load testing
* Stress testing
* Penetration testing
* Cross-browser testing on many browser/device combinations
* Mobile application testing
* Full accessibility audit
* Midtrans payment gateway integration
* Automated UI testing
* Automated API testing

Midtrans-related code exists in the repository, but Midtrans is not required for the current local portfolio demonstration.

Automation testing will be considered in a later phase after the manual testing foundation is complete.

---

## 6. Test Environment

Current local testing environment:

| Component        | Environment                |
| ---------------- | -------------------------- |
| Operating System | Windows                    |
| Application      | Laravel 12                 |
| PHP              | 8.4                        |
| Database         | MySQL 8                    |
| Web Server       | Laravel development server |
| Local URL        | `http://127.0.0.1:8000`    |
| Browser          | Google Chrome              |
| Package Manager  | Composer / npm             |
| Version Control  | Git / GitHub               |

Application start command:

```bash
php artisan serve
```

Fresh database validation command:

```bash
php artisan migrate:fresh --seed
```

---

## 7. Test Accounts and Demo Data

The database seeder provides repeatable demo data for testing.

### Administrator

```text
Email: admin@kostbuadah.test
Password: password
```

### Tenant Accounts

```text
081200000001
081200000002
081200000003
```

Password for all demo tenant accounts:

```text
password
```

The demo database includes:

* 4 units
* 3 tenants
* 2 bookings
* 2 rentals
* 3 billing records

The data represents multiple application states such as available, booked, occupied, unpaid, overdue, and paid.

---

## 8. Test Approach

Testing will primarily use **manual black-box testing** from the user's point of view.

The tester will verify application behavior using expected business requirements without relying only on internal implementation details.

The following techniques will be used:

### Positive Testing

Verify that valid input and normal user actions produce the expected result.

Example:

> A registered tenant submits a booking for an available unit and the booking appears in the administrator panel.

### Negative Testing

Verify that invalid input or unauthorized actions are rejected properly.

Example:

> A tenant attempts to access another tenant's billing record by modifying the record ID in the URL.

### Boundary and Validation Testing

Verify form validation and important input restrictions.

Examples:

* Required fields are empty
* Duplicate phone number
* Duplicate identity number
* Invalid credentials
* Unsupported payment status

### Regression Testing

Previously working critical features will be retested after a bug fix or project change.

Regression testing is especially important for:

* Authentication
* Booking
* Rental creation
* Billing
* Payment verification
* PDF receipt generation
* Authorization

---

## 9. Critical Business Flows

### Flow 1 — Tenant Booking

```text
Tenant Registration/Login
        ↓
View Available Unit
        ↓
Submit Booking
        ↓
Booking Pending
        ↓
Administrator Reviews Booking
        ↓
Approve / Reject
```

Expected result:

* Booking is stored correctly.
* Administrator can review the request.
* Unit status is updated according to the booking state.

---

### Flow 2 — Rental

```text
Approved Booking / Available Unit
        ↓
Rental Created
        ↓
Tenant Occupies Unit
        ↓
Unit Status = terisi
```

Expected result:

* Rental is associated with the correct tenant and unit.
* Occupied unit displays the correct tenant.

---

### Flow 3 — Payment

```text
Tenant Has Bill
        ↓
Upload Payment Proof
        ↓
menunggu_verifikasi
        ↓
Administrator Reviews Payment
        ↓
Confirm Payment
        ↓
lunas
```

Expected result:

* Payment proof is saved.
* Billing status changes correctly.
* No HTTP 500 or database error occurs.

---

### Flow 4 — PDF Receipt

```text
Bill Status = lunas
        ↓
Download Receipt
        ↓
PDF Generated
```

Expected result:

* PDF can be generated successfully.
* Receipt contains information related to the correct bill and tenant.

---

### Flow 5 — Tenant Data Isolation

```text
Tenant A Login
        ↓
Open Own Bill
        ↓
Change Bill ID in URL
        ↓
Attempt to Access Tenant B's Bill
```

Expected result:

> Tenant A must not be able to access Tenant B's billing information or receipt.

---

## 10. Entry Criteria

Testing can begin when:

* Application starts successfully.
* Database connection works.
* Required migrations have been applied.
* Demo data is available.
* Administrator login works.
* Tenant login works.
* Critical pages can be opened without startup errors.

---

## 11. Exit Criteria

The current manual testing phase can be considered complete when:

* All critical business flows have test cases.
* All critical test cases have been executed.
* No unresolved Critical or High severity bug blocks the main workflow.
* Important Medium severity defects are documented.
* Previously fixed critical bugs pass regression testing.
* Authorization tests for tenant-owned data pass.
* Payment flow passes from upload through PDF receipt.
* Testing evidence is stored for important test cases.
* Test results are documented clearly.

---

## 12. Defect Severity

The following severity levels will be used.

### Critical

The application or major business process is completely unusable.

Example:

* Application cannot start.
* Database is unavailable for all users.

### High

A critical business feature is blocked, but the entire application may still run.

Example:

* Tenant cannot upload payment proof.
* Payment confirmation always returns HTTP 500.
* Tenant can access another tenant's private billing data.

### Medium

A feature works partially or has a significant issue with an available workaround.

Example:

* Incorrect validation message.
* Export contains incorrect formatting.

### Low

Minor issue that does not significantly affect the business flow.

Example:

* Typographical error.
* Small visual alignment issue.

---

## 13. Defect Reporting

Each important defect should contain at least:

* Bug ID
* Title
* Area/module
* Preconditions
* Steps to reproduce
* Expected result
* Actual result
* Severity
* Priority when needed
* Environment
* Evidence
* Root cause when known
* Fix status
* Regression result

Known example:

```text
PAY-001 — Payment Status Schema Mismatch
Severity: High
Status: Fixed and regression tested
```

---

## 14. Test Deliverables

The QA portfolio for this project is planned to contain:

* `test-plan.md`
* Manual test cases
* Regression checklist
* Formal bug reports
* Screenshots or other test evidence
* Test execution results
* Future automated test examples

Planned directory structure:

```text
docs/
└── qa/
    ├── test-plan.md
    ├── test-cases.md
    ├── bug-reports.md
    ├── regression-checklist.md
    └── evidence/
```

---

## 15. Risks and Limitations

### Local Environment Only

The application is currently tested locally, so production infrastructure behavior is outside this test plan.

### Demo Data

Seeded records are synthetic demo data and do not represent real tenants.

### Browser Coverage

Initial manual testing mainly uses Google Chrome. Full browser compatibility testing has not yet been performed.

### Payment Gateway

Midtrans integration is not part of the current main testing scope.

### Automation Coverage

Current testing is primarily manual. Automated tests will be added after manual test scenarios and expected behavior are documented.

---

## 16. Current QA Status

Completed before this test plan:

* Fresh clone restoration
* Fresh database migration validation
* Demo database seeder validation
* Payment workflow bug investigation
* Payment status migration fix
* Payment regression validation
* PDF receipt validation
* Tenant billing authorization improvement
* Repository cleanup
* Portfolio README

Current phase:

> **Formalize the manual QA work into structured test documentation.**

Next activity:

> **Create and execute manual test cases for the main Kost Bu Adah business flows.**
