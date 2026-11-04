<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
     public function index(Request $request)
    {
        $search = $request->search;
        
        $contactUs = UserContactUs::when($search, function($query) use ($search) {
            $query->where('fullname', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('company', 'like', "%$search%")
                  ->orWhere('subject', 'like', "%$search%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(getPaginate(10));
        
        $pageTitle = 'Contact Us Messages';
        $emptyMessage = 'No contact messages found';
        
        return view('admin.contact_us.index', compact('contactUs', 'pageTitle', 'emptyMessage', 'search'));
    }
    
    public function destroy($id)
    {
        $contact = UserContactUs::findOrFail($id);
        $contact->delete();
        
        $notify[] = ['success', 'Contact message deleted successfully'];
        return back()->withNotify($notify);
    }
 
}
