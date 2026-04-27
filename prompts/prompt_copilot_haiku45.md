# GitHub Copilot Agent Prompt — Claude Haiku 4.5
> Paste this into your GitHub Copilot Chat (VSCode) with Haiku 4.5 selected as the agent.
> Purpose: Error fixing + incremental feature implementation for auto2000-hc Laravel/Filament LMS project.

---

## CONTEXT

You are an expert Laravel 13 + Filament 3 + Livewire developer working on **auto2000-hc**, an HC (Human Capital) department LMS platform for Auto2000. The stack is:
- PHP 8.3 / Laravel 13.4
- Filament v3 (admin panel)
- Livewire v3
- MySQL (strict mode: `only_full_group_by` is ON)
- Blade templating

The project resource files (BA Nama Cabang, schema, etc.) are already in your project context. Always reference them before creating new data structures.

---

## WORKING RULES

1. **One task at a time.** Complete and verify each task before moving to the next.
2. **Run `php artisan route:list` and `php artisan cache:clear` after every Filament page/route change.**
3. **Test every fix** by checking the affected URL in the browser before proceeding.
4. **Never break existing migrations.** Create new migrations for schema changes.
5. **Follow Filament v3 conventions** for Resources, Pages, Widgets, and Table columns.
6. **MySQL strict mode is active.** Every `GROUP BY` query must include all non-aggregated SELECT columns.

---

## PHASE 1 — FIX CRITICAL ERRORS (Do this first, in order)

### Task 1.1 — Fix `only_full_group_by` SQL error on `/admin/nasional-page`

**Error:**
```
SQLSTATE[42000]: 1055 Expression #1 of ORDER BY clause is not in GROUP BY clause
SQL: ... GROUP BY employees.region ORDER BY employees.id ASC
```

**Location to find:** Search for the Filament Table query in `NasionalPage` — likely in:
- `app/Filament/Pages/NasionalPage.php`
- Or a Resource file referenced by it

**Fix:** Remove the `->orderBy('employees.id')` call, or replace with `->orderByRaw('MIN(employees.id) ASC')`. The `ORDER BY` column must either be in `GROUP BY` or be an aggregate function.

**Correct query pattern:**
```php
Employee::query()
    ->selectRaw('employees.region, COUNT(DISTINCT employees.area) as jumlah_area, COUNT(DISTINCT employees.branch_id) as jumlah_cabang, COUNT(employees.id) as jumlah_karyawan, SUM(CASE WHEN enrollments.status = \'completed\' THEN 1 ELSE 0 END) as enrollment_selesai')
    ->leftJoin('enrollments', 'enrollments.employee_id', '=', 'employees.id')
    ->whereNotNull('employees.region')
    ->groupBy('employees.region')
    ->orderBy('employees.region', 'asc'); // ORDER BY the same column in GROUP BY
```

After fixing, run: `php artisan cache:clear` and test `/admin/nasional-page`.

---

### Task 1.2 — Fix `Route [filament.pages.nasional.region] not defined`

**Error:**
```
Route [filament.pages.nasional.region] not defined.
Location: resources/views/filament/widgets/region-heatmap-widget.blade.php:39
```

**Steps:**
1. Open `resources/views/filament/widgets/region-heatmap-widget.blade.php`
2. Find line ~39 where `route('filament.pages.nasional.region', ...)` is called
3. Run `php artisan route:list --name=filament` to see actual registered route names
4. Either:
   - **Option A:** Replace the route name with the correct registered name (e.g., `filament.admin.pages.nasional-page`)
   - **Option B:** If a region-detail page is needed, create it first (see Phase 2), then update this reference

Temporary safe fix while Phase 2 is pending:
```blade
{{-- Replace the broken route() call with a safe fallback --}}
href="{{ route('filament.admin.pages.nasional-page') }}?region={{ urlencode($region) }}"
```

After fixing, test `/admin/dashboard`.

---

### Task 1.3 — Full project error scan

After fixing 1.1 and 1.2, run the following to catch any remaining issues:

```bash
php artisan route:list
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

Then manually visit these URLs and note any errors:
- `/admin/dashboard`
- `/admin/nasional-page`
- `/admin/branches`
- `/admin/employees`
- Any other pages in the sidebar

Fix each error found using the same approach: identify the file, understand the root cause, apply the minimal correct fix.

---

## PHASE 2 — SIDEBAR & PAGE RESTRUCTURING

> Only start Phase 2 after Phase 1 is fully verified and error-free.

### Task 2.1 — Dashboard widgets

In `app/Filament/Pages/Dashboard.php` (or the dashboard widget files), ensure the following widgets are registered and working:

**Stat widgets (top row):**
- Total Karyawan Aktif
- Total Cabang
- Enrollment Selesai (completed)
- Completion Rate % (completed / total enrollments)
- Total Training Records

**Chart widgets:**
- Completion by Department (bar chart — departments: Sales, Aftersales, PD, HC, GS)
- Completion by Region (bar chart — per region)
- Top Cabang this month (single stat from existing query)
- Level distribution (pie/donut chart from `training_records.level_achieved`)

**Table widget:**
- Regional heatmap table (from NasionalPage query — reuse it here as a widget)

For each widget, create a separate class in `app/Filament/Widgets/`. Use `Filament\Widgets\StatsOverviewWidget` for stats and `Filament\Widgets\ChartWidget` for charts.

---

### Task 2.2 — Master Data navigation group

In your Filament panel provider (`app/Providers/Filament/AdminPanelProvider.php`), register a navigation group called **"Master Data"** containing these resources in this order:
1. RegionResource
2. AreaResource
3. BranchResource (rename display label to "Cabang")
4. EmployeeResource (rename display label to "Karyawan")

---

### Task 2.3 — BranchResource (Cabang) updates

**Table columns** (in this order):
`kode_cabang` | `nama` (Nama Cabang) | `region` | `area` | `type` (Tipe: HO, VSP, SP, CAO, VP, DP, V, GSO, Fleet, PDC) | `employees_count` (Jml. Karyawan — use `withCount('employees')`)

**Remove:** ImportAction (CSV import button)
**Add:** CreateAction with label "Tambah Cabang"

**Detail/View page (ViewBranch):**
Create a custom View page for each branch that contains:
- Branch info header (kode, nama, region, area, tipe)
- `Kepala Cabang` widget (filter employees where position like 'Kepala Cabang' or designated head)
- Infolist with branch authorities
- RelationManager: EmployeesRelationManager (list of karyawan in this branch)
- Stats: total karyawan, completion rate, enrollment breakdown
- EditAction CTA

Make each table row clickable to the View page:
```php
->recordUrl(fn (Branch $record): string => BranchResource::getUrl('view', ['record' => $record]))
```

---

### Task 2.4 — AreaResource

Create `app/Filament/Resources/AreaResource.php` with:

**Table columns:** `nama_area` | `region` | `nama_abh` (Nama ABH - Area Business Head) | `branches_count` (Jml. Cabang)

**View/Detail page:** Contains a table of branches under this area (BranchesRelationManager).

Rows must be clickable to the Area detail page.

---

### Task 2.5 — RegionResource

Create `app/Filament/Resources/RegionResource.php` with:

**Table columns:** `nama_region` | `nama_rbh` (Nama RBH - Region Business Head) | `areas_count` (Jml. Area)

**View/Detail page:** Contains a table of areas under this region (AreasRelationManager).

Rows must be clickable to the Region detail page.

---

### Task 2.6 — EmployeeResource (Karyawan) updates

**Table columns:**
`nrp` | `nama_lengkap` | `position_name` | `pos` | `kode_cabang` | `branch.nama` (Cabang) | `area` | `region` | `masa_bakti` | `status` (badge: Aktif=success, Non-Aktif=warning, Pensiun=danger)

**Bulk import:** Add `ImportAction` with CSV support. Add a `ExportAction` for CSV template download.

**Hierarchical input on Create/Edit form:**
- Select Region first → then Area filters by selected region → then Cabang filters by selected area
- Use `Select::make()->reactive()->afterStateUpdated()` to chain the selects

**View page:** Show one-sheet-profile style (similar to existing `/admin/one-sheet-profile-page`) with employee info + competency + enrollment summary.

---

### Task 2.7 — Nasional page

Fill the Nasional page with:
- Regional summary table (already built — fix from Phase 1)
- Stat widgets for national totals
- Completion rate by region (chart)
- Top performing region/area this month

---

### Task 2.8 — Rename "Sesis" to "Batch" and restructure

1. Rename the Sesi resource/page display label to **"Batch"**
2. Move it under a new navigation group: **"Enrollment & Operasional"**

**Batch table columns:**
`kode_batch` | `nama_batch` | `pic` (PIC Training) | `status` (Aktif/Non-aktif/Selesai/Dibatalkan) | `jumlah_peserta` (count) | `completion_rate` (%)

**Batch Create flow:**
1. Choose Kurikulum template
2. Assign PIC Training (from employees)
3. Assign trainers (from employees, multi-select)
4. Assign peserta/students (from employees, multi-select)
5. Set active period (date range)
6. Set status

**Batch Detail page tabs:**
- **Info Batch** — summary (kode, nama, pic, periode, status)
- **Daftar Peserta** — table of enrolled employees with basic info + modul completion rate
- **Progress Learning** — list of kurikulum modules with per-student enrollment progress

**Batch Review/Evaluation:**
After batch runs: a Review section with ratings (1–5 stars) and text for: the batch, PIC, trainers, and kurikulum.

**Batch Report CTA:**
A button on the detail page that generates a printable/exportable summary of the batch regardless of status.

---

## COMPLETION CHECKLIST

After all phases:
- [ ] `/admin/dashboard` loads with no errors and shows all widgets
- [ ] `/admin/nasional-page` loads with correct GROUP BY query
- [ ] Master Data group visible in sidebar with Region, Area, Cabang, Karyawan
- [ ] Each Master Data list row is clickable to a detail/view page
- [ ] Cabang page has "Tambah Cabang" (no CSV import)
- [ ] Karyawan page has CSV bulk import + template download
- [ ] Enrollment & Operasional group contains Batch (renamed from Sesis)
- [ ] Batch detail page has 3 tabs + review section + report CTA
- [ ] `php artisan route:list` shows no broken references
- [ ] No console errors or Blade exceptions on any page

---

*This prompt is optimized for Haiku 4.5 — work through tasks sequentially, one file at a time.*
