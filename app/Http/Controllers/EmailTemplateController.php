<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_email_templates'])->only('index', 'edit', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function all_email(Request $request)
    {
        $sort_search = null;
        $addons = Addon::where('activated', 1)->pluck('unique_identifier')->toArray();
        $email_tabs = ['All Email Templates', 'Admin Email Templates', 'Seller Email Templates', 'Customer Email Templates', 'Common Email Templates'];
        $emails = EmailTemplate::orderBy('created_at', 'desc');

        // If email templated for addons, check addons are insatalled and activated.
        $emails->where(function ($query) use ($addons) {
                $query->whereAddon(null)
                    ->orWhere(function ($query) use ($addons) {
                        $query->whereIn('addon', $addons);
                    });
        });
        if ($request->has('search')) {
            $sort_search = $request->search;
            $emails = $emails->where('email_type', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%'.$sort_search.'%');
        }
        $emails = $emails->paginate(15);
        return view('backend.setup_configurations.email_templates.index', compact('emails', 'sort_search', 'email_tabs'));
    }

    public function filter(Request $request)
    {
        $addons = Addon::where('activated', 1)->pluck('unique_identifier')->toArray();
        $emails = EmailTemplate::orderBy('created_at', 'desc');
        
        // If email templated for addons, check addons are insatalled and activated.
        $emails->where(function ($query) use ($addons) {
                $query->whereAddon(null)
                    ->orWhere(function ($query) use ($addons) {
                        $query->whereIn('addon', $addons);
                    });
        });

        $sort_search = null;

        if ($request->email_status == 'admin_email_templates') {
            $emails = $emails->where('receiver', 'admin');
        } else if ($request->email_status == 'seller_email_templates') {
            $emails = $emails->where('receiver', 'seller');
        } else if ($request->email_status == 'customer_email_templates') {
            $emails = $emails->where('receiver', 'customer');
        } else if ($request->email_status == 'common_email_templates') {
            $emails = $emails->where('receiver', 'all');
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $emails = $emails->where(function($query) use ($sort_search) {
                $query->where('email_type', 'like', '%' . $sort_search . '%');
            });
        }

        $emails = $emails->paginate(15);
        $view = view(
            'backend.setup_configurations.email_templates.table',
            compact('emails', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $emailTemplate  = EmailTemplate::findOrFail($id);
        return view('backend.setup_configurations.email_templates.edit', compact('emailTemplate'));
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
        $emailTemplate = EmailTemplate::findOrFail($id);
        $emailTemplate->subject = $request->subject;
        $emailTemplate->default_text = $request->default_text;
        $emailTemplate->save();

        flash(translate('Email Template has been updated successfully'))->success();
        return back();
    }

    public function updateStatus(Request $request) {
        $emailTemplate = EmailTemplate::findOrFail($request->id);
        $emailTemplate->status = $request->status;
        $emailTemplate->save();
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
