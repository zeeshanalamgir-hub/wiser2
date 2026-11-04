<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidpakages;

class BidpakagesController extends Controller
{

    public function index()
    {
        // dd("g");
        $pageTitle = "Bids Package for Freelancer";
    	$emptyMessage = "No data found";
    
        $pakage_details = Bidpakages::paginate(getPaginate());
        return view('admin.pakages.bidpakages',compact('pageTitle','emptyMessage','pakage_details'));
    }


    public function add()
    {
        $pageTitle = "Add Bids Packages";
    	$emptyMessage = "No data found";
        
        return view('admin.pakages.addbidpakage',compact('pageTitle','emptyMessage'));
    }
    
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string',
            'price' => 'required',
            'status' => 'required',
            'bids' => 'required',
          
        ]);
        $add = new Bidpakages();
        $add->title = $request->title;
        $add->price = $request->price;
        $add->services = $request->services;
        $add->f_services = $request->f_services;
        $add->status = $request->status;
        $add->bids = $request->bids;

        $add->save();
        $notify[] = ['success', 'Package has been created.'];
        return back()->withNotify($notify);
    }
    public function update(Request $request){
        $request->validate([
            'title' => 'required|string',
            'price' => 'required',
            'status' => 'required',
            'bids' => 'required',
        ]);
        $addmember = Bidpakages::where('id',$request->id)->update([
        'title' => $request->title,
        'price' => $request->price,
        'status' => $request->status,
        'services' => $request->services,
        'f_services' => $request->f_services,
        'bids' => $request->bids,
          ]);
        $notify[] = ['success', 'Package has been Updated.'];
        return back()->withNotify($notify);
    }
}
