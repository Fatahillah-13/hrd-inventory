<?php

namespace App\Http\Controllers\AtkMaster;

use App\Exports\AtkRecapExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to   = $request->date('to')?->endOfDay() ?? now()->endOfMonth();

        $rows = $this->baseQuery($from, $to)->paginate(20)->withQueryString();

        return view('atk_master.recap', [
            'rows' => $rows,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }

    public function export(Request $request)
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to   = $request->date('to')?->endOfDay() ?? now()->endOfMonth();

        $rows = $this->baseQuery($from, $to)->get();

        $filename = 'rekap_atk_' . $from->format('Ymd') . '_' . $to->format('Ymd') . '.xlsx';
        return Excel::download(new AtkRecapExport($rows), $filename);
    }

    private function baseQuery($from, $to)
    {
        // Step 3: rekap berdasarkan order APPROVED
        return DB::table('atk_order_items as oi')
            ->join('atk_orders as o', 'o.id', '=', 'oi.atk_order_id')
            ->join('items as i', 'i.id', '=', 'oi.item_id')
            ->join('divisions as d', 'd.id', '=', 'o.division_id')
            ->where('o.status', 'approved')
            ->whereBetween('o.created_at', [$from, $to])
            ->selectRaw('i.name as item_name, d.name as division_name, SUM(oi.qty_requested) as total_qty')
            ->groupBy('i.name', 'd.name')
            ->orderBy('i.name');
    }
}
