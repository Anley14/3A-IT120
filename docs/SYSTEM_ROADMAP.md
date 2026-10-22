# Sun Son Solar system roadmap

This roadmap consolidates the stakeholder interviews in the project brief with the current CodeIgniter prototype. It is intended to guide the next development work and keep new pages consistent with the existing site.

## Current system

- Public company homepage with service information and a quote request form.
- Customer self-registration and separate customer and employee login pages.
- Customer dashboard for quote requests and shop orders.
- Role-aware customer, employee, and administrator workspaces.
- Solar shop with a session cart and order requests. The prototype does not take payments.
- MySQL schema for customer and employee accounts, admins, quote requests, shop orders, bookings, support tickets, notices, service categories, check-ins, and job reports.

The `/portal` workspace now supports customer booking and schedule management, profile/password updates, support tickets, admin booking assignment, HR account provisioning/deactivation, dispatch scheduling, technician check-ins and job reports, announcements, and editable service categories and estimate prices. Admin sign-in uses the existing `admins` table and enforces its `must_change_password` flag. Employees land on assigned work; dispatch and HR access is based on their department.

The following items still need follow-up before the system is production complete: support replies, notification read state and customer preferences, email delivery, operating-hour/service-area availability rules, audit logs, charted analytics, payment processing and receipts, and employee profile editing. Revenue must not be inferred from estimate totals.

## Requested system areas

### Customer portal

- Service schedule: view upcoming bookings, request a booking, and ask to reschedule or cancel.
- Notifications: booking confirmations and changes, reminders, promotions, and system notices, with read/unread state and customer preferences.
- Profile and security: edit contact details and address, change password, and manage communication preferences.
- Service history: completed work, order history, and receipts. Receipts should only be shown once a payment workflow exists; current shop orders are requests and do not represent paid invoices.
- Support: submit a help ticket and follow its status and replies.

### Admin, HR, and dispatch workspace

- User management: search, view, edit, create, and deactivate accounts; assign employee department and permissions; preserve records when accounts are deactivated.
- Schedule overview: calendar/list of bookings, assign technicians, approve or decline requests, and reschedule with a recorded reason.
- Reports: customer activity, service volume, order/request status, and revenue only when reliable payment data is available.
- Notification controls: compose and target announcements, reminders, promotions, or system updates; preview and audit sends.
- Settings: service categories, estimate pricing, operating hours, service areas, and booking availability.

### Employee and technician workspace

- Site check-in: one clear action to record employee, server time, and coordinates, with a manual fallback when location access is unavailable.
- Assigned jobs: show only assigned work and the customer details required to complete it.
- Announcements: employee notices, safety information, and urgent updates.
- Job reports: submit equipment issues, work notes, and customer concerns to the appropriate manager.

## Recommended next work

1. **Complete account roles and profile coverage.** Add explicit staff roles beyond department, employee profile management, and audit logging for account and booking changes.
2. **Finish support and notifications.** Add staff/customer replies, read/unread state, customer communication preferences, and mail delivery with failure handling.
3. **Complete schedule settings.** Add operating hours, service areas, blackout periods, and conflict checks before staff confirm a requested time.
4. **Build reports and history.** Add charts for activity and service volume. Add revenue, receipts, and payments only after a payment/invoice workflow exists.

## Security and data notes

- The interview attachment contains example credentials. Treat them as exposed: do not copy them into documentation, source code, or demo instructions. Rotate any matching live or seeded credentials and require password changes for provisioned accounts.
- Store password hashes only, use generic login errors, and keep session regeneration on successful login.
- Enforce authorization server-side for every page and mutation; hiding a navigation link is not access control.
- Use CSRF protection on all state-changing forms, validate every posted identifier and status, and escape user-provided content when rendering it.
- Minimize technician access to customer data and record administrative changes to users, schedules, and settings.
- Collect only necessary profile fields. Keep birth date and precise location access out of views that do not need them.
- Configure backups, retention, and a recovery process before treating the prototype as production data storage.

## Page design rules for future work

Use the current homepage and portal as the visual reference:

- Reuse `assets/css/home.css` tokens and typography: Manrope headings, DM Sans body text, navy `#203c63`, blue `#4165df`, pale blue surfaces, and yellow `#ffd23f` accents.
- Keep the existing Sun Son brand mark, content width, restrained borders, square/softly rounded controls, and compact uppercase section labels.
- Portal pages should follow the dashboard pattern in `application/views/account_dashboard.php`: branded top bar, clear page heading, summary cards where useful, white bordered content panels, obvious empty states, and responsive layouts.
- Use the shared `.wrap`, `.brand`, `.button`, `.button-lime`, and `.sr-only` styles where appropriate instead of introducing a separate design system.
- Keep forms mobile-friendly, label every control, show validation and success feedback in the established notice style, and make primary actions visually clear.
- Add responsive styles and accessible focus states for every new interactive page. Avoid introducing a new color palette or unrelated typography.

## Suggested next page set

The first useful end-to-end release should include:

1. Admin/staff login and role-aware navigation.
2. Staff user management and employee provisioning.
3. Customer booking request and schedule view.
4. Staff schedule queue with assignment and status updates.
5. Technician assigned-jobs view with site check-in and job report.

Notifications, profile management, support tickets, configurable service settings, history, and reports can then build on those records and permissions without relying on placeholder data.
