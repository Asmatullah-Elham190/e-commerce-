<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // return false;
        $data = array(
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value
        );

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', $data);

            $recaptchaData = $response->json();
            //dd($recaptchaData);
            return ($recaptchaData['success'] ?? false) && ($recaptchaData['score'] ?? 0) >= (float) env('RECAPTCHA_SCORE_THRESHOLD', 0.5);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return (translate('Verification failed. Please try again.'));
    }
}
