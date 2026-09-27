<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Resources\V2\Seller\CategoryDiscountCollection;

class CategoryController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $categories = Category::with('sellerDiscount')
            ->orderBy('order_level', 'desc');

        if ($request->search != null) {
            $categories->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $categories->paginate(15);

        return new CategoryDiscountCollection($categories);
    }

    public function update(Request $request)
    {
        $dateRange = null;

        if ($request->discount_start_date != null && $request->discount_end_date != null) {
            $dateRange = $request->discount_start_date . ' to ' . $request->discount_end_date;
        }

        $data = [
            'category_id' => $request->category_id,
            'discount'    => $request->discount,
            'date_range'  => $dateRange,
        ];

        $response = $this->productService->setCategoryWiseDiscount($data);

        if ($response == 1) {
            return response()->json([
                "status"  => "success",
                "message" => "Category Wise Product Discount Set Successfully",
            ]);
        }

        return response()->json([
            "status"  => "error",
            "message" => "Something went wrong",
        ], 422);
    }
}