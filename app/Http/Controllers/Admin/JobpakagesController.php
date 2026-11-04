<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobpakages;

class JobpakagesController extends Controller
{

    public function index()
    {
        $pageTitle = "job Package for Employer";
    	$emptyMessage = "No data found";
    
        $pakage_details = Jobpakages::paginate(getPaginate());
        return view('admin.pakages.jobpakages',compact('pageTitle','emptyMessage','pakage_details'));
    }


    public function add()
    {
        $pageTitle = "Add Bids Packages";
    	$emptyMessage = "No data found";
        
        return view('admin.pakages.addjobpakages',compact('pageTitle','emptyMessage'));
    }
   
    public function store(Request $request){
        // $request->validate([
        //     'title' => 'required|string',
        //     'type' => 'required',
        //     'price' => 'required',
        //     'status' => 'required',
        //     'jobs' => 'required',
          
        // ]);
        $add = new Jobpakages();
        $add->title = $request->title;
        // $add->type = $request->type;
        $add->price = $request->price;
        // $add->status = $request->status;
        $add->jobs = $request->jobs;
        $add->invites = $request->invites;
        $add->f_jobs = $request->f_jobs;
 
        $add->save();
        $notify[] = ['success', 'Package has been created.'];
        return back()->withNotify($notify);
    }
    public function update(Request $request){
        // $request->validate([
        //     'title' => 'required|string',
        //     'type' => 'required',
        //     'price' => 'required',
        //     'status' => 'required',
        //     'jobs' => 'required',
        // ]);
        $addmember = Jobpakages::where('id',$request->id)->update([
        'title' => $request->title,
        // 'type' => $request->type,
        'price' => $request->price,
        // 'status' => $request->status,
        'jobs' => $request->jobs,
        'f_jobs' => $request->f_jobs,
        'invites' => $request->invites,
          ]);
        $notify[] = ['success', 'Package has been Updated.'];
        return back()->withNotify($notify);
    }
}
