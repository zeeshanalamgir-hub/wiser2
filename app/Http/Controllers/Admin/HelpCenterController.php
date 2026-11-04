<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\HelpCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HelpCenterController extends Controller
{
    public function index(Request $request, $type)
    {
        $request->merge([
            'type'=>$type
        ]);
        $request->validate([
            'type' => 'required|in:buyer,seller,policy,terms',
        ]);
        $type = $request->input('type');
        $createTitle = ucfirst($type).' Help Center';
        $pageTitle = ucfirst($type)." Help Center Listing";
        $helpCenters = HelpCenter::with('details')->type($type)->latest()->paginate(10);
        return view('admin.help_center.index', compact('helpCenters', 'pageTitle', 'type','createTitle'));
    }

    public function create(Request $request, $type)
    {
        $request->merge([
            'type'=>$type
        ]);

        $request->validate([
            'type' => 'required|in:buyer,seller,policy,terms',
        ]);

        $type = $request->input('type');
        $pageTitle = "Create New Record";
        $emptyMessage = "Data Not Found";

        return view('admin.help_center.create',compact('pageTitle','emptyMessage','type'));
    }

    public function store(Request $request, $type)
    {
        $request->merge([
            'type'=>$type
        ]);
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:buyer,seller,policy,terms',
            'details.*.sub_title' => 'required',
            'details.*.desc' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $helpCenter = HelpCenter::create([
                'title' => $request->title,
                'slug' => $this->checkSlug($request),
                'type' => $request->type,
            ]);

            foreach ($request->details as $detail) {
                $helpCenter->details()->create($detail);
            }

            DB::commit();

            $notify[] = ['success', ' Record been created'];

            return redirect()->route('admin.help-center.index',['type'=>$type])->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request, $type, $id)
    {
        $request->merge([
            'type'=>$type
        ]);
        $request->validate([
            'type' => 'required|in:buyer,seller,policy,terms',
        ]);

        $pageTitle = "Update Record";
        $helpCenter = HelpCenter::with('details')->findOrFail($id);
        return view('admin.help_center.edit', compact('helpCenter','pageTitle','type'));
    }

    public function update(Request $request, $type, $id)
    {
        $helpCenter = HelpCenter::findOrFail($id);
        $request->merge([
            'type'=>$type
        ]);

        $request->validate([
            'title' => 'required',
            'type' => 'required|in:buyer,seller,policy,terms',
            'details.*.sub_title' => 'required',
            'details.*.desc' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $helpCenter->update([
                'title' => $request->title,
                'slug' => $this->checkSlug($request),
                'type' => $request->type,
            ]);

            $helpCenter->details()->delete();

            foreach ($request->details as $detail) {
                $helpCenter->details()->create($detail);
            }

            if($request->type == 'terms' || $request->type == 'policy')
            {
                $admin = Admin::first();
                $users = User::where('status', 1)->cursor();
                foreach ($users as $user) {
                    if($request->type == 'policy') {
                        sendPrivacyAndTermsNotification($admin->id, $user->id, 'PRIVACY_POLICY');
                    }
                    else if($request->type == 'terms') {
                        sendPrivacyAndTermsNotification($admin->id, $user->id, 'TERMS_SERVICES');
                    }
                }
            }

            DB::commit();
            $notify[] = ['success', ' Record been updated'];

            return redirect()->route('admin.help-center.index',['type'=>$type])->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($type, $id)
    {
        $helpCenter = HelpCenter::findOrFail($id);
        $helpCenter->details()->delete();
        $helpCenter->delete();
        return response()->json([
            'success'=>true,
        ]);
    }

    function checkSlug(Request $request)
    {
        $baseSlug = Str::slug($request->title);
    
        $slug = $baseSlug;
        $count = 1;
    
        while (
            HelpCenter::where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $count++;
        }
        return $slug;
    }
}
