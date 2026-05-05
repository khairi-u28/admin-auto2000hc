<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Batch;
use App\Models\BatchParticipant;
use Illuminate\Support\Facades\DB;

$maxYear = Batch::max(DB::raw('YEAR(end_date)'));
echo "Max Year: $maxYear\n";

$count2026 = Batch::whereYear('end_date', 2026)->count();
echo "Batches in 2026: $count2026\n";

$selesai2026 = Batch::whereYear('end_date', 2026)->where('status', 'selesai')->count();
echo "Selesai in 2026: $selesai2026\n";

$totalPeserta = BatchParticipant::whereIn('batch_id', Batch::whereYear('end_date', 2026)->pluck('id'))->count();
echo "Total Peserta in 2026: $totalPeserta\n";
