<?php

namespace App\Http\Controllers\Admin;

use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\ServiceTiers;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategories;
use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use App\Models\ProfileLanguage;
use App\Models\ProfileDegree;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Request;

class FrontendController extends Controller
{

    public function templates()
    {
        $pageTitle = 'Templates';
        $temPaths = array_filter(glob('core/resources/views/templates/*'), 'is_dir');
        $templates=[];
        foreach ($temPaths as $key => $temp) {
            $arr = explode('/', $temp);
            $tempname = end($arr);
            $templates[$key]['name'] = $tempname;
            $templates[$key]['image'] = asset($temp) . '/preview.jpg';
        }
        $extra_templates = json_decode(getTemplates(), true);
        return view('admin.frontend.templates', compact('pageTitle', 'templates', 'extra_templates'));

    }

    public function templatesActive(Request $request)
    {
        $general = GeneralSetting::first();

        $general->active_template = $request->name;
        $general->save();

        $notify[] = ['success', 'Updated successfully'];
        return back()->withNotify($notify);
    }

    public function seoEdit()
    {
        $pageTitle = 'SEO Configuration';
        $seo = Frontend::where('data_keys', 'seo.data')->first();
        if(!$seo){
            $data_values = '{"keywords":["admin","blog"],"description":"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit","social_title":"WEBSITENAME","social_description":"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit","image":null}';
            $data_values = json_decode($data_values, true);
            $frontend = new Frontend();
            $frontend->data_keys = 'seo.data';
            $frontend->data_values = $data_values;
            $frontend->save();
        }
        return view('admin.frontend.seo', compact('pageTitle', 'seo'));
    }



    public function frontendSections($key)
    {
        $section = @getPageSections()->$key;
        if (!$section) {
            return abort(404);
        }
        $content = Frontend::where('data_keys', $key . '.content')->orderBy('id','desc')->first();
        $elements = Frontend::where('data_keys', $key . '.element')->orderBy('id')->orderBy('id','desc')->get();
        $pageTitle = $section->name ;
        $emptyMessage = 'No item create yet.';
        return view('admin.frontend.index', compact('section', 'content', 'elements', 'key', 'pageTitle', 'emptyMessage'));
    }




    public function frontendContent(Request $request, $key)
    {
        $purifier = new \HTMLPurifier();
        $valInputs = $request->except('_token', 'image_input', 'key', 'status', 'type', 'id');
        foreach ($valInputs as $keyName => $input) {
            if (gettype($input) == 'array') {
                $inputContentValue[$keyName] = $input;
                continue;
            }
            $inputContentValue[$keyName] = $purifier->purify($input);
        }
        $type = $request->type;
        if (!$type) {
            abort(404);
        }
        $imgJson = @getPageSections()->$key->$type->images;
        $validation_rule = [];
        $validation_message = [];
        foreach ($request->except('_token', 'video') as $input_field => $val) {
            if ($input_field == 'has_image' && $imgJson) {
                foreach ($imgJson as $imgValKey => $imgJsonVal) {
                    $validation_rule['image_input.'.$imgValKey] = ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])];
                    $validation_message['image_input.'.$imgValKey.'.image'] = inputTitle($imgValKey).' must be an image';
                    $validation_message['image_input.'.$imgValKey.'.mimes'] = inputTitle($imgValKey).' file type not supported';
                }
                continue;
            }elseif($input_field == 'seo_image'){
                $validation_rule['image_input'] = ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
                continue;
            }
            $validation_rule[$input_field] = 'required';
        }
        $request->validate($validation_rule, $validation_message, ['image_input' => 'image']);
        if ($request->id) {
            $content = Frontend::findOrFail($request->id);
        } else {
            $content = Frontend::where('data_keys', $key . '.' . $request->type)->first();
            if (!$content || $request->type == 'element') {
                $content = new Frontend();
                $content->data_keys = $key . '.' . $request->type;
                $content->save();
            }
        }
        if ($type == 'data') {
            $inputContentValue['image'] = @$content->data_values->image;
            if ($request->hasFile('image_input')) {
                try {
                    $inputContentValue['image'] = uploadImage($request->image_input,imagePath()['seo']['path'], imagePath()['seo']['size'], @$content->data_values->image);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload the image.'];
                    return back()->withNotify($notify);
                }
            }
        }else{
            if ($imgJson) {
                foreach ($imgJson as $imgKey => $imgValue) {
                    $imgData = @$request->image_input[$imgKey];
                    if (is_file($imgData)) {
                        try {
                            $inputContentValue[$imgKey] = $this->storeImage($imgJson,$type,$key,$imgData,$imgKey,@$content->data_values->$imgKey);
                        } catch (\Exception $exp) {
                            $notify[] = ['error', 'Could not upload the image.'];
                            return back()->withNotify($notify);
                        }
                    } else if (isset($content->data_values->$imgKey)) {
                        $inputContentValue[$imgKey] = $content->data_values->$imgKey;
                    }
                }
            }
        }
        $content->data_values = $inputContentValue;
        $content->save();
        $notify[] = ['success', 'Content has been updated.'];
        return back()->withNotify($notify);
    }



    public function frontendElement($key, $id = null)
    {
        $section = @getPageSections()->$key;
        if (!$section) {
            return abort(404);
        }

        unset($section->element->modal);
        $pageTitle = $section->name . ' Items';
        if ($id) {
            $data = Frontend::findOrFail($id);
            return view('admin.frontend.element', compact('section', 'key', 'pageTitle', 'data'));
        }
        return view('admin.frontend.element', compact('section', 'key', 'pageTitle'));
    }




    protected function storeImage($imgJson,$type,$key,$image,$imgKey,$old_image = null)
    {
        $path = 'assets/images/frontend/' . $key;
        if ($type == 'element' || $type == 'content') {
            $size = @$imgJson
            ->$imgKey->size;
            $thumb = @$imgJson
            ->$imgKey->thumb;
        }else{
            $path = imagePath()[$key]['path'];
            $size = imagePath()[$key]['size'];
            $thumb = @imagePath()[$key]['thumb'];
        }
        return uploadImage($image, $path, $size, $old_image, $thumb);
    }

    public function remove(Request $request)
    {
        $request->validate(['id' => 'required']);
        $frontend = Frontend::findOrFail($request->id);
        $key = explode('.', @$frontend->data_keys)[0];
        $type = explode('.', @$frontend->data_keys)[1];
        if (@$type == 'element' || @$type == 'content') {
            $path = 'assets/images/frontend/' . $key;
            $imgJson = @getPageSections()->$key->$type->images;
            if ($imgJson) {
                foreach ($imgJson as $imgKey => $imgValue) {
                    removeFile($path . '/' . @$frontend->data_values->$imgKey);
                    removeFile($path . '/thumb_' . @$frontend->data_values->$imgKey);
                }
            }
        }
        $frontend->delete();
        $notify[] = ['success', 'Content has been removed.'];
        return back()->withNotify($notify);
    }

    public function managetiers()
    {
        $emptyMessage = 'No Record Found.';
        $pageTitle = 'Manage Tiers';

        $tiers = ServiceTiers::when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(getPaginate())
            ->withQueryString();

        return view(
            'admin.frontend.tiers.index',
            compact('pageTitle', 'tiers', 'emptyMessage')
        );
    }
    

    public function addtiers()
    {
        $pageTitle = 'Add Tiers'; 
        $cat=Category::all();
        $subcat=SubCategory::all();
        $childcat=ChildCategories::all();

        return view('admin.frontend.tiers.add', compact('pageTitle','cat','subcat','childcat'));
    }

    public function submittier(Request $request){
        $tier=new ServiceTiers();
        $tier->name=$request->name;
        $tier->type=$request->type;
        $tier->cat_id=$request->cat_id;
        $tier->subcat_id=$request->subcat_id;
        $tier->childcat_id=$request->childcat_id;
        $tier->save();
        $notify[] = ['success', 'Service Tier Added Successfully.'];
        return back()->withNotify($notify);
    }

    public function edittiers($id)
    {
         $edittier = ServiceTiers::findOrFail($id);
        $pageTitle = 'Edit Tiers'; 
        $cat=Category::all(); 
        $subcat=SubCategory::all();
        $childcat=ChildCategories::all();
        return view('admin.frontend.tiers.edit', compact('pageTitle','cat','subcat','edittier','childcat'));
    }
     
     public function update(Request $request, $id)
    {
       

        $tier = ServiceTiers::findOrFail($id);
        
      $tier->name=$request->name;
        $tier->type=$request->type;
        $tier->cat_id=$request->cat_id;
        $tier->subcat_id=$request->subcat_id;
        $tier->childcat_id=$request->childcat_id;
        $tier->save();

        $notify[] = ['success', $tier->name . ' has been updated.'];
        return back()->withNotify($notify);
    }

    public function manageprolangs()
    {
        $emptyMessage = 'No Record Found.';
        $pageTitle = 'Manage Profile Languages'; 
        $langs=ProfileLanguage::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.frontend.prolangs.index', compact('pageTitle','langs','emptyMessage'));
    }
    public function addlanguages()
    {
        $pageTitle = 'Add Languages'; 
       
        return view('admin.frontend.prolangs.add', compact('pageTitle'));
    }
    public function submitlang(Request $request){
        $lang=new ProfileLanguage();
        $lang->name=$request->name;
        $lang->save();
        $notify[] = ['success', 'Language Added Successfully.'];
        return back()->withNotify($notify);
    }
    public function editlang($id)
    {
         $editlang = ProfileLanguage::findOrFail($id);
        $pageTitle = 'Edit Language'; 
        
        return view('admin.frontend.prolangs.edit', compact('pageTitle','editlang'));
    }
    public function updatelang(Request $request, $id)
    {
       

        $lang = ProfileLanguage::findOrFail($id);
        
      $lang->name=$request->name;
        
        $lang->save();

        $notify[] = ['success', $lang->name . ' has been updated.'];
        return redirect()->route('admin.frontend.managelanguagesprofile')->withNotify($notify);
    }



    public function manageprodegree()
    {
        $emptyMessage = 'No Record Found.';
        $pageTitle = 'Manage Profile Degree'; 
        $degree=ProfileDegree::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.frontend.prodegree.index', compact('pageTitle','degree','emptyMessage'));
    }
    public function adddegree()
    {
        $pageTitle = 'Add Degree';  
       
        return view('admin.frontend.prodegree.add', compact('pageTitle'));
    }
    public function chatdataold()
    {
        $pageTitle = 'Chat Data'; 
       
        return view('admin.frontend.chatdata', compact('pageTitle'));
    }
    public function chatdata()
    { 
        $pageTitle    = "Chat";
        $emptyMessage = "No data found";
         
        $users        = User::get();
        $z            = array(); 
        
            $firstcahat   = Chat::first();
            $sid = $firstcahat->ch_s_id;
            $rid = $firstcahat->ch_r_id;
         
        
        $user         = Chat::where('ch_s_id',$sid)
                              ->where('ch_r_id',$rid)
                              ->get()->pluck('ch_id');

                    foreach ($user as $key) {
                       array_push($z, $key);    
                    }
                    

        $user1 =Chat::where('ch_s_id',$rid)
                      ->where('ch_r_id',$sid)
                      ->get()->pluck('ch_id');
                     foreach ($user1 as $key) {
                       array_push($z, $key);    
                    }
            //  print_r($z);
            //  exit;
           $chats = Chat::whereIn('ch_id',$z)->get();
           foreach ($chats as $key) {
               
               $ss =User::where('id', $key->ch_s_id)->first();
               $key->sender_user =$ss;
               $rr = User::where('id', $key->ch_r_id)->first();
               $key->reciver_user =$rr;
            //    print_r($key);
            //    exit;
           } 

        //   print_r($chats);
        //     exit;
       
        return view('admin.frontend.chatdata',compact('pageTitle', 'emptyMessage','users','chats','sid','rid')); 
    }

    public function chatdataDetail($ssid=null , $rrid=null)
    { 
        $pageTitle    = "Chat";
        $emptyMessage = "No data found";
         
        $users        = User::get();
        $z            = array();  
        if($ssid && $rrid){ 
            $sid = $ssid;
            $rid = $rrid;
        }else{
            $firstcahat   = Chat::first();
            $sid          = $firstcahat->ch_s_id;
            $rid          = $firstcahat->ch_r_id;
        }
        
        $user     = Chat::where('ch_s_id',$sid)
                              ->where('ch_r_id',$rid)
                              ->get()->pluck('ch_id');

                    foreach ($user as $key) {
                       array_push($z, $key);       
                    }
                    

        $user1 =Chat::where('ch_s_id',$rid)
                      ->where('ch_r_id',$sid)
                      ->get()->pluck('ch_id');
                     foreach ($user1 as $key) {
                       array_push($z, $key);        
                    }
 
           $chat = $this->ChatUser();
           $chatsg = User::whereIn('id',$chat)->get();
           $chats = Chat::whereIn('ch_id',$z)->get();
           foreach ($chats as $key) {
               
               $ss =User::where('id', $key->ch_s_id)->first();
               $key->sender_user =$ss;
               $rr = User::where('id', $key->ch_r_id)->first();
               $key->reciver_user =$rr;
            //    print_r($key);
            //    exit;
           } 

        //   print_r($chatsg);
        //     exit;
       
        return view('admin.frontend.chatdata',compact('pageTitle', 'emptyMessage','users','chatsg','chats','sid','rid')); 
    }

    public function submitdegree(Request $request){
        $degree=new ProfileDegree();
        $degree->name=$request->name;
        $degree->save();
        $notify[] = ['success', 'Degree Added Successfully.'];
        return back()->withNotify($notify);
    } 
    public function editdegree($id)
    {
         $editdegree = ProfileDegree::findOrFail($id);
        $pageTitle = 'Edit Degree'; 
        
        return view('admin.frontend.prodegree.edit', compact('pageTitle','editdegree'));
    }
    public function updatedegree(Request $request, $id)
    {
       

        $degree = ProfileDegree::findOrFail($id);
        
      $degree->name=$request->name;
        
        $degree->save();

        $notify[] = ['success', $degree->name . ' has been updated.'];
        return redirect()->route('admin.frontend.managedegreeprofile')->withNotify($notify);
    }

    public function ChatUser()
    {
        $chats = Chat::get(); 
        $users =[]; 
        foreach($chats as $u){
            array_push($users,$u->ch_r_id);
            array_push($users,$u->ch_s_id);
        }  
       $rf =  array_unique($users, SORT_REGULAR); 
       return $rf ; 
     }

}
