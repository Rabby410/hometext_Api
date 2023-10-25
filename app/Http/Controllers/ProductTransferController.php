<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferProduct;

class ProductTransferController extends Controller
{
    /**
     * Create a new product transfer.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'from_shop_id' => 'required|integer',
            'to_shop_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        // Create a new product transfer
        $transfer = TransferProduct::create($validatedData);

        // You can implement additional logic here, such as updating the shop_product table
        
        return response()->json(['message' => 'Product transfer created successfully', 'data' => $transfer], 201);
    }

    /**
     * List all product transfers.
     */
    public function index()
    {
        $transfers = TransferProduct::all();

        return response()->json(['data' => $transfers]);
    }

    /**
     * Show the details of a specific product transfer.
     */
    public function show(TransferProduct $transfer)
    {
        return response()->json(['data' => $transfer]);
    }

    /**
     * Approve a product transfer.
     */
    public function approve(TransferProduct $transfer)
    {
        $transfer->update(['status' => 'approved']);

        return response()->json(['message' => 'Product transfer approved successfully']);
    }

    /**
     * Reject a product transfer.
     */
    public function reject(TransferProduct $transfer)
    {
        $transfer->update(['status' => 'rejected']);

        return response()->json(['message' => 'Product transfer rejected']);
    }
}
