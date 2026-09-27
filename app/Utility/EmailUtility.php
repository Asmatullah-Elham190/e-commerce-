<?php

namespace App\Utility;

use App\Mail\MailManager;
use App\Models\EmailTemplate;
use App\Models\User;
use Mail;

class EmailUtility
{
    // Customer registration email to Admin & Customer
    public static function customer_registration_email($emailIdentifier, $user, $password = null){
        $admin = get_admin();
        $emailSendTo = $emailIdentifier == 'customer_reg_email_to_admin' ? $admin->email : $user->email;
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[customer_name]]', $user->name, $emailSubject);
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $email_or_phone = $user->email != null ? $user->email : $user->phone;
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
        $emailBody = str_replace('[[customer_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[email]]', $user->email, $emailBody);
        $emailBody = str_replace('[[password]]', $password, $emailBody);
        $emailBody = str_replace('[[email/phone]]', $email_or_phone, $emailBody);
        $emailBody = str_replace('[[date]]', date('d-m-Y', strtotime($user->created_at)), $emailBody);
        $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);
        
        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($emailSendTo)->queue(new MailManager($array));
    }

     // Email verification for customer Registration
     public static function email_verification_for_registration_customer($emailIdentifier, $email, $verificationCode){
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[code]]', $verificationCode, $emailBody);
        $emailBody = str_replace('[[admin_email]]', get_admin()->email, $emailBody);

        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($email)->queue(new MailManager($array));
    }

    // Email verification for seller Registration
    public static function email_verification_for_registration_seller($emailIdentifier, $email, $verificationCode){
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[code]]', $verificationCode, $emailBody);
        $emailBody = str_replace('[[admin_email]]', get_admin()->email, $emailBody);

        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($email)->queue(new MailManager($array));
    }


    // Customer wallet recharge to Admin & Customer
    public static function wallet_recharge_email($emailIdentifier, $user, $amount, $payment_method){
        $admin = get_admin();
        $emailSendTo = $user->email;
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[amount]]', $amount, $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $email_or_phone = $user->email != null ? $user->email : $user->phone;
        $emailBody = str_replace('[[payment_method]]', $payment_method, $emailBody);
        $emailBody = str_replace('[[amount]]', $amount, $emailBody);
        $emailBody = str_replace('[[date]]', date('d-m-Y', strtotime($user->created_at)), $emailBody);
        $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);
        $emailBody = str_replace('[[customer_name]]', $user->name, $emailBody);
        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($emailSendTo)->queue(new MailManager($array));
    }
    
    // Seller registration email to Admin & Seller
    public static function selelr_registration_email($emailIdentifier, $user, $password = null){
        $admin = get_admin();
        $shop = $user->shop;
        $emailSendTo = $emailIdentifier == 'seller_reg_email_to_admin' ? $admin->email : $user->email;
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[shop_name]]', $shop->name, $emailSubject);
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[seller_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[seller_email]]', $user->email, $emailBody);
        $emailBody = str_replace('[[password]]', $password, $emailBody);
        $emailBody = str_replace('[[seller_shop_name]]', $shop->name, $emailBody);
        $emailBody = str_replace('[[seller_shop_address]]', $shop->address, $emailBody);
        $emailBody = str_replace('[[date]]', date('d-m-Y', strtotime($user->created_at)), $emailBody);
        $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);

        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($emailSendTo)->queue(new MailManager($array));
    }

    public static function deliveryBoyRegEmail($emailIdentifiers, $user, $password){
        $admin = get_admin();
        foreach($emailIdentifiers as $emailIdentifier){
            $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();
            if($emailTemplate != null && $emailTemplate->status == 1){
                
                $emailSendTo = $emailTemplate->receiver == 'admin' ? $admin->email : $user->email;

                $emailSubject = $emailTemplate->subject;
                $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);
    
                $emailBody = $emailTemplate->default_text;
                $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
                $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
                $emailBody = str_replace('[[delivery_boy_name]]', $user->name, $emailBody);
                $emailBody = str_replace('[[delivery_boy_email]]', $user->email, $emailBody);
                $emailBody = str_replace('[[delivery_boy_phone]]', $user->phone, $emailBody);
                $emailBody = str_replace('[[delivery_boy_password]]', $password, $emailBody);
                $emailBody = str_replace('[[delivery_boy_country]]', $user->country, $emailBody);
                $emailBody = str_replace('[[date]]', date('d-m-Y', strtotime($user->created_at)), $emailBody);
                $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);
    
                $array['subject'] = $emailSubject;
                $array['content'] = $emailBody;
                
                try {
                    Mail::to($emailSendTo)->queue(new MailManager($array));
                } catch (\Exception $e) {}
            }
        }
    }
 
    // Order delivery and payment status change Email
    public static function order_email($order, $status){
        $admin = get_admin();
        $userIds = array($order->seller_id);
        if($order->user->email != null){
            array_push($userIds, $order->user_id);
        }
        if ($order->seller_id != $admin->id) {
            array_push($userIds, $admin->id);
        }
        $users = User::findMany($userIds);

        foreach($users as $user){ 
            $emailIdentifier = 'order_'.$status.'_email_to_'.$user->user_type;
            $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

            if($emailTemplate != null && $emailTemplate->status == 1){
                $shopName = $user->user_type == 'seller' ? $user->shop->name : null;
                $emailSubject = $emailTemplate->subject;
                $emailSubject = str_replace('[[order_code]]', $order->code, $emailSubject);
                $emailBody = $emailTemplate->default_text;
                $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
                $emailBody = str_replace('[[shop_name]]', $shopName, $emailBody);
                $emailBody = str_replace('[[customer_name]]', $order->user->name, $emailBody);
                $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
                $emailBody = str_replace('[[order_code]]', $order->code, $emailBody);
                $emailBody = str_replace('[[order_date]]', date('d-m-Y', strtotime($order->created_at)), $emailBody);
                $emailBody = str_replace('[[delivery_date]]', date('d-m-Y'), $emailBody);
                $emailBody = str_replace('[[order_amount]]', single_price($order->grand_total), $emailBody);
                $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);
                
                $trackingSection = '';
                if($order->tracking_code != null ) {
                    $trackIdentifier = $order->tracking_code;
                    
                    $trackingLink = route('orders.track') . '?tracking_code=' . urlencode($trackIdentifier);
                    
                    $trackingSection = '<div style="margin-top: 15px; margin-bottom: 15px; padding: 10px; background-color: #f5f5f5; border-left: 4px solid #4CAF50;">
                        <p style="margin: 20px;"><strong>Tracking Code:</strong> ' . e($trackIdentifier) . '</p>
                        <p style="margin: 20px;"><strong>Track your order:</strong> <a href="' . $trackingLink . '" style="color: #4CAF50; text-decoration: none;">Click here to track your order</a></p>
                    </div>';
                } else {
                    $trackingSection = '';
                }
                
                $emailBody = str_replace('[[tracking_section]]', $trackingSection, $emailBody);
                $emailBody = str_replace('[[tracking_code]]', $order->tracking_code ?? '', $emailBody);
                $emailBody = str_replace('[[tracking_link]]', $trackingLink ?? '', $emailBody);
                
                $array['subject'] = $emailSubject;
                $array['content'] = $emailBody;
                try {
                    Mail::to($user->email)->queue(new MailManager($array));
                } catch (\Exception $e) {}
            }   
        }  
    }

    // User Email Verification
    public static function email_verification($user, $userType){
        $emailIdentifier =  'email_verification_'.$userType;
        $verification_code = encrypt($user->id);

        // User Veridication code add
        $user->verification_code = $verification_code;
        $user->save();

        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);
        
        $emailBody = $emailTemplate->default_text;
        $link = route('email.verification.confirmation', $verification_code);
        $verifyButton = '<div style="display: flex; justify-content: center; padding-bottom:4px;">
            <a href="'.$link.'" target="_blank" style="background: #0b60bd; text-decoration:none; padding: 1.4rem 2rem; color:#fff;border-radius: .3rem;">Click here</a>
        </div>';
        
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[customer_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[seller_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[verify_email_button]]', $verifyButton, $emailBody);
        $emailBody = str_replace('[[admin_email]]', get_admin()->email, $emailBody);

        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($user->email)->queue(new MailManager($array));

    }


     //Update Email OTP verification for customer Registration
     public static function email_otp_verification_for_update_email($user, $userType, $verificationCode, $new_email){

        $emailIdentifier =  'change_email_verification_code_'.$userType;
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[seller_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[customer_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[code]]', $verificationCode, $emailBody);
        $emailBody = str_replace('[[admin_email]]', get_admin()->email, $emailBody);
        $emailBody = str_replace('[[new_email]]', $new_email, $emailBody);
        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($user->email)->queue(new MailManager($array));
    }


    // Update Email Verification
    public static function change_email_verification($user, $userType, $new_email){
      
        $emailIdentifier =  'email_update_verification_'.$userType;
        $verification_code = encrypt($user->id);

        // User Veridication code add
        $user->new_email_verificiation_code = $verification_code;
        $user->save();
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);
        
        $emailBody = $emailTemplate->default_text;
        $link = route('email_change.callback') 
        . '?new_email_verificiation_code=' . urlencode($verification_code) 
        . '&email=' . urlencode($new_email);
        $verifyButton = '<div style="display: flex; justify-content: center; padding-bottom:4px;">
            <a href="'.$link.'" target="_blank" style="background: #0b60bd; text-decoration:none; padding: 1.4rem 2rem; color:#fff;border-radius: .3rem;">Click here</a>
        </div>';
        
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[customer_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[seller_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[verify_email_button]]', $verifyButton, $emailBody);
        $emailBody = str_replace('[[admin_email]]', get_admin()->email, $emailBody);

        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;
        Mail::to($new_email)->queue(new MailManager($array));

    }

    // Seller Payout emails
    public static function seller_payout($emailIdentifiers, $seller, $amount, $payment_method = null){
        $admin = get_admin();
        $shop = $seller->shop;
        foreach($emailIdentifiers as $emailIdentifier){
            $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();
            if($emailTemplate != null && $emailTemplate->status == 1){
                $emailSendTo = $emailTemplate->receiver == 'admin' ? $admin->email : $seller->email;

                $emailSubject = $emailTemplate->subject;
                $emailSubject = str_replace('[[shop_name]]', $shop->name, $emailSubject);
                $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);

                $emailBody = $emailTemplate->default_text;
                $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
                $emailBody = str_replace('[[shop_name]]', $shop->name, $emailBody);
                $emailBody = str_replace('[[shop_email]]', $seller->email, $emailBody);
                $emailBody = str_replace('[[amount]]', single_price($amount), $emailBody);
                $emailBody = str_replace('[[payment_method]]', $payment_method, $emailBody);
                $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);;
                $emailBody = str_replace('[[date]]', date('d-m-Y'), $emailBody);
                $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);

                $array['subject'] = $emailSubject;
                $array['content'] = $emailBody;

                try {
                    Mail::to($emailSendTo)->queue(new MailManager($array));
                } catch (\Exception $e) {}
            }
        
        }
    }

    // Refund Request Email
    public static function refundEmail($emailIdentifiers, $refundReqest){
        $order = $refundReqest->order;
        $customer = $refundReqest->user;
        $seller = $refundReqest->seller;
        $productName = $refundReqest->orderDetail->product->getTranslation('name');
        $shopName = $refundReqest?->order?->shop?->user->user_type == 'seller' ? $refundReqest?->order?->shop->name : null;

        $admin = get_admin();
        foreach($emailIdentifiers as $emailIdentifier){
            $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();
            if($emailTemplate != null && $emailTemplate->status == 1){
                
                if($emailTemplate->receiver == 'admin'){
                    $emailSendTo = $admin->email;
                }
                elseif($emailTemplate->receiver == 'seller'){
                    $emailSendTo = $seller->email;
                }
                elseif($emailTemplate->receiver == 'customer'){
                    $emailSendTo = $customer->email;
                }
                $emailSubject = $emailTemplate->subject;
                $emailSubject = str_replace('[[order_code]]', $order->code, $emailSubject);
                $emailSubject = str_replace('[[shop_name]]', $shopName, $emailSubject);
    
                $emailBody = $emailTemplate->default_text;
                $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
                $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
                $emailBody = str_replace('[[shop_name]]', $shopName, $emailBody);
                $emailBody = str_replace('[[customer_name]]', $customer->name, $emailBody);
                $emailBody = str_replace('[[order_code]]', $order->code, $emailBody);
                $emailBody = str_replace('[[product_name]]', $productName, $emailBody);
                $emailBody = str_replace('[[refund_reason]]', $refundReqest->reason, $emailBody);
                $emailBody = str_replace('[[denied_reason]]', $refundReqest->reject_reason, $emailBody);
                $emailBody = str_replace('[[request_date]]', date('d-m-Y', strtotime($refundReqest->created_at)), $emailBody);
                $emailBody = str_replace('[[refund_amount]]', single_price($refundReqest->refund_amount), $emailBody);
                $emailBody = str_replace('[[processes_date]]', date('d-m-Y'), $emailBody);
                $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);
    
                $array['subject'] = $emailSubject;
                $array['content'] = $emailBody;
                
                try {
                    Mail::to($emailSendTo)->queue(new MailManager($array));
                } catch (\Exception $e) {}
            }
        }
    }

    // Dispute Refund Request Email
    public static function disputeRefundEmail($emailIdentifiers, $refundReqest){
        $order = $refundReqest->order;
        $customer = $refundReqest->user;
        $seller = $refundReqest->seller;
        $productName = $refundReqest->orderDetail->product->getTranslation('name');
        $shopName = $refundReqest?->order?->shop?->user->user_type == 'seller' ? $refundReqest?->order?->shop->name : null;

        $admin = get_admin();
        foreach($emailIdentifiers as $emailIdentifier){
            $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();
            if($emailTemplate != null && $emailTemplate->status == 1){
                
                if($emailTemplate->receiver == 'admin'){
                    $emailSendTo = $admin->email;
                }
                elseif($emailTemplate->receiver == 'seller'){
                    $emailSendTo = $seller->email;
                }
                elseif($emailTemplate->receiver == 'customer'){
                    $emailSendTo = $customer->email;
                }
                $emailSubject = $emailTemplate->subject;
                $emailSubject = str_replace('[[order_code]]', $order->code, $emailSubject);
                $emailSubject = str_replace('[[shop_name]]', $shopName, $emailSubject);
    
                $emailBody = $emailTemplate->default_text;
                $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
                $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
                $emailBody = str_replace('[[shop_name]]', $shopName, $emailBody);
                $emailBody = str_replace('[[customer_name]]', $customer->name, $emailBody);
                $emailBody = str_replace('[[order_code]]', $order->code, $emailBody);
                $emailBody = str_replace('[[product_name]]', $productName, $emailBody);
                $emailBody = str_replace('[[dispute_reason]]', $refundReqest->dispute_reason, $emailBody);
                $emailBody = str_replace('[[denied_dispute_reason]]', $refundReqest->admin_dispute_reject_reason, $emailBody);
                $emailBody = str_replace('[[request_date]]', date('d-m-Y', strtotime($refundReqest->created_at)), $emailBody);
                $emailBody = str_replace('[[refund_amount]]', single_price($refundReqest->refund_amount), $emailBody);
                $emailBody = str_replace('[[processes_date]]', date('d-m-Y'), $emailBody);
                $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);
    
                $array['subject'] = $emailSubject;
                $array['content'] = $emailBody;
                
                try {
                    Mail::to($emailSendTo)->queue(new MailManager($array));
                } catch (\Exception $e) {}
            }
        }
    }

     // Seller registration email to Admin & Seller
    public static function seller_shop_approval_email($emailIdentifier, $shop){
        $admin = get_admin();
        $shop = $shop;
        $user= $shop->user;
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[seller_shop_name]]', $shop->name, $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $emailBody = str_replace('[[admin_name]]', $admin->name, $emailBody);
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[seller_name]]', $user->name, $emailBody);
        $emailBody = str_replace('[[seller_email]]', $user->email, $emailBody);
        $emailBody = str_replace('[[seller_shop_name]]', $shop->name, $emailBody);
        $emailBody = str_replace('[[seller_shop_address]]', $shop->address, $emailBody);
        $emailBody = str_replace('[[date]]', date('d-m-Y', strtotime($user->created_at)), $emailBody);
        $emailBody = str_replace('[[admin_email]]', $admin->email, $emailBody);
        $emailBody = str_replace('[[login_url]]', route('seller.login'), $emailBody);
        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;
        Mail::to($user->email)->queue(new MailManager($array));
    }

}
