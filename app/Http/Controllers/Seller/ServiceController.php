<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\BidsLog;
use App\Models\Booking;
use App\Models\Category;
use App\Models\ChildCategories;
use App\Models\Features;
use App\Models\Freelancerservicescount;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Newskillbyuser;
use App\Models\OptionalImage;
use App\Models\Rank;
use App\Models\SellerDetailSkillNewExtra;
use App\Models\SellerDetailSkillParent;
use App\Models\Service;
use App\Models\Servicedetailskillnewextra;
use App\Models\Servicedetailskillparent;
use App\Models\ServiceDetailTier;
use App\Models\Servicedisputedata;
use App\Models\Servicedisputeoffers;
use App\Models\ServicesDetail;
use App\Models\Serviceskill;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserServicesAddsOn;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function __construct(protected $activeTemplate = null)
    {
        $this->activeTemplate = activeTemplate();
    }

    public function index(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = 'Manage service';
        $emptyMessage = 'No data found';
        $services = Service::where('user_id', $user->id)->with('category')->orderBy('id', 'DESC')->paginate(getPaginate());
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => ' Manage Services'],
        ];

        return view($this->activeTemplate.'user.seller.service.index', compact('pageTitle', 'services', 'emptyMessage', 'breadcrumbs'));
    }

    public function create(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = 'Create service';
        $features = Features::latest()->get();
        $userservices = Freelancerservicescount::where('user_id', $user->id)->get();
        // $categoryss=DB::table('categories')->where('type','service')->get();
        $categoryss = DB::table('categories')->where('type', 'service')->get();
        $servicescount = $userservices[0]->normal_services + $userservices[0]->priority_services + $userservices[0]->featured_services;

        if ($servicescount < 1) {
            $notify[] = ['error', 'Your Service posting limit is full.'];

            return redirect(url('/user/seller/dashboard'))->withNotify($notify);
        }
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => ' Create Service'],
        ];
        $allSkillsMap = Skill::pluck('name', 'id')->toArray(); // [id => name]

        return view($this->activeTemplate.'user.seller.service.create', compact(
            'pageTitle',
            'features',
            'userservices',
            'categoryss',
            'allSkillsMap',
            'breadcrumbs'
        ));
    }

    public function store(Request $request)
    {
        $allowedTags = '<b><i><u><a><p><br><ul><ol><li><strong>';
        $plainDescription = strip_tags($request->description); // No allowed tags, just pure text
        if (mb_strlen($plainDescription) > 2000) {
            return back()->withInput()->withErrors(['description' => 'Description must not exceed 2000 characters (excluding formatting).']);
        }

        $request->merge([
            'description' => strip_tags($request->description, $allowedTags),
        ]);

        $rules = [
            'title' => 'required|string|max:90',
            'description' => 'required|string|min:10',
            'optional_image.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required|integer|exists:categories,id',
            'subcategory' => 'required|integer|exists:sub_categories,id',
            'childcategory' => 'nullable|integer|exists:child_categories,id',
            'main_image_temp' => 'required|string',
            'optional_images_temp' => 'nullable|array|max:5',
            'priority' => 'required',
        ];

        // Restrict optional images count
        if ($request->hasFile('optional_image') && count($request->file('optional_image')) > 5) {
            return back()->withNotify([
                ['optional_image', 'You can upload up to 5 additional images only.'],
            ]);
        }

        // Messages
        $messages = [
            'title.required' => 'Title is required.',
            'title.max' => 'Title must not exceed 90 characters.',

            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters.',

            'optional_image.*.image' => 'Each file must be an image.',
            'optional_image.*.mimes' => 'Only jpeg, png, jpg formats are allowed.',
            'optional_image.*.max' => 'Each image must be less than 2MB.',

            'category.required' => 'Category is required.',
            'subcategory.required' => 'Subcategory is required.',
            'childcategory.exists' => 'Selected child category is invalid.',

            'main_image_temp.required' => 'Main image is required.',

            'optional_images_temp.max' => 'Maximum 5 optional images allowed.',

            'priority.required' => 'The "Post as a" field is required.',
        ];

        // Multi / Single Package Rules
        if ($request->msval == 1) {

            // Multi-package
            for ($i = 1; $i <= 3; $i++) {

                $rules["title$i"] = 'required|string|max:20';
                $rules["desc$i"] = 'required|string|max:30';
                $rules["price$i"] = 'required|numeric|min:1';
                $rules["time$i"] = 'required|numeric|min:1';
                $rules["revision$i"] = 'required|numeric|min:1';

                // Dynamic messages
                $messages["title$i.required"] = "Package $i title is required.";
                $messages["desc$i.required"] = "Package $i description is required.";
                $messages["price$i.required"] = "Package $i price is required.";
                $messages["time$i.required"] = "Package $i delivery time is required.";
                $messages["revision$i.required"] = "Package $i revision is required.";
            }

        } else {

            // Single package
            $rules['titlesin'] = 'required|string|max:60';
            $rules['descsin'] = 'required|string|max:30';
            $rules['pricesin'] = 'required|numeric|min:1';
            $rules['timesin'] = 'required|numeric|min:1';
            $rules['revisionin'] = 'required|numeric|min:1';

            $messages['titlesin.required'] = 'Package title is required.';
            $messages['descsin.required'] = 'Package description is required.';
            $messages['pricesin.required'] = 'Package price is required.';
            $messages['timesin.required'] = 'Delivery time is required.';
            $messages['revisionin.required'] = 'Revision is required.';

            $messages['revisionin.numeric'] = 'Revision must be a valid number.';
            $messages['revisionin.min'] = 'Revision must be at least 1.';
        }

        $request->validate($rules, $messages);
        // Process tags (skills) as per your existing logic...
        $tag_data = [];
        if (isset($request->skills)) {
            foreach ($request->skills as $skill) {
                $get = Skill::where('id', $skill)->first();
                array_push($tag_data, $get->name);
            }
        }
        if ($tag_data == null) {
            $tag = $request->newskills;
        } else {
            if ($request->newskills == null) {
                $tag = $tag_data;
            } else {
                $tag = array_merge($tag_data, $request->newskills);
            }
        }

        $user = Auth::user();
        $general = GeneralSetting::first();

        $service = new Service;
        $service->user_id = $user->id;
        $service->category_id = $request->category;
        $c_name = DB::table('categories')->where('id', $request->category)->first();
        $service->cat_name = $c_name->name;
        $service->sub_category_id = $request->subcategory ? $request->subcategory : null;
        $sub_name = DB::table('sub_categories')->where('id', $request->subcategory)->first();
        $service->subcat_name = $sub_name->name;

        if ($request->childcategory == '') {
            $service->childcat_id = '0';
            $service->childcat_name = '';
        } else {
            $service->childcat_id = @$request->childcategory ? $request->childcategory : null;
            $chi_name = DB::table('child_categories')->where('id', $request->childcategory)->first();
            $service->childcat_name = $chi_name->name;
        }

        $service->title = $request->title;
        $service->price = $request->price ?? 0;
        $service->service_type = $request->msval;
        $service->delivery_time = $request->delivery;
        $service->tag = $tag;
        $service->customization = $request->customization;
        $service->description = $request->description;

        // $mainImageFilename = $request->input('main_image_temp');
        // if ($mainImageFilename) {
        //     $tempPath = storage_path('app/public/temp-service-images/'.$mainImageFilename);
        //     $permanentPath = public_path('assets/images/service/'.$mainImageFilename);
        //     if (file_exists($tempPath)) {
        //         rename($tempPath, $permanentPath);
        //         $service->image = $mainImageFilename;
        //     }
        // }

        $mainImageFile = $request->file('image');
        if ($mainImageFile) {
            $filename = time().'_'.$mainImageFile->getClientOriginalName();
            $destinationDir = public_path('assets/images/service');
            File::ensureDirectoryExists($destinationDir);
            $mainImageFile->move($destinationDir, $filename);
            if ($service->image && file_exists(public_path('assets/images/service/'.$service->image))) {
                unlink(public_path('assets/images/service/'.$service->image));
            }
            $service->image = $filename;
        }

        if ($general->approval_post == 1) {
            $service->status = 1;
        }
        $service->priority = $request->priority;
        $insert = $service->save();

        // $optionalImages = $request->input('optional_images_temp', []);
        // if (is_array($optionalImages)) {
        //     foreach ($optionalImages as $imgFile) {
        //         $tempPath = storage_path('app/public/temp-service-images/'.$imgFile);
        //         $permanentPath = public_path('assets/images/optionalService/'.$imgFile);
        //         if (file_exists($tempPath)) {
        //             rename($tempPath, $permanentPath);
        //             $optImg = new OptionalImage;
        //             $optImg->service_id = $service->id;
        //             $optImg->image = $imgFile;
        //             $optImg->save();
        //         }
        //     }
        // }

        $optionalImages = $request->file('optional_image', []);
        if (! empty($optionalImages)) {
            $destinationDir = public_path('assets/images/optionalService');
            File::ensureDirectoryExists($destinationDir);
            foreach ($optionalImages as $imgFile) {
                if (! $imgFile->isValid()) {
                    continue;
                }
                $filename = time().'_'.$imgFile->getClientOriginalName();
                $imgFile->move($destinationDir, $filename);
                OptionalImage::create([
                    'service_id' => $service->id,
                    'image' => $filename,
                ]);
            }
        }

        if (isset($request->skills)) {
            foreach ($request->skills as $skill) {
                $get = Skill::where('id', $skill)->first();
                $psk = new Servicedetailskillparent;
                $psk->service_id = $service->id;
                $psk->user_id = $user->id;
                $psk->parent = $get->skill_category_id;
                $psk->child = $get->name;
                $psk->save();
            }
        }

        if (isset($request->newskills)) {
            // foreach ($request->newskills as $key => $skill) {
            //     $nsk = new Servicedetailskillnewextra;
            //     $nsk->service_id = $service->id;
            //     $nsk->user_id = $user->id;
            //     $nsk->name = $skill;
            //     $nsk->save();
            // }

            foreach ($request->newskills as $skill) {
                Servicedetailskillnewextra::updateOrCreate(
                    [
                        'service_id' => $service->id,
                        'name' => $skill,
                    ],
                    [
                        'user_id' => $user->id,
                        'name' => $skill,
                    ]
                );
            }
        }
        $total_service_cost = 0;
        // insert tiers
        if ($insert) {
            $packageCount = $request->msval == 1 ? 3 : 1;

            for ($i = 1; $i <= $packageCount; $i++) {
                $serviceDetail = new ServicesDetail;
                $serviceDetail->service_id = $service->id;
                $serviceDetail->user_id = $user->id;

                if ($request->msval == 1) {
                    // Multi-package: use indexed fields title1/title2/title3 etc.
                    $serviceDetail->title = $request->input("title{$i}");
                    $serviceDetail->desc = $request->input("desc{$i}");
                    $serviceDetail->price = $request->input("price{$i}");
                    $serviceDetail->delivery_time = $request->input("time{$i}");
                    $serviceDetail->revision = $request->input("revision{$i}");
                } else {
                    // Single-package: always read from the 'sin' fields directly.
                    // Both div#a (multi) and div#b (single) exist in the DOM, so title1/price1 etc.
                    // submit as empty strings — not null — breaking Laravel's input() fallback default.
                    $serviceDetail->title = $request->input('titlesin');
                    $serviceDetail->desc = $request->input('descsin');
                    $serviceDetail->price = $request->input('pricesin');
                    $serviceDetail->delivery_time = $request->input('timesin');
                    $serviceDetail->revision = $request->input('revisionin');
                }
                $serviceDetail->save();

                $total_service_cost += $serviceDetail->price;

                // Handle tier checkbox values.
                // Note: both div#a (multi) and div#b (single) are always in the DOM so
                // tier_id[] gets submitted twice — deduplicate with array_unique().
                if ($request->has('tier_id')) {
                    $uniqueTierIds = array_unique($request->tier_id);

                    $valueKey = $request->msval == 1
                        ? "tier_service_value{$i}"
                        : 'tier_service_value1';

                    $tierValues = $request->input($valueKey, []);

                    foreach ($uniqueTierIds as $tierId) {
                        // Only save when the checkbox was explicitly checked ("yes")
                        if (isset($tierValues[$tierId]) && $tierValues[$tierId] === 'yes') {
                            $tier = new ServiceDetailTier;
                            $tier->service_detail_id = $serviceDetail->id;
                            $tier->service_tier_id   = $tierId;
                            $tier->service_value_tier = 'yes';
                            $tier->save();
                        }
                    }
                }

                if ($request->has('fast_tier') && ! empty($request->fast_tier[$i]) && ! empty($request->fast_tier_time[$i])) {
                    $fastTierId = $request->fast_tier[$i];

                    $addon = new UserServicesAddsOn;
                    $addon->user_id = $user->id;
                    $addon->services_addson_id = $fastTierId;
                    $addon->price = $request->fast_tier_price[$i] ?? 0;
                    $addon->delivery_time = $request->fast_tier_time[$i] ?? 0;
                    $addon->service_id = $service->id;
                    $addon->service_detail_id = $serviceDetail->id ?? null;
                    $addon->save();
                }
            }
            if ($request->has('addon_price')) {
                foreach ($request->addon_price as $tierId => $price) {
                    $delivery = $request->addon_delivery_time[$tierId] ?? null;

                    if (! is_null(value: $price) || ! is_null($delivery)) {
                        $addon = new UserServicesAddsOn;
                        $addon->user_id = $user->id;
                        $addon->services_addson_id = $tierId;
                        $addon->price = $price ?? 0;
                        $addon->delivery_time = $delivery ?? 0;
                        $addon->service_id = $service->id;
                        $addon->service_detail_id = null;
                        $addon->save();

                        $total_service_cost += $serviceDetail->price;
                    }
                }
            }
        }

        $service = Service::find($service->id);
        $service->price = $total_service_cost ?? 0;
        $service->save();
        // insert job skills
        $service_arr = [];
        if (isset($request->skills)) {
            for ($i = 0; $i < count($request->skills); $i++) {
                $service_arr[] = [
                    'skill_id' => $request->skills[$i],
                    'service_id' => $service->id,
                ];
            }
        }

        Serviceskill::insert($service_arr);
        if ($request->has('newskills')) {
            $newskill_arr = [];
            for ($i = 0; $i < count($request->newskills); $i++) {
                $newskill_arr[] = [
                    'term_id' => $service->id,
                    'type' => 1,
                    'name' => $request->newskills[$i],
                ];
            }
            Newskillbyuser::insert($newskill_arr);
        }
        // update user service count accordingly
        $coloum_name = '';
        if ($request->priority == 0) {
            $coloum_name = 'normal_services';
        } elseif ($request->priority == 1) {
            $coloum_name = 'priority_services';
        } elseif ($request->priority == 2) {
            $coloum_name = 'featured_services';
        }
        if ($coloum_name != '') {
            $updateservices = Freelancerservicescount::where('user_id', $user->id)->decrement($coloum_name, 1);
            $getbids = Freelancerservicescount::where('user_id', $user->id)->first();

            $transaction_arr[] = [
                'user_id' => auth()->user()->id,
                'bids' => $updateservices,
                'tr_op' => '-',
                'post_balance' => $getbids->bids - $updateservices,
                'details' => 'Bid on '.$request->title,

            ];
            BidsLog::insert($transaction_arr);
        }

        sendAdminRealTimeNotification(Auth::user(), 'Service Created By '.Auth::user()->username, route('admin.service.pending'));

        // $adminNotification = new AdminNotification();
        // $adminNotification->user_id = $user->id;
        // $adminNotification->title = 'Service Create ' . $service->title;
        // $adminNotification->click_url = urlPath('admin.service.details', $service->id);
        // $adminNotification->save();

        $review = $request->review;
        if (isset($review) && $review != '') {
            return redirect('/user/seller/service-edit/'.$service->id.'/'.$service->title.'?review=true');
            // return redirect('/service/edit/'.$service->id . '/' .$service->title );
        } else {
            $notify[] = ['success', 'Service has been created.'];

            return redirect('/user/seller/service-index')->withNotify($notify);
        }
    }

    public function uploadTempImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $file = $request->file('image');
        $filename = uniqid('temp_').'.'.$file->getClientOriginalExtension();

        $file->storeAs('public/temp-service-images', $filename);

        return response()->json([
            'filename' => $filename,
            'url' => asset('storage/temp-service-images/'.$filename),
        ]);
    }

    public function deleteTempImage(Request $request)
    {
        $filename = $request->input('filename');
        if (! $filename) {
            return response()->json(['error' => 'Filename is required.'], 400);
        }

        // File path (adjust if you used a different folder for temp images)
        $filePath = 'public/temp-service-images/'.$filename;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function edit(Request $req, $id, $slug)
    {
        if ($req->type == 'Jobs') {
            return searchJob($req);
        } else if ($req->type == 'Professionals') {
            return searchProfessionals($req);
        } else if ($req->type == 'Services') {
            return searchService($req);
        }
        $review = isset($req->review) ? 'true' : 'false';
        $pageTitle = $review === 'true' ? 'Review service' : 'Update service';
        $user = Auth::user();
        $features = Features::latest()->get();

        $service = Service::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => ' Edit Service'],
        ];

        $userservices = Freelancerservicescount::where('user_id', $user->id)->get();

        $categoryss = DB::table('categories')
            ->where('type', 'service')
            ->get();
        $isMulti = $service->service_type == 1;

        $existingAddOns = UserServicesAddsOn::where('service_id', $service->id)->get()->groupBy('services_addson_id');

        $tiersCheckbox = DB::table('service_tiers')
            ->where('type', 'checkbox')
            ->where('subcat_id', $service->sub_category_id)
            ->when($service->childcat_id > 0, function ($q) use ($service) {
                $q->where('childcat_id', $service->childcat_id);
            })->get();

        $defaultTiers = DB::table('service_tiers')
            ->where('type', 'checkbox')
            ->where('cat_id', 0)
            ->where('subcat_id', 0)
            ->get();

        $mergedCheckbox = collect($defaultTiers->merge($tiersCheckbox))
            ->map(function ($tier) use ($existingAddOns) {
                $tier = (object) $tier;
                $tier->addons = $existingAddOns->get($tier->id, collect());

                return $tier;
            });

        $serviceDetails = DB::table('services_details')
            ->where('service_id', $service->id)
            ->where('is_active', 1)
            ->orderBy('id')
            ->get();

        // $tierValues = [];
        // foreach ($serviceDetails as $detail) {
        //     $tierValues[$detail->id] = DB::table('service_detail_tiers')
        //         ->join('services_details', 'service_detail_tiers.service_detail_id', '=', 'services_details.id')
        //         ->where('service_detail_id', $detail->id)
        //         ->pluck('service_value_tier', 'service_tier_id')
        //         ->toArray();
        // }

        $tierValues = [];

        foreach ($serviceDetails as $detail) {
            $tierValues[$detail->id] = DB::table('service_detail_tiers')
                ->join('services_details', 'service_detail_tiers.service_detail_id', '=', 'services_details.id')
                ->where('service_detail_id', $detail->id)
                ->pluck('service_value_tier', 'service_tier_id')
                ->toArray();
        }

        $rawKeywords = DB::table('seller_detail_skill_new_extra')
            ->where('service_id', $service->id)
            ->pluck('name')
            ->toArray();

        $ids = array_filter($rawKeywords, fn ($v) => is_numeric($v));
        $names = array_filter($rawKeywords, fn ($v) => ! is_numeric($v));

        $names = array_map(fn ($v) => strtolower(trim($v)), $names);

        $keywords = DB::table('skills')
            ->where(function ($query) use ($ids, $names) {

                if (! empty($ids)) {
                    $query->whereIn('id', $ids);
                }

                if (! empty($names)) {
                    $query->orWhereIn(
                        DB::raw('LOWER(TRIM(name))'),
                        $names
                    );
                }
            })
            ->select('id', 'name')
            ->get();

        return view($this->activeTemplate.'user.seller.service.edit', compact(
            'pageTitle',
            'features',
            'service',
            'userservices',
            'categoryss',
            'review',
            'isMulti',
            'tiersCheckbox',
            'serviceDetails',
            'tierValues',
            'keywords',
            'mergedCheckbox',
            'breadcrumbs',
        ));
    }

    public function update(Request $request, $id)
    {
        $allowedTags = '<b><i><u><a><p><br><ul><ol><li><strong>';
        $plainDescription = strip_tags($request->description);
        if (mb_strlen($plainDescription) > 2000) {
            return back()
                ->withInput()
                ->withErrors(['description' => 'Description must not exceed 2000 characters (excluding formatting).']);
        }
        $request->merge([
            'description' => strip_tags($request->description, $allowedTags),
        ]);

        $rules = [
            'service_title' => 'required|string|max:90',
            'description' => 'required|string|min:10|max:2000',
            'optional_image.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required|integer|exists:categories,id',
            'subcategory' => 'required|integer|exists:sub_categories,id',
            'childcategory' => 'nullable|integer|exists:child_categories,id',
            'priority' => 'required|in:0,1,2',
        ];

        if ($request->msval == 1) {
            for ($i = 1; $i <= 3; $i++) {
                $rules["title.$i"] = 'required|string|max:60';
                $rules["desc.$i"] = 'required|string|max:200';
                $rules["price.$i"] = 'required|numeric|min:1';
                $rules["time.$i"] = 'required|string|min:1';
                $rules["revision.$i"] = 'required';
            }
        } else {
            $rules['titlesin'] = 'required|string|max:60';
            $rules['descsin'] = 'required|string|max:200';
            $rules['pricesin'] = 'required|numeric|min:1';
            $rules['timesin'] = 'required|string|min:1';
            $rules['revisionin'] = 'required';
        }

        $messages = [
            'priority.required' => 'The "Post as a" field is required.',
            'title.*.required' => 'Package title is required.',
            'desc.*.required' => 'Package description is required.',
            'price.*.required' => 'Package price is required.',
            'time.*.required' => 'Delivery time is required.',
            'revision.*.required' => 'Number of revisions is required.',
            'titlesin.required' => 'Service title is required.',
            'descsin.required' => 'Service description is required.',
            'pricesin.required' => 'Service price is required.',
            'timesin.required' => 'Delivery time is required.',
            'revisionin.required' => 'Number of revisions is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $tag_data = [];
        if ($request->filled('skills')) {
            foreach ($request->skills as $skillId) {
                $skill = Skill::find($skillId);
                if ($skill) {
                    $tag_data[] = $skill->name;
                }
            }
        }
        $tag = $tag_data
            ? ($request->newskills ? array_merge($tag_data, $request->newskills) : $tag_data)
            : $request->newskills;

        $user = Auth::user();
        $general = GeneralSetting::first();

        $service = Service::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $service_arr = [];
        if (isset($request->skills)) {
            Serviceskill::where('service_id', $id)->delete();
            for ($i = 0; $i < count($request->skills); $i++) {
                $service_arr[] = [
                    'skill_id' => $request->skills[$i],
                    'service_id' => $service->id,
                ];
            }
        }

        Serviceskill::insert($service_arr);

        $currentPriority = $service->priority;

        $mainImageFile = $request->file('image');
        if ($mainImageFile) {
            $filename = time().'_'.$mainImageFile->getClientOriginalName();
            $destinationDir = public_path('assets/images/service');
            File::ensureDirectoryExists($destinationDir);
            $mainImageFile->move($destinationDir, $filename);
            if ($service->image && file_exists(public_path('assets/images/service/'.$service->image))) {
                unlink(public_path('assets/images/service/'.$service->image));
            }
            $service->image = $filename;
        }

        $service->category_id = $request->category;
        $service->cat_name = DB::table('categories')->find($request->category)->name;
        $service->sub_category_id = $request->subcategory;
        $service->subcat_name = DB::table('sub_categories')->find($request->subcategory)->name;
        $service->childcat_id = $request->childcategory ?: 0;
        $service->childcat_name = $request->childcategory
            ? DB::table('child_categories')->find($request->childcategory)->name
            : '';
        $service->title = $request->service_title;
        $service->description = $request->description;
        $service->service_type = $request->msval ? 1 : 0;
        $service->tag = $tag;
        $service->customization = $request->customization;
        $service->priority = $request->priority;
        $service->status = $general->approval_post ? 1 : 0;

        $is_updated = $service->save();

        if (isset($request->skills)) {
            Servicedetailskillparent::where('service_id', $service->id)->delete();

            foreach ($request->skills as $skill) {
                $get = Skill::where('id', $skill)->first();
                if ($get) {
                    $psk = new Servicedetailskillparent;
                    $psk->service_id = $service->id;
                    $psk->user_id = $user->id;
                    $psk->parent = $get->skill_category_id;
                    $psk->child = $get->name;
                    $psk->save();
                }
            }
        }

        if (isset($request->newskills)) {
            Servicedetailskillnewextra::where('service_id', $service->id)->delete();

            foreach ($request->newskills as $skill) {
                $nsk = new Servicedetailskillnewextra;
                $nsk->service_id = $service->id;
                $nsk->user_id = $user->id;
                $nsk->name = $skill;
                $nsk->save();
            }
        }
        $total_service_cost = 0;
        if ($is_updated) {
            $detailIds = ServicesDetail::where('service_id', $service->id)->where('is_active', 1)->pluck('id')->toArray();
            ServiceDetailTier::whereIn('service_detail_id', $detailIds)->delete();
            ServicesDetail::where('service_id', $service->id)->update(['is_active' => 0]);
            UserServicesAddsOn::where('service_id', $service->id)->delete();

            if ($request->msval == 1) {
                for ($i = 1; $i <= 3; $i++) {
                    $serviceDetail = new ServicesDetail;
                    $serviceDetail->service_id = $service->id;
                    $serviceDetail->user_id = $user->id;
                    $serviceDetail->title = $request->input("title.$i", '');
                    $serviceDetail->desc = $request->input("desc.$i", '');
                    $serviceDetail->price = $request->input("price.$i", 0);
                    $serviceDetail->delivery_time = $request->input("time.$i", '');
                    $serviceDetail->revision = $request->input("revision.$i", '');
                    $serviceDetail->save();

                    $total_service_cost += $serviceDetail->price;

                    if ($request->has('tier_service_value')) {
                        if ($request->has('detail_id') && isset($request->detail_id[$i - 1])) {
                            $originalDetailId = $request->detail_id[$i - 1];
                            if (isset($request->tier_service_value[$originalDetailId])) {
                                foreach ($request->tier_service_value[$originalDetailId] as $tierId => $value) {
                                    if ($value == 'yes') {
                                        ServiceDetailTier::create([
                                            'service_detail_id' => $serviceDetail->id,
                                            'service_tier_id' => $tierId,
                                            'service_value_tier' => $value,
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    // if ($request->has('fast_tier') && ! empty($request->fast_tier[$i]) && ! empty($request->fast_tier_time[$i])) {
                    //     UserServicesAddsOn::create([
                    //         'user_id' => $user->id,
                    //         'service_id' => $service->id,
                    //         'service_detail_id' => $serviceDetail->id,
                    //         'services_addson_id' => $request->fast_tier[$i],
                    //         'price' => $request->input("fast_tier_price.$i", 0),
                    //         'delivery_time' => $request->input("fast_tier_time.$i", 0),
                    //     ]);
                    // }
                    if ($request->has('fast_tier') && ! empty($request->fast_tier[$i]) && ! empty($request->fast_tier_time[$i])) {
                        $fastTierId = $request->fast_tier[$i];

                        $addon = new UserServicesAddsOn;
                        $addon->user_id = $user->id;
                        $addon->services_addson_id = $fastTierId;
                        $addon->price = $request->fast_tier_price[$i] ?? 0;
                        $addon->delivery_time = $request->fast_tier_time[$i] ?? 0;
                        $addon->service_id = $service->id;
                        $addon->service_detail_id = $serviceDetail->id ?? null;
                        $addon->save();
                    }
                }
            } else {
                $serviceDetail = new ServicesDetail;
                $serviceDetail->service_id = $service->id;
                $serviceDetail->user_id = $user->id;
                $serviceDetail->title = $request->input('titlesin', '');
                $serviceDetail->desc = $request->input('descsin', '');
                $serviceDetail->price = $request->input('pricesin', 0);
                $serviceDetail->delivery_time = $request->input('timesin', '');
                $serviceDetail->revision = $request->input('revisionin', '');
                $serviceDetail->save();

                $total_service_cost += $serviceDetail->price;

                if ($request->has('tier_service_value1')) {
                    foreach ($request->tier_service_value1 as $tierId => $value) {
                        if ($value == 'yes') {
                            ServiceDetailTier::create([
                                'service_detail_id' => $serviceDetail->id,
                                'service_tier_id' => $tierId,
                                'service_value_tier' => $value,
                            ]);
                        }
                    }
                }


                if ($request->has('fast_tier') && isset($request->fast_tier[1]) && ! empty($request->fast_tier_time[1])) {

                    UserServicesAddsOn::create([
                        'user_id' => $user->id,
                        'service_id' => $service->id,
                        'service_detail_id' => $serviceDetail->id,
                        'services_addson_id' => $request->fast_tier[1],
                        'price' => $request->input('fast_tier_price.1', 0),
                        'delivery_time' => $request->input('fast_tier_time.1', 0),
                    ]);
                }
            }

            if ($request->has('addon_price')) {
                // foreach ($request->addon_price as $tierId => $priceArr) {
                //     $price = $priceArr[0] ?? null;
                //     $delivery = $request->addon_delivery_time[$tierId][0] ?? null;

                //     if (($price !== null && $price !== '') || ($delivery !== null && $delivery !== '')) {
                //         UserServicesAddsOn::create([
                //             'user_id' => $user->id,
                //             'service_id' => $service->id,
                //             'service_detail_id' => null,
                //             'services_addson_id' => $tierId,
                //             'price' => $price ?? 0,
                //             'delivery_time' => $delivery ?? 0,
                //         ]);
                //     }
                // }

                foreach ($request->addon_price as $tierId => $priceArr) {

                    foreach ($priceArr as $index => $price) {

                        $delivery = $request->addon_delivery_time[$tierId][$index] ?? null;
                        if (
                            ($price !== null && $price !== '') ||
                            ($delivery !== null && $delivery !== '')
                        ) {
                            UserServicesAddsOn::create([
                                'user_id' => $user->id,
                                'service_id' => $service->id,
                                'service_detail_id' => null,
                                'services_addson_id' => $tierId,
                                'price' => $price ?? 0,
                                'delivery_time' => $delivery ?? 0,
                            ]);
                        }
                    }
                }
            }
        }

        $service = Service::find($service->id);
        $service->price = $total_service_cost ?? 0;
        $service->save();

        if ($currentPriority != $request->priority) {
            $column_name = '';
            if ($currentPriority == 0) {
                $column_name = 'normal_services';
            } elseif ($currentPriority == 1) {
                $column_name = 'priority_services';
            } elseif ($currentPriority == 2) {
                $column_name = 'featured_services';
            }

            if ($column_name != '') {
                Freelancerservicescount::where('user_id', $user->id)->decrement($column_name, 1);
            }

            $new_column_name = '';
            if ($request->priority == 0) {
                $new_column_name = 'normal_services';
            } elseif ($request->priority == 1) {
                $new_column_name = 'priority_services';
            } elseif ($request->priority == 2) {
                $new_column_name = 'featured_services';
            }

            if ($new_column_name != '') {
                Freelancerservicescount::where('user_id', $user->id)->increment($new_column_name, 1);

                $getbids = Freelancerservicescount::where('user_id', $user->id)->first();
                if ($getbids) {
                    BidsLog::create([
                        'user_id' => $user->id,
                        'bids' => 1,
                        'tr_op' => '-',
                        'post_balance' => $getbids->bids - 1,
                        'details' => 'Updated service: '.$request->service_title.' (priority changed)',
                    ]);
                }
            }
        }

        if ($request->deleted_optional_images && !is_null($request->deleted_optional_images)) {
            $deletedIds = explode(',', $request->deleted_optional_images);
            foreach ($deletedIds as $imageId) {
                $optionalImage = OptionalImage::find($imageId);
                Log::info('Attempting to delete optional image with ID: '.$imageId);
                if ($optionalImage && $optionalImage->service_id == $service->id) {
                    if (file_exists(public_path('assets/images/optionalService/'.$optionalImage->image))) {
                        unlink(public_path('assets/images/optionalService/'.$optionalImage->image));
                    }
                    $optionalImage->delete();
                }
            }
        }

        $optionalImages = $request->file('optional_image', []);
        if (! empty($optionalImages)) {
            $destinationDir = public_path('assets/images/optionalService');
            File::ensureDirectoryExists($destinationDir);
            foreach ($optionalImages as $imgFile) {
                if (! $imgFile->isValid()) {
                    continue;
                }
                $filename = time().'_'.$imgFile->getClientOriginalName();
                $imgFile->move($destinationDir, $filename);
                OptionalImage::create([
                    'service_id' => $service->id,
                    'image' => $filename,
                ]);
            }
        }

        // Create admin notification
        $adminNotification = new AdminNotification;
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'Service updated: '.$service->title;
        $adminNotification->click_url = urlPath('admin.service.details', $service->id);
        $adminNotification->save();

        $notify[] = ['success', 'Service has been updated successfully.'];

        return redirect('/user/seller/service-index')->withNotify($notify);
    }

    public function optionalImageRemove(Request $request)
    {
        $ids = [];

        if ($request->has('id')) {
            $ids[] = $request->id;
        }

        if ($request->has('deleted_optional_images')) {
            $multiple = json_decode($request->deleted_optional_images, true);
            if (is_array($multiple)) {
                $ids = array_merge($ids, $multiple);
            }
        }

        foreach ($ids as $id) {
            $img = DB::table('optional_images')->where('id', $id)->first();

            if ($img) {
                $imagePath = public_path('assets/images/optionalService/'.$img->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                DB::table('optional_images')->where('id', $id)->delete();
            }
        }
    }

    private function optionalImageStore($request, $optionalImage, $serviceId)
    {
        foreach ($optionalImage as $optional) {

            $optionals = new OptionalImage;
            $optionals->service_id = $serviceId;
            $path = imagePath()['optionalService']['path'];
            $size = imagePath()['optionalService']['size'];
            if ($request->hasFile('optional_image')) {

                $file = $optional;
                try {
                    $filename = uploadImage($file, $path, $size);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Image could not be uploaded.'];

                    return back()->withNotify($notify);
                }
                $optionals->image = $filename;
            }
            $optionals->save();
        }
    }

    public function getServiceSkills(Request $request)
    {
        $skill = Skill::where('skill_category_id', $request->c_id)
            ->get()
            ->pluck('id', 'name', 'skill_category_id')
            ->toArray();
        if (count($skill) == 0) {
            return response()->json(['error' => '0']);
        } else {
            return response()->json($skill);
        }
    }

    public function getServiceSkillsByName(Request $request)
    {
        $skill = Skill::where(Skill::raw('lower(name)'), 'LIKE', $request->search.'%')
            ->get()
            ->pluck('id', 'name')
            ->toArray();
        if (count($skill) == 0) {
            return response()->json(['error' => '0']);
        } else {

            return response()->json($skill);
        }
    }

    public function serviceSkillCategory(Request $request)
    {
        $skill_category = SkillCategory::where('sub_category_id', $request->subcategory)->get()->pluck('id', 'name')->toArray();
        if (count($skill_category) == 0) {
            return response()->json(['error' => 'Sub category not available under this category']);
        } else {
            return response()->json($skill_category);
        }
    }

    public function serviceCategory(Request $request)
    {
        $sub_category = SubCategory::where('category_id', $request->category)->get();
        if ($sub_category->isEmpty()) {
            return response()->json(['error' => 'Sub category not available under this category']);
        } else {
            return response()->json($sub_category);
        }
    }

    public function serviceSubCategory(Request $request)
    {
        $child_category = ChildCategories::where('subcat_id', $request->subcategory)->get();
        if ($child_category->isEmpty()) {
            return response()->json(['error' => 'Child category not available under this category']);
        } else {
            return response()->json($child_category);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $service_details = ServicesDetail::with('tierValues')->where('service_id', $id)->get();
            foreach ($service_details as $service_detail) {
                $service_detail->tierValues()->delete();
                $service_detail->delete();
            }
            $service = Service::findOrFail($id);

            Serviceskill::where('service_id', $id)->delete();
            UserServicesAddsOn::where('service_id', $id)->delete();
            SellerDetailSkillNewExtra::where('service_id', $id)->delete();
            SellerDetailSkillParent::where('service_id', $id)->delete();
            OptionalImage::where('service_id', $id)->delete();

            $service->delete();
            DB::commit();
            $notify[] = ['success', 'Service has been deleted.'];

            return redirect()->back()->withNotify($notify);
        } catch (Exception $ex) {
            DB::rollBack();
            $notify[] = ['error', $ex->getMessage()];

            return redirect('/user/seller/services-index')->withNotify($notify);
        }
    }

    public function orderNow($service_id)
    {
        $user = Auth::user();
        $service_id = decrypt($service_id);

        return view($this->activeTemplate.'ServiceOrderNow', compact('user'));
    }

    public function orderNowPayment($service_id)
    {
        $user = Auth::user();
        $service_id = decrypt($service_id);

        return view($this->activeTemplate.'ServiceOrderPayment', compact('user'));
    }

    public function serviceBookeds()
    {
        $user = Auth::user();
        $pageTitle = 'Service booking list';
        $emptyMessage = 'No data found';
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })->with('seller')->latest()->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Service Booking'],
        ];

        return view($this->activeTemplate.'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function serviceBookingConfirm(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'confirm' => 'required|in:approved,cancel',
        ]);
        $user = Auth::user();
        $general = GeneralSetting::first();
        $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        if (! $booking) {
            $notify[] = ['error', 'Invalid service booking'];

            return back()->withNotify($notify);
        }
        if ($request->confirm == 'approved') {
            $booking->working_status = 4;
            $booking->status = 5;
            $booking->updated_at = Carbon::now();
            $booking->save();

            sendJobEmailWithNotification(
                auth()->id(),                   // sender_id (probably current user)
                $booking->seller_id,           // reciever_id (the seller)
                $booking->service_id,          // job_id (here it's the service)
                'SERVICE_BOOKING_APPROVED',    // notification_type (customize this identifier as needed)
                false,                         // is_internal
                null,                          // bid_id (null if not applicable)
                $booking->id,                  // booking_id (if your function uses it)
                null,                          // custom_data or extra param
                null                           // offer_id or any optional id
            );

            $notify[] = ['success', 'Thanks For Receiving the booking'];

            return back()->withNotify($notify);
        } elseif ($request->confirm == 'cancel') {
            $booking->status = 4;
            $booking->working_status = 3;
            $booking->save();

            $user = User::where('id', $booking->user_id)->firstOrFail();
            $user->balance += $booking->amount;
            $user->save();

            $transaction = new Transaction;
            $transaction->user_id = $user->id;
            $transaction->amount = $booking->amount;
            $transaction->post_balance = $user->balance;
            $transaction->trx_type = '+';
            $transaction->trx = $booking->order_number;
            $transaction->details = 'Refund Money '.$booking->order_number;
            $transaction->save();

            // send email
            notify($user, 'MONEY_REFUND', [
                'amount' => getAmount($booking->amount),
                'currency' => $general->cur_text,
                'order_number' => $booking->order_number,
                'post_balance' => getAmount($user->balance),
            ]);
            $notify[] = ['success', 'Refund money.'];

            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Invalid booking'];

            return back()->withNotify($notify);
        }
    }

    public function workServiceDeliveryApproved(Request $request, $orderNumber)
    {
        $request->merge(['order_number' => $orderNumber]);

        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'dispute_amount' => 'required|numeric',
            'buyer_amount' => 'required|numeric',
            'seller_amount' => 'required|numeric',
            'reason' => 'required|string',
            'message' => 'nullable|string',
            'file' => 'nullable|file|mimes:zip,jpg,jpeg,png,pdf', // adjust file types as needed
        ]);

        $dispute_amount = $request->dispute_amount;
        $buyer_amount = $request->buyer_amount;
        $seller_amount = $request->seller_amount;

        if (($buyer_amount + $seller_amount) != $dispute_amount) {
            return back()->withNotify([['error', 'Offered amount must equal the dispute amount']]);
        }

        $booking = Booking::where('order_number', $orderNumber)->firstOrFail();
        $service = Service::findOrFail($booking->service_id);

        $dispute = new Servicedisputedata([
            'booking_id' => $booking->id,
            'service_id' => $booking->service_id,
            'seller_id' => $service->user_id,
            'buyer_id' => $booking->buyer_id,
            'order_number' => $orderNumber,
            'reason' => $request->reason,
            'disputed_amount' => $dispute_amount,
            'buyer_amount' => $buyer_amount,
            'seller_amount' => $seller_amount,
            'message' => $request->message,
            'status' => '0',
            'send_by' => 'buyer',
        ]);

        // Handle file upload if present
        if ($request->hasFile('file')) {
            try {
                $filename = uploadFile($request->file, imagePath()['workFile']['path']);
                $dispute->file = $filename;
            } catch (\Exception $exp) {
                return back()->withNotify([['error', 'File could not be uploaded.']]);
            }
        }

        $dispute->save();

        // Save dispute offer details
        $disputeOffer = new Servicedisputeoffers([
            'dispute_id' => $dispute->id,
            'disputed_amount' => $dispute_amount,
            'buyer_amount' => $buyer_amount,
            'seller_amount' => $seller_amount,
            'message' => $request->message,
            'status' => '0',
            'send_by' => 'buyer',
        ]);
        $disputeOffer->save();

        // Update booking status
        $booking->update([
            'status' => '8',
            'working_status' => '6',
        ]);

        // Send notification email inside try-catch
        try {
            sendJobEmailWithNotification(
                senderId: $booking->buyer_id,
                receiverId: $booking->seller_id,
                jobId: $booking->service_id,
                type: 'DISPUTE_CREATED',
                admin: false,
                userBiddingId: null,
                transactionId: null,
                transactionAmount: null
            );
        } catch (Exception $emailException) {
            Log::error('Email failed to send: '.$emailException->getMessage());
        }

        return back()->withNotify([['success', 'Work has been successfully disputed']]);
    }

    // Services
    public function service(Request $request)
    {
         if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        }

        $pageTitle = 'Service';
        $emptyMessage = 'No data found';

        // Get categories of type service
        $categories = Category::where('type', 'service')->get();

        // Ranks for all levels
        $ranks = Rank::whereIn('level', [1, 2, 3, 4, 5])->get()->keyBy('level');

        // Currency logic
        $helpers = new Helpers;
        $currency = Session::get('currency', 'USD');
        $currencySymbol = Session::get('currency_symbol', '$');
        $currency_rates = $helpers->get_currency_rates();

        // Build query with optional search filters
        $servicesQuery = Service::query()
            ->where('services.status', 1)
            ->whereHas('user')
            ->whereHas('category', function ($q) {
                $q->where('categories.status', 1);
            })
            ->with(['user', 'user.rank', 'category', 'details']);

        // Search by title
        $keyword = $request->filled('title') ? $request->title : $request->search;
        if (filled($keyword)) {
            $servicesQuery->where(function ($q) use ($keyword) {
                $q->where('services.title', 'like', '%'.$keyword.'%')
                    ->orWhere('services.tag', 'like', '%'.$keyword.'%');
            });
        }

        // Search by location
        if ($request->filled('location')) {
            $location = strtolower($request->location);
            $servicesQuery->whereHas('user', function ($q) use ($location) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(address, '$.country'))) LIKE ?", ['%'.$location.'%']);
            });
        }

        // Sorting
        $sort = $request->get('sort');

        if ($sort == 'date') {
            $servicesQuery->orderBy('services.created_at', 'desc');
        } elseif ($sort == 'price_asc' || $sort == 'price_desc') {
            // Join with service details to sort by price
            $servicesQuery->join('services_details', function ($join) {
                $join->on('services.id', '=', 'services_details.service_id')
                    ->where('services_details.is_active', 1); // only active
            })
            ->select('services.*') // make sure we only select service columns
            ->distinct();

            if ($sort == 'price_asc') {
                $servicesQuery->orderBy('services_details.price', 'asc');
            } else {
                $servicesQuery->orderBy('services_details.price', 'desc');
            }
        } else {
            $servicesQuery->inRandomOrder();
        }

        // Paginated services
        $services = $servicesQuery->paginate(getPaginate())->appends($request->all());

        // Process services with ratings, price, etc.
        $processedServices = $this->processServices($services, $ranks, $helpers, $currency, $currencySymbol);

        // Professional count
        $professionalCount = Service::where('status', 1)
            ->whereHas('user')
            ->distinct('user_id')
            ->count('user_id');

        $user = Auth::user();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
        $languages = Language::all(['id', 'name', 'code']);

        return view($this->activeTemplate.'service', compact(
            'pageTitle',
            'emptyMessage',
            'categories',
            'professionalCount',
            'currencySymbol',
            'processedServices',
            'services',
            'user',
            'countries',
            'languages'
        ));
    }

    private function processServices($services, $ranks, $helpers, $currency, $currencySymbol)
    {
        return $services->map(function ($service) use ($ranks, $helpers, $currency) {
            // Calculate average rating
            $avgRating = DB::table('review_ratings')
                ->where('service_id', $service->id)
                ->avg('rating') ?? 0;

            $totalReviews = DB::table('review_ratings')
                ->where('service_id', $service->id)
                ->count();

            // Initialize default values
            $price = 0;
            $deliveryTime = 0;

            // Safely get service details
            if ($service->relationLoaded('details') && $service->details) {
                $details = $service->details;

                // Handle different return types
                if ($details instanceof \Illuminate\Database\Eloquent\Collection) {
                    $firstDetail = $details->where('is_active', 1)->first();
                    if ($firstDetail) {
                        $price = $firstDetail->price ?? 0;
                        $deliveryTime = $firstDetail->delivery_time ?? 0;
                    }
                } elseif (is_object($details)) {
                    $price = $details->price ?? 0;
                    $deliveryTime = $details->delivery_time ?? 0;
                }
            }

            // Convert price if helper exists and price is greater than 0
            if ($price > 0 && $helpers) {
                $price = $helpers->convert_to_currency($currency, $price);
            }

            // Calculate rank progress based on user income
            $income = 0;
            if ($service->relationLoaded('user') && $service->user) {
                $income = $service->user->income ?? 0;
            }

            $progress = $this->calculateRankProgress($income, $ranks);

            return (object) [
                'service' => $service,
                'avg_rating' => round($avgRating, 1),
                'total_reviews' => $totalReviews,
                'price' => $price,
                'delivery_time' => $deliveryTime,
                'progress' => $progress,
            ];
        });
    }

    /**
     * Calculate rank progress percentage
     */
    private function calculateRankProgress($income, $ranks)
    {
        $rankProgress = [
            1 => 20,
            2 => 40,
            3 => 60,
            4 => 80,
            5 => 100,
        ];

        foreach (array_reverse(range(1, 5)) as $level) {
            if (! empty($ranks[$level]) && $income >= $ranks[$level]->amount) {
                return $rankProgress[$level];
            }
        }

        return 0;
    }

    // Search Filters
    public function searchFilterServices(Request $request)
    {
        $return_array = [];

        if ($request->all()) {
            $postData = $request->all();

            // Get filtered services
            $services = $this->filterServices($postData);

            // Get ranks for progress calculation
            $ranks = Rank::whereIn('level', [1, 2, 3, 4, 5])->get()->keyBy('level');

            // Currency logic
            $helpers = new Helpers;
            $currency = Session::get('currency', 'USD');
            $currencySymbol = Session::get('currency_symbol', '$');

            // Process services
            $processedServices = $this->processServices($services, $ranks, $helpers, $currency, $currencySymbol);

            $return_array['status'] = 'true';
            $return_array['content_data'] = view('service_filtered', [
                'processedServices' => $processedServices,
                'currencySymbol' => $currencySymbol,
            ])->render();
            $return_array['count'] = $processedServices->count();

        } else {
            $return_array['status'] = 'false';
            $return_array['content_data'] = '';
            $return_array['count'] = 0;
        }

        return response()->json($return_array);
    }

    private function filterServices($postData)
    {
        $query = Service::where('status', 1)
            ->whereHas('user')
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })
            ->with(['user', 'user.rank', 'category', 'details']);

        if (! empty($postData['category'])) {
            $categories = is_array($postData['category']) ? $postData['category'] : [$postData['category']];
            $query->whereIn('category_id', $categories);
        }

        if (! empty($postData['subcategory'])) {
            $subcategories = is_array($postData['subcategory']) ? $postData['subcategory'] : [$postData['subcategory']];
            $query->whereIn('sub_category_id', $subcategories);
        }

        if (! empty($postData['keyword'])) {
            $keyword = $postData['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('tag', 'LIKE', '%'.$keyword.'%');
            });
        }

        if (! empty($postData['minbudget']) && is_numeric($postData['minbudget'])) {
            $query->where('price', '>=', $postData['minbudget']);
        }

        if (! empty($postData['maxbudget']) && is_numeric($postData['maxbudget'])) {
            $query->where('price', '<=', $postData['maxbudget']);
        }

        if (! empty($postData['location'])) {
            $locations = is_array($postData['location']) ? $postData['location'] : [$postData['location']];
            $query->whereHas('user', function ($q) use ($locations) {
                $q->where(function ($subQuery) use ($locations) {
                    foreach ($locations as $location) {
                        $subQuery->orWhere('address->country', 'LIKE', '%'.$location.'%');
                    }
                });
            });
        }

        if (! empty($postData['skills'])) {
            $skills = is_array($postData['skills']) ? $postData['skills'] : [$postData['skills']];
            $query->whereHas('user.skills', function ($q) use ($skills) {
                $q->whereIn('user_profile_skills.skill_id', $skills);
            });
        }

        if (! empty($postData['lang_level'])) {
            $langLevels = is_array($postData['lang_level']) ? $postData['lang_level'] : [$postData['lang_level']];
            $query->whereHas('user', function ($q) use ($langLevels) {

                $lowerLevels = array_map('strtolower', $langLevels);

                $q->whereIn(
                    DB::raw('LOWER(level)'),
                    $lowerLevels
                );
            });
        }

        if (! empty($postData['otherlang'])) {
            $otherLangs = (array) $postData['otherlang'];
            $query->whereHas('user.languages', function ($q) use ($otherLangs) {
                $q->whereIn('name', $otherLangs);
            });
        }

        // working on it
        // if (!empty($postData['exp'])) {
        //     $expLevels = is_array($postData['exp']) ? $postData['exp'] : [$postData['exp']];
        //     $query->whereHas('user', function($q) use ($expLevels) {
        //         $q->whereIn('level', $expLevels);
        //     });
        // }

        if (! empty($postData['delivery_time']) && $postData['delivery_time'] > 0) {
            $query->whereHas('details', function ($q) use ($postData) {
                $q->where('delivery_time', '<=', $postData['delivery_time']);
            });
        }

        if (! empty($postData['sort'])) {
            switch ($postData['sort']) {
                case 'date':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy(
                        ServicesDetail::select('price')
                            ->whereColumn('service_id', 'services.id')
                            ->limit(1),
                        'asc'
                    );
                    break;
                case 'price_desc':
                    $query->orderBy(
                        ServicesDetail::select('price')
                            ->whereColumn('service_id', 'services.id')
                            ->limit(1),
                        'desc'
                    );
                    break;
                default:
                    $query->inRandomOrder();
            }
        } else {
            $query->inRandomOrder();
        }

        return $query->get();
    }
}
