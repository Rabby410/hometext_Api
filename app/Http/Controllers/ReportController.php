<?php

namespace App\Http\Controllers;

use App\Manager\ReportManager;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reportManager = new ReportManager();
        dd($reportManager->buying_stock_price);
//        $report =[
//            'total_product' => (new ReportManager())->getProducts(),
//        ];
        return response()->json($report);
    }
}
