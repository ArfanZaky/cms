<?php

namespace App\Http\Controllers\Api\Contact;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\FormMail;
use App\Models\WebArticleCategories;
use App\Models\WebContacts;
use App\Models\WebEmail;
use App\Models\WebSettings;
use App\Services\LogServices;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Validator;

class ContactApiController extends BaseController
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function store(Request $request)
    {
        $input = collect($request)->toArray();
        $validator = Validator::make($input, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|max:255',
            'description' => 'required',
        ], [
            'full_name.required' => 'Full Name is required',
            'email.required' => 'Email is required',
            'subject.required' => 'Subject is required',
            'description.required' => 'Description is required',
        ]);

        $language = _get_languages($request->lang);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), [], 404);
        }

        // $recaptchaResponse = $request->gresponse;
        // $secretKey = WebSettings::where('code', 'web_captcha_secret_key')->first()->value;

        // $response = Http::get('https://www.google.com/recaptcha/api/siteverify', [
        //     'secret' => $secretKey,
        //     'response' => $recaptchaResponse,
        // ]);

        // $responseData = $response->collect();

        // if ($responseData->get('success') == false) {
        //     return $this->sendError('Captcha not valid', [], 404);
        // }
        
        DB::beginTransaction();
        try {
            $data = WebContacts::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'subject' => $request->subject,
                'description' => $request->description,
                'status' => '1',
            ]);
            DB::commit();
            $data  = [
                'code' => 200,
                'message' => 'Contact created successfully.'
            ];
            $temp['name'] = $request->full_name;
            $temp['subject'] = $request->subject;
            $temp['email'] = $request->email;
            $temp['description'] = $request->description;
            $email = \App\Helper\Helper::_setting_code('web_email');

            Mail::to($email)->send(new FormMail($temp));

            return $this->sendResponse($data, 'Contact created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);
            return $this->sendError('Something went wrong', [], 404);
        }
    }

}
