# Live Deployment & Seeding Plan

Now that we have successfully tested the new Payroll Rule Engine on your local server, here is the exact step-by-step plan to deploy these changes to your live server safely.

---

## Step 1: Upload the Modified Code Files

You need to upload the specific files that were created or modified during this session via FTP/cPanel to your live server. Make sure to place them in the exact same directory structures.

**New/Modified Files to Upload:**
*   `application/controllers/admin/Payrollrules.php` (The main UI controller)
*   `application/models/Payrollrule_model.php` (The database query logic)
*   `application/libraries/Payrollengine.php` (The brain of the mathematical rules)
*   `application/language/English/app_files/system_lang.php` (Added the language key for `payroll_run_logs`)
*   `application/views/admin/payrollrules/*` (Upload the entire `payrollrules` folder inside views/admin which contains the dashboard, traces, and run logs UI).

---

## Step 2: Set Up the Database Tables on Live

The live server's database needs the new tables created for the rules, traces, and simulations.

1. Connect to your Live Database via **phpMyAdmin** (or however you manage your live MySQL).
2. If you have the SQL migration script from earlier in this project that created the `pre_rules`, `pre_calculation_traces`, etc. tables, execute that SQL script on your live database to create the empty tables.

---

## Step 3: Add the UI Menus

To make the "Payroll Rules" and "Payroll Run Logs" show up on the live server's sidebar menu, run this quick SQL command in phpMyAdmin on your live database:

```sql
INSERT INTO sidebar_sub_menus (sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, activate_controller, activate_methods, is_active, created_at, updated_at) 
VALUES 
(15, 'Payroll Rules', 'payroll_rules', 'payroll_rules', 'admin/payrollrules', 2, '(\'payroll_rules\',\'can_view\')', 'payrollrules', 'index,rules,addrule,simulate,runsimulation,versions', 1, NOW(), NOW()),
(15, 'Payroll Run Logs', 'payroll_run_logs', 'payroll_run_logs', 'admin/payrollrules/runlogs', 2, '(\'payroll_rules\',\'can_view\')', 'payrollrules', 'runlogs,runtrace', 1, NOW(), NOW());
```
*(Assuming `15` is the correct ID for your Human Resources parent menu).*

---

## Step 4: Seed the Default Data (The Rules!)

Instead of running PHP scripts on your live server to seed the data, I have created a clean **SQL Dump File** directly from your local server. This file contains the exact Teaching, NTS, and Driver rules we just configured.

1. Download the generated SQL file here: [payroll_rules_seed.sql](file:///C:/Users/mahendra.singh/.gemini/antigravity-ide/brain/1f2c6a48-0fd2-4bc8-a160-ecfbd6c327fe/payroll_rules_seed.sql)
2. Open phpMyAdmin on your Live Server.
3. Select your live ERP database (`smserp`).
4. Click on the **Import** tab at the top.
5. Upload the `payroll_rules_seed.sql` file and click **Go**.

> [!TIP]
> This will instantly populate all the default rules exactly as we just set them up locally, saving you from doing it all over again manually!

---

## Step 5: Test on Live
Once done, simply login to your live ERP, navigate to **Human Resource -> Payroll Rules**, and verify that the rules appear in the list. Run a quick "Simulation" to ensure the `from_date` holiday SQL fix and everything else is functioning smoothly on your live hosting environment!
