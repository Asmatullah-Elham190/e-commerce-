<?php

namespace App\Services;

use App\Models\AiPrompt;
use App\Models\aiUsageLogs;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AiService
{
    public function productGenerateWithAI(array $data)
    {
        $user = Auth::user();

        if ($user && $user->user_type != 'admin') {

            if ($user->seller_monthly_token_limit <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your monthly AI generation limit has been reached.'
                ]);
            }

        }

        //Log::info('asf',$data);
       try {

            $productName  = trim($data['product_name'] ?? '');
            $section      = $data['section'] ?? null;
            $language     = $data['lang'] ?? default_language();
            $existingData = $data['existing_data'] ?? [];

            if(!$productName){
                return response()->json([
                    'success'=>false,
                    'message'=>'Product name is required'
                ]);
            }

            if(!$section){
                return response()->json([
                    'success'=>false,
                    'message'=>'Section is required'
                ]);
            }

            // language name
            $languageName = Language::where('code',$language)->value('name') ?? 'English';


            /*
            |--------------------------------------------------------------------------
            | Field Config
            |--------------------------------------------------------------------------
            */

            $fieldMap = [

                'basic-information'=>[
                    'fields'=>['name'],
                    'prompt_fields'=>"name: a clean, attractive, SEO-friendly product title (max 100 characters)",
                    'language_target'=>$languageName,
                ],

                'product-description'=>[
                    'fields'=>['description'],
                    'prompt_fields'=>"description: 2–4 paragraphs, attractive, benefit-focused HTML",
                    'language_target'=>$languageName,
                ],

                'product-seo-meta-tag'=>[
                    'fields'=>['meta_title','meta_description','meta_keywords'],
                    'prompt_fields'=>"meta_title, meta_description, meta_keywords (SEO optimized)",
                    'language_target'=>'English',
                ],

                'product-configuration'=>[
                    'fields'=>['weight','min_qty','tags'],
                    'prompt_fields'=>"weight (kg), min_qty, tags",
                    'language_target'=>$languageName,
                ],
            ];

            if(!isset($fieldMap[$section])){
                return response()->json([
                    'success'=>false,
                    'message'=>'Invalid section'
                ]);
            }

            $config = $fieldMap[$section];


            /*
            |--------------------------------------------------------------------------
            | Load Prompt From DB
            |--------------------------------------------------------------------------
            */

            $promptTemplate = AiPrompt::where('identifier','product_add_edit_prompt')
                ->value('prompt');

            if(!$promptTemplate){
                return response()->json([
                    'success'=>false,
                    'message'=>'AI prompt not configured'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Build Prompt
            |--------------------------------------------------------------------------
            */

            $prompt = str_replace(
                ['{product_name}','{language}','{prompt_fields}'],
                [$productName,$config['language_target'],$config['prompt_fields']],
                $promptTemplate
            );

            if(!empty($existingData)){
                $prompt .= "\nImprove this content:\n".json_encode($existingData);
            }


            /*
            |--------------------------------------------------------------------------
            | Gemini API Call
            |--------------------------------------------------------------------------
            */

            $model  = get_setting('gemini_model') ?? 'gemini-2.0-flash-lite';
            $apiKey = env('GEMINI_API_KEY');

            $response = Http::withHeaders([
                'Content-Type'=>'application/json'
            ])->post(
                "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents'=>[
                        [
                            'parts'=>[
                                ['text'=>$prompt]
                            ]
                        ]
                    ],
                    'generationConfig'=>[
                        'temperature'=>0.7,
                        'topP'=>0.95,
                        'maxOutputTokens'=>1024
                    ]
                ]
            );

            if(!$response->successful()){

                Log::error('Gemini API Error', [
                    'status'=>$response->status(),
                    'response'=>$response->body()
                ]);

                return response()->json([
                    'success'=>false,
                    'message'=>'AI request failed'
                ]);
            }

            $result = $response->json();


            /*
            |--------------------------------------------------------------------------
            | Extract AI Text
            |--------------------------------------------------------------------------
            */

            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

            if(!$text){
                return response()->json([
                    'success'=>false,
                    'message'=>'AI returned empty response'
                ]);
            }

            // Clean markdown code blocks
            $text = preg_replace('/^```json\s*|\s*```$/m','',$text);
            $text = preg_replace('/^```\s*|\s*```$/m','',$text);
            $text = trim($text);

            $data = json_decode($text,true);

            if(json_last_error() !== JSON_ERROR_NONE){

                Log::error('Invalid AI JSON', [
                    'response'=>$text,
                    'error'=>json_last_error_msg()
                ]);

                return response()->json([
                    'success'=>false,
                    'message'=>'Invalid AI JSON response'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Filter Needed Fields Only
            |--------------------------------------------------------------------------
            */

            $clean = [];

            foreach($config['fields'] as $field){
                $clean[$field] = $data[$field] ?? null;
            }


            /*
            |--------------------------------------------------------------------------
            | Token Usage
            |--------------------------------------------------------------------------
            */

            $tokenUsage = $result['usageMetadata'] ?? [];

            // Log::info('AI Usage',[
            //     'product'=>$productName,
            //     'section'=>$section,
            //     'tokens'=>$tokenUsage
            // ]);

            if (auth()->check()) {
                try {
                    $totalTokens = $tokenUsage['totalTokenCount'] ?? 0;
                    $promptTokens = $tokenUsage['promptTokenCount'] ?? 0;
                    $completionTokens = $tokenUsage['candidatesTokenCount'] ?? 0;
                    
                    aiUsageLogs::create([
                        'user_id' => auth()->id(),
                        'prompt_tokens' => $promptTokens,
                        'completion_tokens' => $completionTokens,
                        'total_tokens' => $totalTokens,
                        'model' => $model
                    ]);

                    if ($user && $user->user_type != 'admin') {

                        $user->seller_monthly_token_limit =
                            $user->seller_monthly_token_limit - $totalTokens;

                        $user->save();  

                    }  
                    
                } catch (\Exception $e) {
                    Log::error('Failed to log AI usage', ['error' => $e->getMessage()]);
                }
            }


            return response()->json([
                'success'=>true,
                'data'=>$clean,
                'section'=>$section,
                'language'=>$language,
                'is_regenerated'=>!empty($existingData),
                'tokens'=>$tokenUsage
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        }
        catch(\Exception $e){

            Log::error('AI Generate Error', [
                'message'=>$e->getMessage(),
                'trace'=>$e->getTraceAsString()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);
        } 
    }

}