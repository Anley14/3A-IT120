# CIsunsunsolar
This is the a project for Sunson Solar Company being developed by the group pitipiwpiw

## Team Pitipiwpiw

Documentation - John Ree Huenda
Data Analyst  - Jonna Mae Londera
Front End     - Marion Leonard Perez
Back End      - Micho Andrew Patañag

### Description

Sun Son Solar is a web-based application developed by Team Pitipiwpiw for Sun Son Solar, a business focused on providing solar energy solutions. The application is designed to showcase the company’s products and services while providing customers with an accessible and convenient way to learn more about their solar solutions.

### Solar shop

Visit `/shop` to browse illustrative solar packages, assessments, and maintenance plans. Customers can add items to a session cart, submit an order request, and review order status from the customer portal. Employees can review shop orders and update their status from the employee workspace. The prototype stores orders for staff follow-up and does not process online payments. Catalog entries and estimate prices are maintained in `application/config/shop_products.php`.

The shop tables (`shop_orders` and `shop_order_items`) are included in `database/sunson.sql`. Existing databases are upgraded when the shop or dashboard is opened.

### Customer, employee, and admin workspaces

Sign in through the customer, employee, or administrator login in the homepage menu. The new `/portal` workspace provides customer service booking, rescheduling/cancellation requests, profile and password updates, support tickets, and notifications. Employees see assigned jobs; dispatch employees can assign technicians and update booking schedules; HR employees can provision or deactivate accounts; administrators can manage users, booking status, announcements, support tickets, and the service catalog with estimate prices. Technicians can record a site check-in (coordinates are optional) and submit a job report.

Operational tables are included in `database/sunson.sql` and are also created/upgraded when the portal is opened. Existing MySQL databases retain their current data. Configure the database in `application/config/database.php` before using the portal.

### System roadmap

See [docs/SYSTEM_ROADMAP.md](docs/SYSTEM_ROADMAP.md) for the consolidated customer, employee, and admin requirements, current system gaps, recommended implementation order, security notes, and design guidance for new pages.
