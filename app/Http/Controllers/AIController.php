<?php

namespace App\Http\Controllers;

use App\Models\AiPrompt;
use App\Models\aiUsageLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
class AIController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:analytics_tools_configuration'])->only('google_analytics');
    }

    public function ai_token_usage(Request $request)
    {
        $query = aiUsageLogs::with('user')->latest();
        if ($request->has('date') && !empty($request->date)) {
            $dates = explode(' to ', $request->date);
            
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                    
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                    
                } catch (\Exception $e) {
                    \Log::error('Date parsing error: ' . $e->getMessage());
                }
            }
        }
        $logs = $query->paginate(20);
        $totalRequests = $query->count();
        $totalTokens = $query->sum('total_tokens');
        $avgPerRequest = $totalRequests > 0 ? round($totalTokens / $totalRequests) : 0;
        $date = $request->date;

        return view('backend.reports.ai_token_usage', compact('logs','totalRequests','totalTokens','avgPerRequest','date'));
    }

    public function ai_configuration (Request $request)
    {
        return view('backend.setup_configurations.ai_configurations.ai_config');
    }

    public function ai_templates (Request $request)
    {
        $prompt_templates= AiPrompt::get();
        return view('backend.setup_configurations.ai_configurations.prompt_templates',compact('prompt_templates'));
    }

    public function update (Request $request, $id){
        $ai_prompt= AiPrompt::findOrFail(decrypt($id));
        
        $ai_prompt->prompt= $request->prompt;
        $ai_prompt->save();
        flash(translate("Prompt Updated Successfully"))->success();
        return back();

    }

    function add_edit_products(){
         return view('backend.setup_configurations.ai_configurations.add_edit');
    }


    
}
