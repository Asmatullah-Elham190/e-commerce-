<?php
namespace App\Http\Controllers\Api\V2;
use App\Http\Resources\V2\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;


class PageController extends Controller
{
    public function get_page_data(Request $request)
    {
        $page_name = $request->page;
        $page = Page::where('slug', $page_name)->first();
        return new PageResource($page);
    }
}
