<?php

namespace App\Http\Controllers;

use App\Manager\PriceManager;
use App\Manager\ReportManager;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reportManager = new ReportManager(auth());
//        dd($reportManager->possible_profit);
        $report =[
            'total_product' => $reportManager->total_product,
            'total_stock' => $reportManager->total_stock,
            'low_stock' => $reportManager->low_stock,
            'buy_value' => PriceManager::priceFormat($reportManager->buying_stock_price),
            'sale_value' => PriceManager::priceFormat($reportManager->saleing_stock_price),
            'possible_profit' => PriceManager::priceFormat($reportManager->possible_profit),
            'total_sales' => PriceManager::priceFormat($reportManager->total_sale),
            'total_sales_today' => PriceManager::priceFormat($reportManager->total_sale_today),
            'total_purchase' => PriceManager::priceFormat($reportManager->total_purchase),
            'total_purchase_today' => PriceManager::priceFormat($reportManager->total_purchase_today),
        ];
        return response()->json($report);
    }
}
