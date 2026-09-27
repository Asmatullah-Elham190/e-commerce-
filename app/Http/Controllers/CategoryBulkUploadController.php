<?php

namespace App\Http\Controllers;

use App\Exports\CategoryBulkExport;
use App\Models\CategoryBulkUpload;
use App\Models\CategoriesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CategoryBulkUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:category_bulk_upload'])->only('index');
    }

    public function index()
    {
        return view('backend.product.category_bulk_upload.index');
    }

    public function download_demo()
    {
        return Excel::download(new CategoryBulkExport, 'category_bulk_demo.xlsx');
    }

    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    public function bulk_upload(Request $request)
    {
        if (!extension_loaded('zip')){
            flash(translate('Please enable the Zip extension'))->error();
            return back();
        }

        if ($request->hasFile('bulk_file')) {
            $import = new CategoryBulkUpload;
            Excel::import($import, $request->file('bulk_file'));

            flash(translate('Categories imported successfully'))->success();
        }

        return back();
    }
}
