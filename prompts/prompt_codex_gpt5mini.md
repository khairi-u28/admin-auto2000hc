# Codex Agent Prompt — GPT 5 Mini
> Paste this into OpenAI Codex with GPT 5 Mini selected.
> Purpose: Full architectural planning, multi-file refactoring, and feature implementation for auto2000-hc LMS project.

---

## PROJECT OVERVIEW

**auto2000-hc** is a Laravel 13.4 + Filament v3 admin panel serving as an LMS (Learning Management System) for Auto2000's HC (Human Capital) department. The platform manages:
- Karyawan (employees) hierarchically organized as: **Region → Area → Cabang → Karyawan**
- Training Curricula and Enrollments
- Batch training sessions
- Competency tracking and reporting

**Stack:** PHP 8.3 | Laravel 13.4 | Filament v3 | Livewire v3 | MySQL (strict `only_full_group_by`) | Blade

The project's organizational hierarchy reference (BA Nama Cabang file) is in your context. All data models must follow that hierarchy exactly.

---

## OPERATING PRINCIPLES

- **Read the codebase first.** Before writing any code, scan the existing `app/Filament/`, `app/Models/`, `database/migrations/`, and `routes/` directories to understand what already exists.
- **Preserve working code.** Never rewrite a working feature. Extend it.
- **MySQL strict compliance.** Every `GROUP BY` query must list all non-aggregated SELECT columns. No exceptions.
- **Test at each phase boundary.** Run `php artisan optimize:clear` and verify each affected URL before proceeding.
- **Filament v3 conventions.** Use `Resource`, `Page`, `Widget`, `RelationManager`, `Action`, `Infolist` patterns correctly.
- **Sequential migration.** Never alter existing migrations; create new ones for schema changes.

---

## PHASE 1 — CRITICAL BUG FIXES
*Priority: Immediate. Nothing else proceeds until these are resolved.*

### Bug 1 — `SQLSTATE[42000] only_full_group_by` on `/admin/nasional-page`

**Root cause:** The Filament table query on `NasionalPage` (or its related Resource) issues:
```sql
SELECT employees.region, COUNT(...), SUM(...)
FROM employees
LEFT JOIN enrollments ...
GROUP BY employees.region
ORDER BY employees.id ASC  -- ← ILLEGAL: employees.id not in GROUP BY
```

**Fix strategy:**
1. Locate the query builder call — search for `->orderBy('employees.id')` or `->orderBy('id')` in the NasionalPage context.
2. Replace with `->orderBy('employees.region', 'asc')` — the only safe sort key for a GROUP BY region query.
3. If Filament's default `defaultSort` is set to the primary key, override it explicitly on the table definition.

**Correct pattern:**
```php
protected function getTableQuery(): Builder
{
    return Employee::query()
        ->selectRaw(implode(', ', [
            'employees.region AS region',
            'COUNT(DISTINCT employees.area) AS jumlah_area',
            'COUNT(DISTINCT employees.branch_id) AS jumlah_cabang',
            'COUNT(employees.id) AS jumlah_karyawan',
            "SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END) AS enrollment_selesai",
        ]))
        ->leftJoin('enrollments', 'enrollments.employee_id', '=', 'employees.id')
        ->whereNotNull('employees.region')
        ->groupBy('employees.region')
        ->orderBy('employees.region');
}
```

---

### Bug 2 — `Route [filament.pages.nasional.region] not defined` on `/admin/dashboard`

**Root cause:** `resources/views/filament/widgets/region-heatmap-widget.blade.php` (line ~39) calls a route that was never registered.

**Fix strategy:**
1. Run `php artisan route:list --name=filament.admin` to list registered Filament routes.
2. Decide: does a Region detail page need to exist?
   - **If yes** (recommended — see Phase 2, Task 2.5): Create `RegionResource` with a View page, then update the route reference to `RegionResource::getUrl('view', ['record' => $regionId])`.
   - **If no** (temporary only): Replace with `route('filament.admin.pages.nasional-page')."?region={$region}"` and handle filtering via a URL query param on NasionalPage.
3. Update the blade template accordingly.

---

### Bug 3 — Proactive scan (run after Bugs 1 & 2 are fixed)

Execute this diagnostic sequence:
```bash
php artisan route:list 2>&1 | grep -i "error\|exception"
php artisan view:cache 2>&1
php artisan config:cache 2>&1
```

Then visit and confirm these pages render without exceptions:
- `/admin/dashboard`
- `/admin/nasional-page`
- `/admin/branches`
- `/admin/employees`
- `/admin/curricula`
- `/admin/enrollments`

Document any additional errors found and fix them before Phase 2.

---

## PHASE 2 — ARCHITECTURE: DATA MODEL VALIDATION

Before building new pages, validate and extend the schema to support all required features.

### 2.1 — Required model relationships

Confirm or create these Eloquent relationships:

```
Region → hasMany Area
Area → belongsTo Region, hasMany Branch
Branch → belongsTo Area, belongsTo Region, hasMany Employee
Employee → belongsTo Branch, belongsTo Area (via branch), belongsTo Region (via branch)
Enrollment → belongsTo Employee, belongsTo Curriculum
TrainingRecord → belongsTo Employee
Batch → belongsMany Employee (as peserta), belongsMany Employee (as trainers), belongsTo Curriculum, belongsTo Employee (as pic)
BatchReview → belongsTo Batch, morphs to reviewer target (batch, pic, trainer, curriculum)
```

### 2.2 — Migration checklist

Verify these columns exist; create migrations if missing:

**regions table:** `id`, `nama_region`, `nama_rbh` (Region Business Head), `timestamps`

**areas table:** `id`, `region_id` (FK), `nama_area`, `nama_abh` (Area Business Head), `timestamps`

**branches table:** `id`, `area_id` (FK), `region_id` (FK), `kode_cabang`, `nama`, `type` (enum: HO,VSP,SP,CAO,VP,DP,V,GSO,Fleet,PDC), `timestamps`

**employees table:** `id`, `branch_id` (FK), `nrp`, `nama_lengkap`, `position_name`, `pos`, `masa_bakti`, `status` (enum: aktif, non_aktif, pensiun), `timestamps`
- Derived fields `area` and `region` should be accessed via `$employee->branch->area->nama_area` — **remove any denormalized region/area columns** to prevent the `only_full_group_by` problem.

**batches table:** `id`, `kode_batch`, `nama_batch`, `curriculum_id` (FK), `pic_employee_id` (FK), `status` (enum: aktif, non_aktif, selesai, dibatalkan), `active_from`, `active_until`, `timestamps`

**batch_reviews table:** `id`, `batch_id` (FK), `reviewable_type`, `reviewable_id`, `rating` (tinyint 1-5), `catatan` (text), `timestamps`

**batch_employee (pivot):** `batch_id`, `employee_id`, `role` (enum: peserta, trainer)

---

## PHASE 3 — SIDEBAR & NAVIGATION

In `app/Providers/Filament/AdminPanelProvider.php`, register navigation groups in this order:

```php
->navigationGroups([
    NavigationGroup::make('Master Data')->icon('heroicon-o-database'),
    NavigationGroup::make('Kurikulum & Materi')->icon('heroicon-o-book-open'),
    NavigationGroup::make('Enrollment & Operasional')->icon('heroicon-o-academic-cap'),
    NavigationGroup::make('Laporan')->icon('heroicon-o-chart-bar'),
])
```

Navigation assignments:
- **Master Data:** RegionResource, AreaResource, BranchResource, EmployeeResource
- **Kurikulum & Materi:** CurriculumResource (existing)
- **Enrollment & Operasional:** BatchResource (renamed from SesiResource), EnrollmentResource (if exists)
- **Laporan:** NasionalPage (standalone page)

---

## PHASE 4 — MASTER DATA PAGES

### 4.1 — RegionResource

**File:** `app/Filament/Resources/RegionResource.php`

**Table columns:**
| Column | Source | Type |
|---|---|---|
| Nama Region | `nama_region` | TextColumn |
| Nama RBH | `nama_rbh` | TextColumn |
| Jml. Area | `areas_count` | TextColumn (withCount) |

**Actions:** Each row → ViewRegion page. Add EditAction and DeleteAction.

**ViewRegion page:**
- Infolist header: nama_region, nama_rbh
- Stats: jumlah area, jumlah cabang (sum), jumlah karyawan (sum)
- AreasRelationManager: paginated table of areas in this region (columns: nama_area, nama_abh, branches_count)

---

### 4.2 — AreaResource

**File:** `app/Filament/Resources/AreaResource.php`

**Table columns:**
| Column | Source |
|---|---|
| Nama Area | `nama_area` |
| Region | `region.nama_region` |
| Nama ABH | `nama_abh` |
| Jml. Cabang | `branches_count` |

**ViewArea page:**
- Infolist: nama_area, region, nama_abh
- BranchesRelationManager: branches in this area (columns: kode_cabang, nama, type, employees_count)

---

### 4.3 — BranchResource (Cabang)

**Table columns:**
| Column | Source |
|---|---|
| Kode Cabang | `kode_cabang` |
| Nama Cabang | `nama` |
| Region | `region.nama_region` |
| Area | `area.nama_area` |
| Tipe | `type` (Badge) |
| Jml. Karyawan | `employees_count` |

**Remove:** ImportAction
**Replace with:** CreateAction labeled "Tambah Cabang"

**ViewBranch page:**
- Infolist: kode_cabang, nama, region, area, tipe
- "Kepala Cabang" widget: filter employees by position containing 'Kepala Cabang'
- EmployeesRelationManager: list of karyawan in this branch
- LMS stats section:
  - Total Karyawan
  - Completion Rate (completed enrollments / total enrollments for this branch's employees)
  - Enrollment breakdown: not_started, in_progress, completed, overdue
- EditAction CTA (top right of page)

**Reference design:** https://auto2000-case-study.vercel.app/ — mirror the layout for the one-sheet-profile approach.

---

### 4.4 — EmployeeResource (Karyawan)

**Table columns:**
| Column | Source |
|---|---|
| NRP | `nrp` |
| Nama Lengkap | `nama_lengkap` |
| Position Name | `position_name` |
| POS | `pos` |
| Kode Cabang | `branch.kode_cabang` |
| Cabang | `branch.nama` |
| Area | `branch.area.nama_area` |
| Region | `branch.area.region.nama_region` |
| Masa Bakti | `masa_bakti` |
| Status | `status` (Badge: aktif=success, non_aktif=warning, pensiun=danger) |

**Header actions:**
1. **ImportAction** — bulk CSV import. Include validation for all required fields.
2. **ExportAction** — download CSV template with column headers pre-filled.

**Hierarchical form (Create/Edit):**
```php
Select::make('region_id')
    ->label('Region')
    ->options(Region::all()->pluck('nama_region', 'id'))
    ->reactive()
    ->afterStateUpdated(fn (Set $set) => $set('area_id', null)),

Select::make('area_id')
    ->label('Area')
    ->options(fn (Get $get) => Area::where('region_id', $get('region_id'))->pluck('nama_area', 'id'))
    ->reactive()
    ->afterStateUpdated(fn (Set $set) => $set('branch_id', null)),

Select::make('branch_id')
    ->label('Cabang')
    ->options(fn (Get $get) => Branch::where('area_id', $get('area_id'))->pluck('nama', 'id'))
    ->required(),
```

**ViewEmployee page:** Reuse/adapt the existing `one-sheet-profile-page` pattern — show employee info, competency summary, and enrollment history.

---

## PHASE 5 — NASIONAL PAGE

Fill `NasionalPage` with these sections:

**Stats row (top):**
- Total Karyawan Aktif Nasional
- Total Cabang
- Total Enrollment
- Completion Rate % (national)
- Total Training Records

**Charts:**
- Completion Rate by Region (horizontal bar)
- Enrollment Status Distribution (donut: not_started / in_progress / completed / overdue)
- Monthly completion trend (line chart, last 6 months)

**Regional summary table** (the fixed GROUP BY query from Bug 1):
Columns: Region | Jml. Area | Jml. Cabang | Jml. Karyawan | Enrollment Selesai | Completion Rate

---

## PHASE 6 — BATCH (Renamed from Sesi)

### 6.1 — BatchResource

**Navigation:** Place under "Enrollment & Operasional" group. Display label: **"Batch"**.

**Table columns:**
| Column | Source |
|---|---|
| Kode Batch | `kode_batch` |
| Nama Batch | `nama_batch` |
| PIC | `pic.nama_lengkap` |
| Status | `status` (Badge) |
| Jml. Peserta | `peserta_count` (withCount on peserta pivot) |
| Completion Rate | computed: completed peserta / total peserta |

### 6.2 — Batch Create wizard (multi-step form)

```
Step 1: Pilih Kurikulum
  → Select curriculum (template or editable copy)
  → Option: "Gunakan template" or "Salin & edit"

Step 2: Informasi Batch
  → kode_batch (auto-generated or manual)
  → nama_batch
  → active_from, active_until (date range)
  → status (default: non_aktif)

Step 3: Assign PIC & Trainer
  → pic_employee_id: Select from employees (single)
  → trainers: Select from employees (multi)

Step 4: Assign Peserta
  → Multi-select employees
  → Show count of selected peserta
  → Confirm
```

### 6.3 — Batch detail page tabs

**Tab 1 — Info Batch:**
- Infolist: kode_batch, nama_batch, pic, periode, status
- Quick stats: jumlah peserta, completion rate

**Tab 2 — Daftar Peserta:**
- Table columns: NRP | Nama | Jabatan | Cabang | Modul Completion Rate (progress bar)
- Filter by completion status

**Tab 3 — Progress Learning:**
- Per-module table: Nama Modul | Tipe | Due Date | Status breakdown across all peserta
- Expandable row per modul showing per-peserta status

### 6.4 — Batch Review/Evaluation

After batch is created (any status), a Review section appears at the bottom of the detail page:

**Review targets:**
1. Batch overall (rating + catatan)
2. PIC Training (rating + catatan)
3. Each Trainer (rating + catatan, per trainer)
4. Kurikulum (rating + catatan)

**Schema:** `batch_reviews` table using polymorphic `reviewable_type` / `reviewable_id`.

### 6.5 — Batch Report CTA

A button labeled **"Lihat Laporan Batch"** on the Batch detail page, accessible regardless of batch status.

The report page shows:
- Batch summary (kode, nama, periode, status, pic, trainers)
- Aggregate stats: total peserta, completion rate, average rating
- Modul completion table
- Reviewers' ratings summary
- Export to PDF button (use `barryvdh/laravel-dompdf` or similar)

---

## PHASE 7 — TESTING & VERIFICATION

Run this verification sequence after all phases:

```bash
# Clear all caches
php artisan optimize:clear

# Check for route issues
php artisan route:list | grep -E "nasional|region|area|branch|employee|batch"

# Check for model issues
php artisan tinker --execute="
  echo 'Regions: ' . App\Models\Region::count() . PHP_EOL;
  echo 'Areas: ' . App\Models\Area::count() . PHP_EOL;
  echo 'Branches: ' . App\Models\Branch::count() . PHP_EOL;
  echo 'Employees: ' . App\Models\Employee::count() . PHP_EOL;
"
```

**Manual verification checklist:**
- [ ] `/admin/dashboard` — no errors, all widgets load
- [ ] `/admin/nasional-page` — table loads, no GROUP BY error
- [ ] `/admin/regions` — list and view pages work
- [ ] `/admin/areas` — list and view pages work, region relation loads
- [ ] `/admin/branches` — list, view, create work; no CSV import button
- [ ] `/admin/employees` — list, create (hierarchical selects), CSV import/template work
- [ ] `/admin/batches` — list, multi-step create, detail tabs, review, report CTA work
- [ ] Sidebar shows all 4 navigation groups with correct resources
- [ ] Each Master Data list row click goes to correct detail page

---

## CONSTRAINTS & NOTES

- **Do not** remove or restructure existing working migrations.
- **Do not** change the Filament panel ID (`admin`) — all routes depend on it.
- **Do not** hardcode region/area/branch names — always read from the database.
- All new Filament Resources must be registered in `AdminPanelProvider`.
- Use Filament's built-in `TextColumn::make()->badge()` for status columns.
- Batch `completion_rate` is a computed accessor, not a DB column.
- The BA Nama Cabang file in your project context is the source of truth for the Region → Area → Cabang hierarchy. Seed from it if the tables are empty.

---

*This prompt is optimized for GPT 5 Mini on Codex — it is designed for multi-file reasoning and architectural decision-making. Work phase-by-phase and always verify before advancing.*
