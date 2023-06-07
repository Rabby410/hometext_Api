<?php
namespace App\Manager;

use App\Models\Product;
use Illuminate\Support\Collection;

class ReportManager
{

    public int $total_product = 0;

    public const LOW_STOCK_ALERT = 5;
    public int $total_stock = 0;
    public int $low_stock = 0;
    public int $buying_stock_price = 0;
    public int $saleing_stock_price = 0;
    private Collection $products;

    function  __construct()
    {
        $this->getProducts();
        $this->setTotalProduct();
        $this->calculateStock();
        $this->findLowStock();
        $this->calculateBuyingStockPrice();
        $this->calculateSaleingStockPrice();
}

    private function getProducts()
    {
     $this->products = (new Product)->getAllProduct();

}
    private function setTotalProduct()
    {
        $this->total_product = count($this->products);
    }
    private function calculateStock()
    {
        $this->total_stock = $this->products->sum('stock');
    }
    private function findLowStock()
    {
        $lowStockProducts = $this->products->where('stock', '<=', self::LOW_STOCK_ALERT);
        $this->low_stock = $lowStockProducts->count();
    }
    private function calculateBuyingStockPrice()
    {
        foreach ($this->products as $product){
            $this->buying_stock_price+= ($product->cost * $product->stock);
        }
    }
    private function calculateSaleingStockPrice()
    {
        foreach ($this->products as $product){
            $this->saleing_stock_price+= ($product->price * $product->stock);
        }
    }
}

