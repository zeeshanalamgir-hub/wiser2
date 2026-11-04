<?php

namespace App\Http\Controllers;

use App\Jobs\ContactEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\UserContactUs;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {

            UserContactUs::create($validated);
            $supportEmail = env('SUPPORT_MAIL', 'support@wiserlance.com');
            $contactData = [
                'fullname' => $request->fullname,
                'email' => $request->email,
                'company' => $request->company ?? '',
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            dispatch(new ContactEmailJob($contactData, $supportEmail));

            $notify[] = ['success', 'Your message has been sent successfully! We\'ll respond within 24 hours.'];

            return redirect()->back()->withNotify($notify);
            
        } catch (\Exception $e) {
            $notify[] = ['error', 'Sorry, there was an error sending your message. Please try again later.'];
            return redirect()->back()->withNotify($notify);
        }
    }
}
