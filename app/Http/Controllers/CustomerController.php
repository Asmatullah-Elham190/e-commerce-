<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Utility\CustomerUtility;
use App\Utility\EmailUtility;
use Hash;

class CustomerController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_customers'])->only('index');
        $this->middleware(['permission:add_customer'])->only('create');
        $this->middleware(['permission:login_as_customer'])->only('login');
        $this->middleware(['permission:ban_customer'])->only('ban');
        $this->middleware(['permission:mark_customer_suspected'])->only('suspicious');
        $this->middleware(['permission:delete_customer'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $customer_tabs = ['All Customers', 'Banned', 'Suspicious', 'Verified', 'Unverified'];
        $customers = User::where('user_type', 'customer')->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $customers = $customers->where('name', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%'.$sort_search.'%');
        }
        $customers = $customers->paginate(15);
        return view('backend.customer.customers.index', compact('customers', 'sort_search', 'customer_tabs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.customer.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            ['name' => 'required|max:255',],
            ['name.required' => translate('Name is required'),'name.max' => translate('Max 255 Character'),]
        );

        // Phone & email both can't be null
        if($request->email == null && $request->phone == null){
            flash(translate('Email and phone number both can not be null.'))->error();
                return back();
        }

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if(User::where('email', $request->email)->first() != null){
                flash(translate('Email already exists.'))->error();
                return back();
            }
        }
        elseif (User::where('phone', '+'.$request->country_code.$request->phone)->first() != null) {
            flash(translate('Phone already exists.'))->error();
            return back();
        }

        $password = substr(hash('sha512', rand()), 0, 8);
        $email = null;
        $phone = null;
        
        // Register By email
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $email = $request->email;
            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            // Account Opening Email to customer
            try {
                EmailUtility::customer_registration_email('registration_from_system_email_to_customer', $user, $password);
            } catch (\Exception $e) {
                $user->delete();
                flash(translate('Registration failed. Please try again later.'))->error();
                return back();
            }

            // Email Verification mail to Customer
            if(get_setting('email_verification') != 1){
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                offerUserWelcomeCoupon();
            }
            else {
                EmailUtility::email_verification($user, 'customer');
            }
            flash(translate('Registration successful.'))->success();

        }
        // Register by phone
        else {
            if (addon_is_activated('otp_system')){
                $phone = '+'.$request->country_code.$request->phone;
                $user = User::create([
                    'name' => $request->name,
                    'phone' => $phone,
                    'password' => Hash::make($password),
                    'verification_code' => rand(100000, 999999)
                ]);

                $otpController = new OTPVerificationController;
                $otpController->account_opening($user, $password);
                flash(translate('Registration successful.'))->success();
            }
        }

        // Customer Account Opening Email to Admin
        if ((get_email_template_data('customer_reg_email_to_admin', 'status') == 1)) {
            try {
                EmailUtility::customer_registration_email('customer_reg_email_to_admin', $user, null);
            } catch (\Exception $e) {}
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $user = User::findOrFail(decrypt($id));
        if($request->verification_status){
           $user->verification_status = $request->verification_status;
        }
        $user->save();
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        CustomerUtility::delete_customer($id);
        return 1;
    }
    
    public function bulk_customer_delete(Request $request) 
    {
        if($request->id) {
            foreach ($request->id as $customer_id) {
                $customer = User::findOrFail($customer_id);
                if (!$customer) {
                    continue;
                }
                CustomerUtility::delete_customer($customer_id);
            }
            return 1;
        }
    }

    public function filter_customer(Request $request)
    {
        // Log::info('Filter All Customer Request: ', $request->all());
        $customers = User::where('user_type', 'customer')->orderBy('id', 'desc');
        $sort_search = null;

        if ($request->customer_status == "banned") {
            $customers = $customers->where('banned', 1);
        } else if ($request->customer_status == 'suspicious') {
            $customers = $customers->where('is_suspicious', 1);
        } else if ($request->customer_status == 'verified') {
            $customers = $customers->whereNotNull('email_verified_at');
        }else if ($request->customer_status == 'unverified') {
            $customers = $customers->whereNull('email_verified_at');
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $customers = $customers->where(function($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%')
                    ->orWhere('email', 'like', '%' . $sort_search . '%');
            });
        }

        $customers = $customers->paginate(15);
        $view = view(
            'backend.customer.customers.table',
            compact('customers', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function login($id)
    {
        $user = User::findOrFail(decrypt($id));

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    public function ban($id) {
        $user = User::findOrFail(decrypt($id));

        if($user->banned == 1) {
            $user->banned = 0;
            flash(translate('Customer UnBanned Successfully'))->success();
        } else {
            $user->banned = 1;
            flash(translate('Customer Banned Successfully'))->success();
        }

        $user->save();
        
        return back();
    }
    public function suspicious($id) {
        $user = User::findOrFail($id);

        if($user->is_suspicious == 1) {
            $user->is_suspicious = 0;
            flash(translate('Customer unsuspected  Successfully'))->success();
        } else {
            $user->is_suspicious = 1;
            flash(translate('Customer suspected Successfully'))->success();
        }

        $user->save();
        
        return back();
    }

    public function unverifiedCustomers(Request $request)
    {
        $sort_search = null;
        $docs_submited =  $request->docs_submited ?? null;
        $users = User::where('user_type', 'customer')->where('verification_status', 0)->orderBy('created_at', 'desc');
        if($docs_submited != null){
            $users = $docs_submited == 'submitted' ? $users->where('verification_info', '!=', null) : $users->where('verification_info', null);
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
            });
        }
        $users = $users->paginate(15);
        return view('backend.customer.customers.unverified', compact('users', 'sort_search', 'docs_submited'));
    }
}
