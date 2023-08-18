<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class CsvController extends Controller
{
    public function saveCsv(Request $request)
    {
        $selectedProductIds = json_decode($request->input('selectedProductIds'), true);

        if (!empty($selectedProductIds)) {
            // Fetch product details based on the selected product IDs
            $products = Product::whereIn('id', $selectedProductIds)->get();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found with the provided IDs.'], 404);
            }

            // Prepare CSV data with the header
            $csvData = "id,title,description,availability,condition,price,link,image_link,brand\n";

            foreach ($products as $product) {
                // Format description
                $description = $this->formatDescription($product->description);

                // Get brand name
                $brand = $this->getBrandName($product->brand_id);

                // Get image link
                $imageLink = $this->getImageLink($product->id);

                $csvData .= "{$product->id},";
                $csvData .= "{$product->name},";
                $csvData .= "\"{$description}\",";
                $csvData .= "\"in stock\","; 
                // Add availability field with value "in stock"
                 $csvData .= "\"new\",";       
                // Add condition field with value "new"


                // Check if sell_price relationship exists before accessing price property
                $price = number_format($product->price, 2);
               $formattedPrice = str_replace(',', '', $price) . " BDT";
               // Remove commas from the formatted price
                $csvData .= "{$formattedPrice},";

                $csvData .= "https://hometexbd.ltd/Shop/{$product->id},";
                $csvData .= "{$imageLink},";
                $csvData .= "{$brand}\n";
            }

            // Generate a fixed file name
            $fileName = 'catalog_products.csv';

            // Save the CSV data to the desired directory using absolute file path
            $filePath = public_path('csv_facebook_post/' . $fileName);
            file_put_contents($filePath, $csvData);

            return response()->json(['message' => 'CSV file created and saved successfully.', 'file' => $fileName]);
        }

        return response()->json(['message' => 'No product IDs received.'], 400);
    }

    protected function formatDescription($description)
    {
        // Format description by removing extra spaces and line breaks
        $description = preg_replace("/\s+/", " ", $description);
        return str_replace('"', '""', $description); // Escape double quotes
    }

    protected function getImageLink($productId)
    {
        // Fetch the primary photo data from the product_photos table based on the provided product ID
        $productPhoto = DB::table('product_photos')->where('product_id', $productId)->first();

        if ($productPhoto) {
            $imageLink = "https://htbapi.hometexbd.ltd/images/uploads/product/" . $productPhoto->photo;
            return $imageLink;
        }

        return '';
    }


    protected function getBrandName($brandId)
    {
        // Fetch brand name from the Brand model based on the provided brand ID
        $brand = Brand::find($brandId);
        return $brand ? $brand->name : '';
    }
}
