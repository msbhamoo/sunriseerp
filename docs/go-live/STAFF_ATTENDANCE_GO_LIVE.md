# Staff Attendance: Go-Live Plan

**Release:** shifts redesign, change log, biometric fixes, monthly sheet, holidays & working days,
approved leave → attendance, printable register, correction requests, late-coming policy.

**Target:** production (sunriseschool.in) · **SQL:** [`staff_attendance_go_live.sql`](staff_attendance_go_live.sql) (sections referenced as §1–§9)

**When:** after school hours on a weekday (e.g. 5–7 pm), not on the 1st of a month or during payroll processing.
Allow **1 hour**, plus 30 minutes for checks.

**People:** one person deploying (files + SQL), one admin / HR person to test, Principal informed.

---

## 0. What changes for users

| Who | What they will notice |
|---|---|
| HR / Admin | Shifts with start/end dates replace "seasons"; ⋮ menu on Staff Attendance (Change Log, Holidays & Working Days, Correction Requests, Shift Timings); Monthly Sheet shows every status, Holiday Work, Late deduction; Print Register / Excel |
| All staff | **Mark My Attendance** page: "Request fix" on the last 7 days, "Request a correction" for any day this month, "My Correction Requests" list |
| Everyone | Biometric sync no longer overwrites manual fixes; approved leave shows as **Leave**; Sundays without punch are **Holiday**, not Absent |

---

## 1. Pre-checks (day before, ~15 min)

1. Run **§1** of the SQL file in phpMyAdmin on production. Record the answers:
   - [ ] Server version is MySQL ≥ 5.7.8 or MariaDB ≥ 10.2.3 (§1.1)
   - [ ] DB user has **TRIGGER** (or ALL) privilege (§1.2). If not, the Change Log will stay empty;
         ask the host (Hostinger) to grant it, or accept going live without the Change Log.
   - [ ] If `log_bin = ON`, `log_bin_trust_function_creators` should be `ON` (§1.3). Same impact as above.
   - [ ] No unexpected triggers on `staff_attendance` (§1.4)
   - [ ] Baseline counts written down (§1.5) and timing setup screenshot saved (§1.6)
2. [ ] PHP on the server is 7.4 or newer (Hostinger panel → PHP Configuration).
3. [ ] Pick the go-live window and tell HR not to edit attendance during it.
4. [ ] Note where the **biometric cron** is set up (Hostinger → Cron Jobs: URL containing
       `sync_biometric_cron` or `cron/biometric_sync`). It will be paused during the window.

## 2. Prepare the release (local, ~15 min)

1. Review and commit locally. **Do not include** these files; they are unrelated local edits:
   - `application/config/database.php` (local DB host; production config must not be overwritten)
   - `application/views/admin/ptm/_student_profile.php`
   - `application/views/student/studentShow.php`
2. Files to deploy (upload exactly these):

   **Modified**
   ```
   application/controllers/Schsettings.php
   application/controllers/admin/Staffattendance.php
   application/libraries/Biometric_lib.php
   application/models/Leaverequest_model.php
   application/models/StaffAttendaceSetting_model.php
   application/models/Staffattendancemodel.php
   application/views/admin/staffattendance/_monthly_sheet.php
   application/views/admin/staffattendance/scan.php
   application/views/admin/staffattendance/staffattendancelist.php
   application/views/setting/attendancetype.php
   ```
   **New**
   ```
   application/models/Staffattendanceaudit_model.php
   application/models/Staffattendancecorrection_model.php
   application/models/Staffattendancedbupdate_model.php
   application/models/Staffdayoverride_model.php
   application/models/Staffleaveattendance_model.php
   application/views/admin/staffattendance/auditlog.php
   application/views/admin/staffattendance/corrections.php
   application/views/admin/staffattendance/dbupdate.php
   application/views/admin/staffattendance/dayoverrides.php
   application/views/admin/staffattendance/register.php
   ```
   Note: `_monthly_sheet.php` and `staffattendancelist.php` also carry earlier uncommitted edits made
   before this release. Check them once in the diff before committing.
3. Zip the 20 files with their folder paths.

## 3. Backup (go-live window, ~10 min)

1. [ ] **Pause the biometric cron** (disable the cron job; note its exact command to restore later).
2. [ ] phpMyAdmin → Export → Quick → SQL → download the full database. Keep it until §9.
3. [ ] Hostinger File Manager: download the current versions of the 10 *modified* files (or zip `application/`).
4. [ ] *(Optional)* run **§2**. Not needed any more: the automatic database update in step 4 makes its own
       `bak_<date>_<time>_*` copies of the four attendance tables before it changes anything.

## 4. Deploy (~10 min)

1. [ ] Upload the 20 files to the same paths (overwrite). Check file sizes match the local ones.
2. [ ] Log in as **Super Admin** and open **Staff Attendance** (`/admin/staffattendance`).
       The first page load runs the **automatic database update** (takes a few seconds, runs only once):
       1. **Backup:** copies `staff_attendance`, `staff_attendence_schedules`, `staff_attendance_type` and
          `staff_leave_request` into `bak_<yymmdd>_<hhmm>_*` tables and checks the row counts. If the copy fails,
          it stops and **changes nothing**.
       2. **Additive changes only:** new columns, new tables, the **Leave** status, old seasonal timings turned into
          **shifts**, existing rows linked to their shift (new column only), change-log triggers.
       3. **Proof:** compares every existing row's original values with the backup. Any difference = **FAILED**.
       4. **Record:** saves the result and a full log, so it never runs twice.
3. [ ] Open ⋮ → Settings → **Database Update** (`/admin/staffattendance/dbupdate`, Super Admin only):
   - [ ] Status **DONE**
   - [ ] "Rows that differ from backup" = **0** for all four tables
   - [ ] Change-log triggers **Installed** (if not: TRIGGER permission, see §1.2/§1.3; everything else still works)
   - [ ] Read the log: shifts created, number of attendance rows linked
   - If the status is **FAILED**: read the log. If any rows differ, click **Restore from backup**, then
     **Run update now** after fixing the cause (or roll back, step 10). The update retries by itself at most 3 times.

> The SQL file's §3 does the same structure changes by hand. It is no longer needed; keep it only as a reference.

## 5. Verify (~15 min)

The Database Update page already proves existing data is unchanged. For a second, independent check run **§5** and tick:

- [ ] §5.1 six tables listed
- [ ] §5.2 shifts listed; exactly one regular shift in force today; temporary ones only for their dates
- [ ] §5.3 three `trg_sa_audit_*` triggers (if 0: privilege problem, see §1.2/§1.3; app still works)
- [ ] §5.4 `timing_rows_without_shift = 0`
- [ ] §5.5 and §5.6 return **no rows**
- [ ] §5.7 Leave status and policy row exist
- [ ] §5.8 attendance row count equals the baseline

Then, in the app:

- [ ] Settings → Attendance Type: shift list shows "General Timings" (or your season name) **In force now**;
      timings match the §1.6 screenshot.
- [ ] Staff Attendance → pick today → Search: statuses and times look the same as before the deploy.
- [ ] Change one staff member's status, save, then ⋮ → **Change Log**: the edit is listed with your name.
      (Then change it back.) Run §5.9 to see the same in the database.
- [ ] Monthly Sheet for last month → totals look sane; **Print Register** opens; **Excel** downloads.
- [ ] As a normal staff login, **Mark My Attendance** shows "Request fix" on recent days.

**Restart the biometric cron.** On the attendance page, click **Sync Biometric** for today and confirm punches arrive
(and that rows HR edited by hand did not change).

## 6. Configure (same day or next morning, ~20 min, HR + admin)

1. **Fix shift settings flagged by §6.6.** On the local copy, *Teacher* and *Driver* had "Early End Half" = Day End (14:45),
   which makes the half-day rule unusable for those roles. In Settings → Attendance Type → Edit the active shift:
   set **Early End Half** to the real half-day cut-off (e.g. 12:30) and keep **Early End Grace** for the
   "left early" warning.
2. **Late-coming policy:** decide the rule with the Principal (e.g. 2 free lates, then every 3 lates = half day),
   set it, and tick **Apply policy**. It changes no daily records, only the Late Ded. column.
3. **Holidays & Working Days** (⋮ menu): enter any upcoming working Sundays or role-specific holidays.
   For a past working Sunday (e.g. 4 Oct), add it so the monthly sheet stops treating it as a holiday.
4. **Permissions** (System Settings → Roles):
   - Correction approval and Holidays & Working Days need **Staff Attendance → Edit** (or Add).
   - Every staff member who should raise corrections needs a staff login (no extra permission).
5. **Biometric mapping:** check the staff listed by §6.7 (no punch in 30 days). For those who do use the machine,
   set Staff → Edit → Biometric ID to the code shown on the device.

## 7. Data clean-up (optional, within the first week)

Run **§6** for the current and previous month and decide:

| Check | What to do |
|---|---|
| §6.3 Sunday absents without punch | §7.1 turns them into Holiday (preview first; exclude real working Sundays) |
| §6.4 Approved leave still shown Absent | Open each leave in HR → Leave and save / approve again; attendance updates itself |
| §6.2 Repeated remarks, statuses from the old noon rule | Needs the PHP clean-up script used on the local copy (backup + dry run + apply). Ask for it to be packaged for production |
| §6.5 Present without punch | Confirm with HR; correct by hand if wrong (it will show in the Change Log) |

Always run §2-style backups (or confirm today's export) before any §7 write.

## 8. Tell staff (message for the staff WhatsApp group)

> From today, if you miss a punch or were on official duty, open **Mark My Attendance** in the school ERP,
> click **Request fix** next to the day (last 7 days) or **Request a correction** (any day this month),
> enter the correct time and reason, and send. The office will approve or reject it, and you can see the
> result on the same page. Corrections for previous months must go to the office directly.

## 9. Monitoring (first 2 weeks)

Daily for the first week, then weekly:

- [ ] ⋮ → Correction Requests: clear pending ones within 2 days.
- [ ] Change Log, filter source **cron/biometric**: any unexpected status changes?
- [ ] §6.1 counts for the month: Absent / Half Day not jumping unexpectedly.
- [ ] Hostinger error log / `application/logs/` for lines containing `Leave -> attendance` or `audit trigger`.

After 2–4 weeks stable: drop the `bak_<date>_*` tables listed on the Database Update page (and `bak_golive_*` if you ran §2).

## 10. Rollback (if something is badly wrong)

Decide within the go-live window if possible.

1. Pause the biometric cron.
2. Database Update page → **Restore from backup** (puts every original value back from the `bak_*` copy),
   then run **§8.1** to drop the triggers. (§8.2 does the same restore by hand from the §2 tables if you made them.)
3. Re-upload the 10 modified files from the step 3 backup and delete the 8 new files.
4. New tables and columns can stay. The old code ignores them.
5. Restart the cron. If anything is still wrong, restore the full phpMyAdmin export from step 3.

---

### Known limits at go-live

- **No logged-in test yet:** the new pages were checked with real data in previews and automated tests, but not
  clicked through while logged in. Step 5 is that test, so do not skip it.
- **Shift dates on re-sync:** re-syncing a past date that has no attendance row uses the shift that is active *today*.
  Existing rows keep the shift they were recorded under.
- **Comp-off and payroll:** Holiday Work and Late deduction are shown but not yet fed into comp-off balances or
  payroll (planned as the next step).
- **Logo:** the register logo appears only if the logo file exists on the server (`uploads/school_content/logo/`).
