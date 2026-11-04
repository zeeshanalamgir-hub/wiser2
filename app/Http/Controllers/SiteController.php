<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\Chat;
use App\Models\Rank;
use App\Models\User;
use App\Models\Skill;
use App\Models\Escrow;
use App\Models\BidsLog;
use App\Models\Booking;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Service;
use App\Models\Category;
use App\Models\Features;
use App\Models\Frontend;
use App\Models\Jobskill;
use App\Models\Language;
use App\Models\Software;
use App\Models\Advertise;
use App\Models\JobBiding;
use App\Models\UserOffer;
use App\Models\Subscriber;
use App\Models\Userbiding;
use App\Models\Acceptedbid;
use App\Models\SubCategory;
use App\Models\UserCompany;
use Illuminate\Support\Str;
use App\Models\Conversation;
use App\Models\Notification;
use App\Models\ReviewRating;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use App\Models\Acceptedoffer;
use App\Models\SupportTicket;
use App\Models\UserEducation;
use App\Models\UserMilestone;
use App\Models\ServicesDetail;
use App\Models\SupportMessage;
use App\Models\Usermembership;
use App\Models\ChildCategories;
use App\Rules\FileTypeValidate;
use App\Services\EscrowService;
use App\Models\UserProfileSkill;
use App\Services\UserBidService;
use App\Events\NotificationSeven;
use App\Http\Controllers\Gateway\Stripe\ProcessController;
use App\Models\AdminNotification;
use App\Models\Buyercounteroffer;
use App\Models\SupportAttachment;
use App\Models\UserCertification;
use App\Models\UserProfileAlerts;
use App\Models\Jobworkdeliverybid;
use App\Models\UserOfferMilestone;
use Illuminate\Support\Facades\DB;
use App\Models\UserProfileNewSkill;
use App\Services\MembershipService;
use Illuminate\Support\Facades\Log;
use App\Models\Freelancermembership;
use App\Models\UserProfilePortfolio;
use Illuminate\Support\Facades\Auth;
use App\Models\BuyercounterofferData;
use App\Models\Employerservicescount;
use Illuminate\Support\Facades\Crypt;
use App\Models\Freelancerservicescount;
use App\Models\Employermembershiptransactions;
use App\Models\Freelancermembershiptransactions;
use App\Models\StripeResponse;


class SiteController extends Controller
{
    public function __construct(
        protected MembershipService $membershipService,
        protected UserBidService $userBidService,
        protected EscrowService $escrowService
    ) {
        $this->activeTemplate = activeTemplate();
        $this->escrowService = $escrowService;
    }
    public function selling_profile_update(Request $request, $id)
    {
        $postData = $request->all();

        // echo"<pre>"; print_r($postData);exit;
        $user = Auth::user();
        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['designation'] = $request->designation;
        $in['about_me'] = $request->about_me;
        $in['per_hour_rate'] = $request->rate;
        $in['level'] = $request->level;
        $in['cat_id'] = $request->category;
        $in['subcat_id'] = $request->subcategory;
        $in['childcat_id'] = $request->childcategory;
        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $wallpaper_image = $request->file('image');
            $fileName = time() . '-' . $wallpaper_image->getClientOriginalName();
            $wallpaper_image->move('assets/images/user/profile/', $fileName);
            $in['image'] = $fileName;
        }

        if ($request->hasFile('bg_image')) {
            $wallpaper_image = $request->file('bg_image');
            $fileName = time() . '-' . $wallpaper_image->getClientOriginalName();
            $wallpaper_image->move('assets/images/user/profile/', $fileName);
            $in['bg_image'] = $fileName;
        }
        $e = $user->fill($in)->save();




        if ($e) {
            $del = UserLanguage::where('user_id', $id)->delete();
            if ($del) {
                $langname = $request->langname;
                $langtype = $request->langvalue;
                foreach ($langtype as $key => $ds) {
                    $lan = new UserLanguage();
                    $lan->name = $langname[$key];
                    $lan->type = $langtype[$key];
                    $lan->user_id = $id;
                    if ($lan->name != '') {
                        $lan->save();
                    }
                }
            }



            $coun = $request->countrycollege;
            $col = $request->namecollege;
            $deg = $request->namedegree;
            $yeard = $request->yeardegree;
            $files = $request->file('degrecertificate');
            $showdeg = $request->showdegree;

            if (isset($files)) {
                foreach ($files as $key => $file) {
                    $edu = new UserEducation();
                    $edu->countryinstitue = $coun[$key];
                    $edu->institue = $col[$key];
                    $edu->degree = $deg[$key];
                    $edu->yeardegree = $yeard[$key];
                    $showdeg = $request->showdegree;
                    $edu->user_id = $id;
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileName = str::random(5) . "-" . date('his') . "-" . rand(10, 10000) . "." . $extension;
                    $destinationPath = 'assets/wiserlance/assets/img/education' . '/';
                    $file->move($destinationPath, $fileName);
                    $edu->degrecertificate = $fileName;
                    if ($edu->countryinstitue != '') {
                        $edu->save();
                    }
                }
            }
        }
        $ins = $request->nameinstitue;
        $cern = $request->namecertificate;
        $yearc = $request->yearcertificate;
        $showcerti = $request->showcerti;

        $files = $request->file('certificate');
        if (isset($files)) {
            foreach ($files as $key => $file) {
                $cer = new UserCertification();
                $cer->nameinstitue = $ins[$key];
                $cer->namecertificate = $cern[$key];
                $cer->yearcertificate = $yearc[$key];
                $cer->showcertificate = @$showcerti[$key];
                $cer->user_id = $id;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = str::random(5) . "-" . date('his') . "-" . rand(10, 10000) . "." . $extension;
                $destinationPath = 'assets/wiserlance/assets/img/certification' . '/';
                $file->move($destinationPath, $fileName);
                $cer->certificate = $fileName;

                if ($cer->nameinstitue != '') {
                    $cer->save();
                }
            }
        }
        $asd = UserProfileSkill::where('user_id', $id)->delete();
        $service_arr = [];
        if (isset($request->skills)) {
            for ($i = 0; $i < count($request->skills); $i++) {
                $service_arr[] = [
                    'skill_id' => $request->skills[$i],
                    'user_id' => $user->id
                ];
            }
        }
        UserProfileSkill::insert($service_arr);


        $asd = UserProfileNewSkill::where('user_id', $id)->delete();
        // if($asd){
        if ($request->has('newskills')) {
            $newskill_arr = [];
            for ($i = 0; $i < count($request->newskills); $i++) {
                $newskill_arr[] = [
                    'user_id' => $user->id,
                    'name' => $request->newskills[$i],
                ];
            }
            UserProfileNewSkill::insert($newskill_arr);
        }
        // }



        $ptitle = $request->ptitle;
        $pdesc = $request->pdesc;
        // $asd=UserProfilePortfolio::where('user_id',$id)->delete();
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $file) {
                $img = new UserProfilePortfolio();
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = str::random(5) . "-" . date('his') . "-" . rand(10, 10000) . "." . $extension;
                $destinationPath = 'assets/wiserlance/assets/img/portfolio' . '/';
                $file->move($destinationPath, $fileName);
                $img->title = @$ptitle[$key];
                $img->description = @$pdesc[$key];
                $img->user_id = $user->id;
                $img->image = $fileName;

                $img->save();
            }
        }

        $alert = UserProfileAlerts::where('id', $request->notiid)->first();
        // $alert->proposalrecieved = $request->proposalrecieved;
        //    $alert->jobposted = $request->jobposted;
        //    $alert->offermodified = $request->offermodified;
        //    $alert->jobpostexpiresoon = $request->jobpostexpiresoon;
        //    $alert->jobpostexpired = $request->jobpostexpired;
        //    $alert->contractstart = $request->contractstart;
        //    $alert->contractmodify = $request->contractmodify;
        //    $alert->contractend = $request->contractend;
        //    $alert->offerdecline = $request->offerdecline;
        //    $alert->offeraccept = $request->offeraccept;
        $alert->servicebooked_seller = $request->servicebooked_seller;
        $alert->serviceworkyes_seller = $request->serviceworkyes_seller;
        $alert->serviceworkno_seller = $request->serviceworkno_seller;
        $alert->servicebookdispute_seller = $request->servicebookdispute_seller;
        $alert->servicebookdisputeoffer_seller = $request->servicebookdisputeoffer_seller;
        $alert->servicebookdisputeofferyes_seller = $request->servicebookdisputeofferyes_seller;
        $alert->servicebookdisputeofferno_seller = $request->servicebookdisputeofferno_seller;
        $alert->servicebookdisputeadmin_seller = $request->servicebookdisputeadmin_seller;
        $alert->servicebookrr_seller = $request->servicebookrr_seller;
        $alert->jobdisputeadmin_seller = @$request->jobdisputeadmin_seller;
        $alert->jobdisputeofferno_seller = $request->jobdisputeofferno_seller;
        $alert->jobdisputeofferyes_seller = $request->jobdisputeofferyes_seller;
        $alert->jobdisputeoffer_seller = $request->jobdisputeoffer_seller;
        $alert->jobwork_seller = $request->jobwork_seller;
        $alert->joboffercounter_seller = $request->joboffercounter_seller;
        $alert->jobbidcounter_seller = $request->jobbidcounter_seller;
        $alert->jobbid_seller = $request->jobbid_seller;
        $alert->save();


        $notify[] = ['success', 'Professional updated successfully.'];
        return redirect()->route('user.home')->withNotify($notify);
    }

    public function deledu(Request $req)
    {
        $ifdelete = DB::table('user_education')->where('id', '=', $req->id)->delete();
        if ($ifdelete) {
            $msg = 1;
            return response()->json(['success' => $msg]);
        }
    }
    public function delcer(Request $req)
    {
        $ifdelete = DB::table('user_certifications')->where('id', '=', $req->id)->delete();
        if ($ifdelete) {
            $msg = 1;
            return response()->json(['success' => $msg]);
        }
    }

    public function delport(Request $req)
    {
        $ifdelete = DB::table('user_profile_portfolios')->where('id', '=', $req->id)->delete();
        if ($ifdelete) {
            $msg = 1;
            return response()->json(['success' => $msg]);
        }
    }

    public function updatePersonalInfo(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Validation
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare data
        $data = [];
        $data['firstname'] = $request->firstname;
        $data['lastname'] = $request->lastname;
        $data['designation'] = $request->designation;
        $data['country'] = $request->country;
        $data['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city,
            'country' => $request->country,
        ];

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('profile_pictures', $fileName, 'public');
            // Remove old image if exists
            if ($user->image) {
                $oldImagePath = storage_path('app/public/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $user->image = $filePath;
        }

        // Handle background image
        if ($request->hasFile('bg_image')) {
            $image = $request->file('bg_image');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $image->storeAs('bg_image', $fileName, 'public');
            $user->image = $filePath;
        }

        // Update the user's data
        $user->fill($data);

        // Update completed_step if less than 1
        if ($user->completed_step < 1) {
            $user->completed_step = 1;
        }

        $user->save();
        $notify[] = ['success', 'Personal information updated successfully.'];
        // Return success message
        return redirect()->back()->withNotify($notify);
    }

    public function updateEducation(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'countrycollege' => 'required|array|min:1',
            'countrycollege.*' => 'required|string|max:255',
            'namecollege' => 'required|array|min:1',
            'namecollege.*' => 'required|string|max:255',
            'namedegree' => 'required|array|min:1',
            'namedegree.*' => 'required|string|max:255',
            'yeardegree' => 'required|array|min:1',
            'yeardegree.*' => 'required|integer|min:1900|max:' . date('Y'),
            'degrecertificate' => 'sometimes|array',
            'degrecertificate.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'countrycollege.*.required' => 'The country field is required.',
            'namecollege.*.required' => 'The college name field is required.',
            'namedegree.*.required' => 'The degree name field is required.',
            'yeardegree.*.required' => 'The year of degree is required.',
            'yeardegree.*.integer' => 'The year of degree must be a valid year.',
            'yeardegree.*.min' => 'The year of degree must not be earlier than 1900.',
            'yeardegree.*.max' => 'The year of degree cannot exceed the current year.',
            'degrecertificate.*.mimes' => 'The degree transcript must be a file of type: jpeg, png, jpg, pdf.',
            'degrecertificate.*.max' => 'The degree transcript file size must not exceed 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('activeStep', 3)
                ->withInput();
        }

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $degrees = $request->namedegree;

            foreach ($degrees as $index => $degreeName) {
                $educationId = $request->education_ids[$index] ?? null;

                $education = $educationId
                    ? $user->educations()->find($educationId)
                    : $user->educations()->make();

                if ($request->hasFile("degrecertificate.$index")) {
                    $file = $request->file("degrecertificate.$index");
                    $path = $file->store('education/certificates', 'public');
                    $education->degrecertificate = $path;
                }

                $education->degree = $degreeName;
                $education->yeardegree = $request->yeardegree[$index] ?? null;
                $education->institue = $request->namecollege[$index] ?? null;
                $education->countryinstitue = $request->countrycollege[$index] ?? null;
                $education->showdegree = isset($request->showdegree[$index]) ? 1 : 0;

                $education->save();
            }

            if ($user->completed_step < 3) {
                $user->completed_step = 3;
                $user->save();
            }

            DB::commit();

            $notify[] = ['success', 'Education details saved successfully.'];
            return redirect()->back()->withNotify($notify)->with('activeStep', 3);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $notify[] = ['error', 'An error occurred while saving education records.'];
            return redirect()->back()->withNotify($notify)->withInput();
        }
    }

    public function updateCertifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namecertificate' => 'required|array|min:1',
            'namecertificate.*' => 'required|string|max:255',
            'nameinstitue' => 'required|array|min:1',
            'nameinstitue.*' => 'required|string|max:255',
            'yearcertificate' => 'required|array|min:1',
            'yearcertificate.*' => 'required|integer|min:1900|max:' . date('Y'),
            'certificate' => 'sometimes|array',
            'certificate.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'namecertificate.*.required' => 'The certificate name field is required.',
            'nameinstitue.*.required' => 'The institute name field is required.',
            'yearcertificate.*.required' => 'The year of certification is required.',
            'yearcertificate.*.integer' => 'The year of certification must be a valid year.',
            'yearcertificate.*.min' => 'The year of certification must not be earlier than 1900.',
            'yearcertificate.*.max' => 'The year of certification cannot exceed the current year.',
            'certificate.*.mimes' => 'The certificate file must be of type: jpeg, png, jpg, pdf.',
            'certificate.*.max' => 'The certificate file size must not exceed 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('activeStep', 4)
                ->withInput();
        }

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $submittedIds = []; // keep track of updated/new IDs

            foreach ($request->namecertificate as $index => $certificateName) {
                $certificationId = $request->certificate_ids[$index] ?? null;

                // Find or create new record
                $certification = $certificationId
                    ? $user->certifications()->find($certificationId)
                    : $user->certifications()->make();

                if (!$certification) {
                    // skip invalid ID
                    continue;
                }

                // Handle file upload
                if ($request->hasFile("certificate.$index")) {
                    $file = $request->file("certificate.$index");
                    $path = $file->store('certifications/files', 'public');
                    $certification->certificate = $path;
                }

                // Assign values
                $certification->namecertificate = $certificateName;
                $certification->nameinstitue = $request->nameinstitue[$index] ?? null;
                $certification->yearcertificate = $request->yearcertificate[$index] ?? null;
                $certification->showcertificate = isset($request->showcertificate[$index]) ? 1 : 0;
                $certification->save();

                $submittedIds[] = $certification->id; // track saved/updated IDs
            }

            // Optionally delete removed ones
            $user->certifications()
                ->whereNotIn('id', $submittedIds)
                ->delete();

            if ($user->completed_step < 4) {
                $user->completed_step = 4;
                $user->save();
            }

            DB::commit();

            $notify[] = ['success', 'Certification details saved successfully.'];
            return redirect()->back()->withNotify($notify)->with('activeStep', 4);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $notify[] = ['error', 'An error occurred while saving certification records.'];
            return redirect()->back()->withNotify($notify)->withInput();
        }
    }



    public function updateLanguages(Request $request)
    {
        // Manually validate the request
        $validator = Validator::make($request->all(), [
            'langname' => 'required|array|min:1',
            'langvalue' => 'required|array|min:1',
            'langname.*' => 'required|string|max:255',
            'langvalue.*' => 'required|string|max:255',
            'language_ids' => 'nullable|array',
            'language_ids.*' => 'nullable|integer|exists:user_languages,id',
        ], [
            'langname.*.required' => 'The language name field is required.',
            'langname.*.string' => 'The language name must be a valid string.',
            'langvalue.*.required' => 'The language level field is required.',
            'langvalue.*.string' => 'The language level must be a valid string.',
            'deleted_language_ids.*.integer' => 'The deleted language ID must be an integer.',
            'deleted_language_ids.*.exists' => 'The selected deleted language ID is invalid.',
            'language_ids.*.integer' => 'The language ID must be an integer.',
            'language_ids.*.exists' => 'The selected language ID is invalid.',
        ]);

        // If validation fails, return with errors and activeStep
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator) // Pass validation errors
                ->withInput() // Retain old input
                ->with('activeStep', 5); // Set activeStep to 4
        }

        // Get logged-in user
        $user = Auth::user();

        // Process updated and new language records
        $languageIds = $request->language_ids;
        $languageNames = $request->langname;
        $languageLevels = $request->langvalue;

        foreach ($languageNames as $index => $languageName) {
            // Check if the language ID exists
            if (!empty($languageIds[$index])) {
                // Update existing language
                $language = UserLanguage::find($languageIds[$index]);
                if (!$language || $language->user_id !== $user->id) {
                    // Skip or handle invalid language ID
                    continue;
                }
            } else {
                // Create new language
                $language = new UserLanguage();
            }

            $language->user_id = $user->id;
            $language->name = $languageName;
            $language->type = $languageLevels[$index] ?? null;

            $language->save();
        }

        // Update user step progress if needed
        if ($user->completed_step < 5) { // Adjust step as per your application flow
            $user->completed_step = 5;
            $user->save();
        }

        $notify[] = ['success', 'Languages updated successfully.'];
        return redirect()->back()->withNotify($notify)->with('activeStep', 5); // Adjust step as needed
    }

    public function updateOccupation(Request $request)
    {
        $input = $request->all();
        if (isset($input['about_me'])) {
            $cleaned = trim(strip_tags($input['about_me']));
            if ($cleaned === '' || $cleaned === '&nbsp;' || $cleaned === null || $cleaned === '<br >') {
                $input['about_me'] = null; // Treat as empty
            }
        }
        $validator = Validator::make($input, [
            'about_me' => 'required|string|max:1000',
            'weekly_hours' => 'required|string',
            'rate' => 'nullable|numeric|min:0',
            'professional' => 'nullable|boolean',
            'serviceprovider' => 'nullable|boolean',
            'expertLevel' => 'required|in:Beginner,Intermedite,Expert',
        ]);
        // If validation fails, return with errors and activeStep
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator) // Pass validation errors
                ->withInput() // Retain old input
                ->with('activeStep', 6); // Set activeStep to 4
        }
        // Get the authenticated user
        $user = Auth::user();

        // Update user details
        $user->about_me = $request->about_me;
        $user->weekly_hours = $request->weekly_hours;
        $user->per_hour_rate = $request->rate;
        $user->professional = $request->professional ?? false; // Default to false if not provided
        $user->serviceprovider = $request->serviceprovider ?? false; // Default to false if not provided
        $user->level = $request->expertLevel;

        // Save the updated user details
        if ($user->save()) {
            if ($user->completed_step < 6) { // Adjust step as per your application flow
                $user->completed_step = 6;
                $user->save();
            }
            $notify[] = ['success', 'Occupation details updated successfully.'];
            return redirect()->back()->withNotify($notify)->with('activeStep', 6); // Adjust step as needed
        }

        $notify[] = ['error', 'Failed to update occupation details.'];
        return redirect()->back()->withNotify($notify)->with('activeStep', 6); // Adjust step as needed
    }

    public function updateSkills(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category' => 'required|exists:categories,id', // Ensure the category exists in the categories table
            'subcategory' => 'required|exists:sub_categories,id', // Ensure the subcategory exists in the subcategories table
            'childcategory' => 'nullable|exists:child_categories,id', // Ensure the child category exists in the childcategories table (if provided)
            'skills' => 'required|array', // Ensure skills is an array (if provided)
            'skills.*' => 'exists:skills,id', // Ensure each skill ID exists in the skills table
            'newskills' => 'nullable|array', // Ensure newskills is an array (if provided)
            'newskills.*' => 'string|max:255', // Ensure each new skill is a string and has a maximum length of 255
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator) // Pass validation errors
                ->withInput() // Retain old input
                ->with('activeStep', 7); // Set activeStep to 4
        }
        // Prepare data for update
        $in['cat_id'] = $request->category;
        $in['subcat_id'] = $request->subcategory;
        $in['childcat_id'] = $request->childcategory;

        $user = Auth::user();
        // Update user profile with category, subcategory, and child category
        User::where('id', $user->id)->update($in);

        // Delete existing skills and insert new ones
        try {
            DB::beginTransaction(); // Start a database transaction

            // Delete existing skills
            UserProfileSkill::where('user_id', $user->id)->delete();

            // Insert new skills if provided
            if ($request->has('skills')) {
                $service_arr = [];
                foreach ($request->skills as $skill_id) {
                    $service_arr[] = [
                        'skill_id' => $skill_id,
                        'user_id' => $user->id,
                        'created_at' => now(), // Add current timestamp for created_at
                        'updated_at' => now(), // Add current timestamp for updated_at
                    ];
                }
                UserProfileSkill::insert($service_arr);
            }

            // Delete existing new skills and insert new ones
            UserProfileNewSkill::where('user_id', $user->id)->delete();

            if ($request->has('newskills')) {
                $newskill_arr = [];
                foreach ($request->newskills as $newskill) {
                    $newskill_arr[] = [
                        'user_id' => $user->id,
                        'name' => $newskill,
                        'created_at' => now(), // Add current timestamp for created_at
                        'updated_at' => now(), // Add current timestamp for updated_at
                    ];
                }
                UserProfileNewSkill::insert($newskill_arr);
            }
            if ($user->completed_step < 7) { // Adjust step as per your application flow
                $user->completed_step = 7;
                $user->save();
            }
            DB::commit(); // Commit the transaction

            // Redirect with success message
            $notify[] = ['success', 'Skills updated successfully.'];
            return redirect()->back()->withNotify($notify)->with('activeStep', 7);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction in case of error
            Log::info('An error occurred while updating skills', ['error' => $e->getMessage()]);
            $notify[] = ['error', 'An error occurred while updating skills. Please try again.'];
            return redirect()->back()->withNotify($notify)->with('activeStep', 7);
        }
    }
    public function updatePortfolio(Request $request)
    {
        $pdesc = $request->input('pdesc', []);
        $cleanedDesc = array_map(function ($desc) {
            // Remove HTML tags and whitespace
            $plain = trim(strip_tags($desc));

            // Convert entities like &nbsp; to normal spaces and trim again
            $plain = trim(html_entity_decode($plain));

            // If empty after cleaning, set to null
            return $plain === '' ? null : $desc;
        }, $pdesc);

        // Replace cleaned values back into request
        $request->merge(['pdesc' => $cleanedDesc]);
        // Validate the request
        $validator = Validator::make($request->all(), [
            'ptitle' => 'required|array|min:1',
            'pdesc' => 'required|array|min:1',
            'ptitle.*' => 'required|string|max:255',
            'pdesc.*' => 'required|string',
            'portfolio_ids' => 'nullable|array',
            'portfolio_ids.*' => 'nullable|integer|exists:user_profile_portfolios,id',
            'pimage' => [
                'array',
                function ($attribute, $value, $fail) use ($request) {
                    foreach ($request->ptitle as $index => $title) {
                        if (empty($request->portfolio_ids[$index]) && empty($request->file('pimage')[$index])) {
                            $fail('The image field is required for new portfolio entries.');
                        }
                    }
                }
            ],
            'pimage.*' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ptitle.*.required' => 'The title field is required.',
            'pdesc.*.required' => 'The description field is required.',
            'pimage.*.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'pimage.*.max' => 'The image size must not exceed 2MB.',
            'portfolio_ids.*.exists' => 'The selected portfolio ID is invalid.',
        ]);

        // If validation fails, return with errors and activeStep
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('activeStep', 8);
        }

        // Get logged-in user
        $user = Auth::user();

        $user_profile_portfolio_count = UserProfilePortfolio::where('user_id', $user->id)->count();
        $freelancer_service_count = Freelancerservicescount::where('user_id', $user->id)->latest()->first();
        $portfolio_limit = $freelancer_service_count->portfolio_size;
        if ($portfolio_limit != 0) {
            if ($user_profile_portfolio_count >= $portfolio_limit) {
                $notify[] = ['error', 'Your reached your maximum portfolio limit.'];
                return redirect()->back()->withNotify($notify)->with('activeStep', 8);
            }
        }

        // Process updated and new portfolio records
        $portfolioIds = $request->portfolio_ids ?? [];
        $portfolioTitles = $request->ptitle ?? [];
        $portfolioDescriptions = $request->pdesc ?? [];
        $portfolioImages = $request->file('pimage') ?? [];

        foreach ($portfolioTitles as $index => $title) {
            // If portfolio ID exists, update; otherwise, create a new record
            $portfolio = !empty($portfolioIds[$index]) ? UserProfilePortfolio::find($portfolioIds[$index]) : new UserProfilePortfolio();

            if (!$portfolio || (!$portfolio->id && !$user->id)) {
                continue; // Skip if invalid
            }

            $portfolio->user_id = $user->id;
            $portfolio->title = $title;
            $portfolio->description = $portfolioDescriptions[$index] ?? null;

            // **Handle Image Upload**
            if (isset($portfolioImages[$index])) {
                $file = $portfolioImages[$index];
                $fileName = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('portfolio/', $fileName, 'public');

                // Delete old image if exists
                if ($portfolio->image) {
                    $oldImagePath = storage_path('app/public/' . $portfolio->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $portfolio->image = $filePath;
            }
            $portfolio->save();
        }

        // Update user step progress if needed
        if ($user->completed_step < 8) {
            $user->completed_step = 8;
            $user->save();
        }

        // Redirect back with success notification
        $notify[] = ['success', 'Portfolio updated successfully.'];
        return redirect()->back()->withNotify($notify)->with('activeStep', 8);
    }


    public function destroyEducation($id)
    {
        $education = UserEducation::find($id);
        if (!$education) {
            return response()->json(['success' => false, 'message' => 'Education record not found.'], 404);
        }

        $user = $education->user; // Assuming there's a relationship between UserEducation and User
        if ($education->delete()) {
            // Check if this was the last education record
            if ($user->educations()->count() === 0) {
                $user->completed_step -= 1;
                $user->save();
            }
            return response()->json(['success' => true, 'message' => 'Education record deleted successfully.', 'activeStep' => $user->completed_step]);
        }
        return response()->json(['success' => false, 'message' => 'Failed to delete education record.', 'activeStep' => $user->completed_step], 500);
    }

    public function destroyCertificate($id)
    {
        $certificate = UserCertification::find($id);
        if (!$certificate) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $user = $certificate->user; // Assuming there's a relationship between UserCertification and User
        if ($certificate->delete()) {
            // Check if this was the last certificate record
            if ($user->certifications()->count() === 0) {
                $user->completed_step -= 1;
                $user->save();
            }
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.', 'activeStep' => $user->completed_step]);
        }
        return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'activeStep' => $user->completed_step], 500);
    }

    public function destroyLanguage($id)
    {
        $language = UserLanguage::find($id);
        if (!$language) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $user = $language->user; // Assuming there's a relationship between UserLanguage and User
        if ($language->delete()) {
            // Check if this was the last language record
            if ($user->languages()->count() === 0) {
                $user->completed_step -= 1;
                $user->save();
            }
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.', 'activeStep' => $user->completed_step]);
        }
        return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'activeStep' => $user->completed_step], 500);
    }

    public function destroyPortfolio($id)
    {
        $portfolio = UserProfilePortfolio::find($id);
        if (!$portfolio) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $user = $portfolio->user; // Assuming there's a relationship between UserLanguage and User
        if ($portfolio->delete()) {
            // Check if this was the last language record
            if ($user->portfolioprofile()->count() === 0) {
                $user->completed_step -= 1;
                $user->save();
            }
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.', 'activeStep' => $user->completed_step]);
        }
        return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'activeStep' => $user->completed_step], 500);
    }

    public function selling_profile(Request $request)
    {
        $postData = $request->all();

        //echo"<pre>"; print_r($postData);exit;
        $user = Auth::user();
        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['designation'] = $request->designation;
        $in['about_me'] = $request->about_me;
        $in['per_hour_rate'] = $request->rate;
        $in['professional'] = $request->freelancer;
        $in['professional'] = $request->serviceprovider;
        $in['level'] = $request->level;
        $in['cat_id'] = $request->category;
        $in['subcat_id'] = $request->subcategory;
        $in['status'] = 2;
        $in['childcat_id'] = $request->childcategory;
        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];

        if ($request->hasFile('image')) {
            $wallpaper_image = $request->file('image');
            $fileName = time() . '-' . $wallpaper_image->getClientOriginalName();
            $wallpaper_image->move('assets/images/user/profile/', $fileName);
            $in['image'] = $fileName;
        }

        if ($request->hasFile('bg_image')) {
            $wallpaper_image = $request->file('bg_image');
            $fileName = time() . '-' . $wallpaper_image->getClientOriginalName();
            $wallpaper_image->move('assets/images/user/profile/', $fileName);
            $in['bg_image'] = $fileName;
        }

        // if ($request->hasFile('image')) {
        //     $location = imagePath()['profile']['user']['path'];

        //     $size = imagePath()['profile']['user']['size'];
        //     $filename = uploadImage($request->image, $location, $size, $user->image);
        //     //echo $filename;exit;
        //     $in['image'] = $filename;
        // }
        // //exit;
        // if ($request->hasFile('bg_image')) {
        //     $location = imagePath()['profile']['user_bg']['path'];
        //     $size = imagePath()['profile']['user_bg']['size'];
        //     $filename = uploadImage($request->bg_image, $location, $size, $user->bg_image);
        //     $in['bg_image'] = $filename;
        // }

        $e = $user->fill($in)->save();
        if ($e) {
            $langname = $request->langname;
            $langtype = $request->langvalue;
            foreach ($langtype as $key => $ds) {
                $lan = new UserLanguage();
                $lan->name = $langname[$key];
                $lan->type = $langtype[$key];
                $lan->user_id = $user->id;
                $lan->save();
            }
            $coun = $request->countrycollege;
            $col = $request->namecollege;
            $deg = $request->namedegree;
            $yeard = $request->yeardegree;
            $img = $request->degrecertificate;
            $showdeg = $request->showdegree;

            if ($request->hasFile('degrecertificate')) {
                $files = $request->file('degrecertificate');
                foreach ($files as $key => $file) {
                    $edu = new UserEducation();
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileName = str::random(5) . "-" . date('his') . "-" . rand(10, 10000) . "." . $extension;
                    $destinationPath = 'assets/wiserlance/assets/img/education' . '/';
                    $file->move($destinationPath, $fileName);
                    $edu->countryinstitue = $coun[$key];
                    $edu->institue = @$col[$key];
                    $edu->degree = @$deg[$key];
                    $edu->yeardegree = @$yeard[$key];
                    $edu->showdegree = @$showdeg[$key];
                    $edu->user_id = @$user->id;
                    $edu->degrecertificate = @$fileName;

                    $edu->save();
                }
            }



            $ins = $request->nameinstitue;
            $cern = $request->namecertificate;
            $yearc = $request->yearcertificate;
            $showcerti = $request->showcerti;
            $img = $request->certificate;

            if ($request->hasFile('certificate')) {
                $files = $request->file('certificate');
                if (isset($files)) {
                    foreach ($files as $key => $file) {
                        $cer = new UserCertification();
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $fileName = str::random(5) . "-" . date('his') . "-" . rand(10, 10000) . "." . $extension;
                        $destinationPath = 'assets/wiserlance/assets/img/certification' . '/';
                        $file->move($destinationPath, $fileName);
                        $cer->nameinstitue = @$ins[$key];
                        $cer->namecertificate = @$cern[$key];
                        $cer->yearcertificate = @$yearc[$key];
                        $cer->showcertificate = @$showcerti[$key];
                        $cer->user_id = @$user->id;
                        $cer->certificate = @$fileName;

                        $cer->save();
                    }
                }
            }




            $ptitle = $request->ptitle;
            $pdesc = $request->pdesc;
            $img = $request->images;

            if ($request->hasFile('images')) {
                $files = $request->file('images');
                if (isset($files)) {
                    foreach ($files as $key => $file) {
                        $pf = new UserProfilePortfolio();
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $fileName = str::random(5) . "-" . date('his') . "-" . rand(10, 10000) . "." . $extension;
                        $destinationPath = 'assets/wiserlance/assets/img/portfolio' . '/';
                        $file->move($destinationPath, $fileName);
                        $pf->title = @$ptitle[$key];
                        $pf->description = @$pdesc[$key];
                        $pf->user_id = @$user->id;
                        $pf->image = @$fileName;

                        $pf->save();
                    }
                }
            }





            $service_arr = [];
            if (isset($request->skills)) {
                for ($i = 0; $i < count($request->skills); $i++) {
                    $service_arr[] = [
                        'skill_id' => $request->skills[$i],
                        'user_id' => $user->id
                    ];
                }
            }
            UserProfileSkill::insert($service_arr);
            if ($request->has('newskills')) {
                $newskill_arr = [];
                for ($i = 0; $i < count($request->newskills); $i++) {
                    $newskill_arr[] = [
                        'user_id' => $user->id,
                        'name' => $request->newskills[$i],
                    ];
                }
                UserProfileNewSkill::insert($newskill_arr);
            }


            //             if ($request->hasFile('images')) {
            // $files = $request->file('images');
            // foreach($files as $file){
            //     $img = new  UserProfilePortfolio();
            //     $filename = $file->getClientOriginalName();
            //     $extension = $file->getClientOriginalExtension();
            //     $fileName = str::random(5)."-".date('his')."-".rand(10,10000).".".$extension;
            //     $destinationPath = 'assets/wiserlance/assets/img/portfolio'.'/';
            //     $file->move($destinationPath, $fileName);

            //                 $img->user_id = $user->id;
            //                 $img->image = $fileName;

            //                 $img->save();
            // }
            // }

        }
        $alert = new UserProfileAlerts();
        // $alert->proposalrecieved = $request->proposalrecieved;
        // $alert->jobposted = $request->jobposted;
        // $alert->offermodified = $request->offermodified;
        // $alert->jobpostexpiresoon = $request->jobpostexpiresoon;
        // $alert->jobpostexpired = $request->jobpostexpired;
        // $alert->contractstart = $request->contractstart;
        // $alert->contractmodify = $request->contractmodify;
        // $alert->contractend = $request->contractend;
        // $alert->offerdecline = $request->offerdecline;
        $alert->servicebooked_seller = $request->servicebooked_seller;
        $alert->serviceworkyes_seller = $request->serviceworkyes_seller;
        $alert->serviceworkno_seller = $request->serviceworkno_seller;
        $alert->servicebookdispute_seller = $request->servicebookdispute_seller;
        $alert->servicebookdisputeoffer_seller = $request->servicebookdisputeoffer_seller;
        $alert->servicebookdisputeofferyes_seller = $request->servicebookdisputeofferyes_seller;
        $alert->servicebookdisputeofferno_seller = $request->servicebookdisputeofferno_seller;
        $alert->servicebookdisputeadmin_seller = $request->servicebookdisputeadmin_seller;
        $alert->servicebookrr_seller = $request->servicebookrr_seller;

        $alert->jobdisputeadmin_seller = @$request->jobdisputeadmin_seller;
        $alert->jobdisputeofferno_seller = $request->jobdisputeofferno_seller;
        $alert->jobdisputeofferyes_seller = $request->jobdisputeofferyes_seller;
        $alert->jobdisputeoffer_seller = $request->jobdisputeoffer_seller;
        $alert->jobwork_seller = $request->jobwork_seller;
        $alert->joboffercounter_seller = $request->joboffercounter_seller;
        $alert->jobbidcounter_seller = @$request->jobbidcounter_seller;
        $alert->jobbid_seller = $request->jobbid_seller;

        $alert->type = '0';
        $alert->user_id = $user->id;
        $alert->save();

        $notify[] = ['success', 'Professional added successfully.'];
        return redirect()->route('user.home')->withNotify($notify);
    }
    public function index(Request $request)
    {
        $pageTitle = "Home";
        $emptyMessage = "No data found";
        if ($request->type == 'jobs') {
            return $this->searchJob($request);
        } else if ($request->type == 'professionals') {
            return $this->searchProfessionals($request);
        } else if ($request->type == 'services') {
            return $this->service($request);
        }
        $user = Auth::user();

        $blogs = Frontend::where('data_keys', 'blog.element')->take('6')->get();
        $services = Service::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->where('featured', 1)->inRandomOrder()->paginate(getPaginate());
        $professionals = User::where('status', 1)->where('is_freelancer', 1)->inRandomOrder()->orderBy('income', 'desc')->where('per_hour_rate', '!=', '')->take(8)->get();
        $jobs = Job::where('status', 1)->where('job_status', 0)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank', 'jobBiding')->where('priority', '2')->orderBy('id', 'desc')->inRandomOrder()->take(8)->get();

        // $categories = Category::with('subCategory')->where('type', 'job')->where('status', 1)
        //     ->withCount('jobs')
        //     ->get(['id', 'name', 'status', 'type']);

        $service_categories = Category::with('subCategory')->where('type', 'service')->where('status', 1)
            ->get(['id', 'name', 'status', 'type', 'image']);

        $getbids = null;
        if (!empty($user)) {
            if ($user->is_freelancer == 1) {
                $getbids = Freelancerservicescount::where("user_id", $user->id)->select('bids')->get();
                if (count($getbids)) {
                    $getbids = $getbids[0]->bids;
                }
            }
        }

        $rank1 = Rank::where('level', 1)->first();
        $rank2 = Rank::where('level', 2)->first();
        $rank3 = Rank::where('level', 3)->first();
        $rank4 = Rank::where('level', 4)->first();
        $rank5 = Rank::where('level', 5)->first();
        return view($this->activeTemplate . 'home_new_design', compact('pageTitle', 'service_categories','services', 'emptyMessage', 'user', 'jobs', 'getbids', 'professionals', 'blogs', 'rank1', 'rank2', 'rank3', 'rank4', 'rank5'));
    }
    public function allcategories()
    {
        $pageTitle = "Jobs";
        $emptyMessage = "No data found";
        return view($this->activeTemplate . 'allcategories', compact('pageTitle', 'emptyMessage'));
    }
    public function service(Request $request)
    {
        $pageTitle = "Service";
        $emptyMessage = "No data found";

        $categories = Category::where('type', 'service')->get();

        // Ranks for all levels
        $ranks = Rank::whereIn('level', [1, 2, 3, 4, 5])->get()->keyBy('level');

        // Currency logic
        $helpers = new \App\Classes\Helpers();
        $currency = Session::get('currency');
        $currencySymbol = Session::get('currency_symbol');
        $currency_rates = $helpers->get_currency_rates();

        // Build query with optional search filters
        $servicesQuery = Service::where('status', 1)
            ->whereHas('user')
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })->with(['user', 'user.rank', 'category']);
        // Search by title
        if ($request->filled('title')) {
            $servicesQuery->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('search')) {
            $servicesQuery->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $servicesQuery->where('category_id', $request->category);
        }

        // Search by location (assuming user has location field)
        if ($request->filled('location')) {
            $location = strtolower($request->location);
            $servicesQuery->whereHas('user', function ($q) use ($location) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(address, '$.country'))) LIKE ?", ['%' . $location . '%']);
            });
        }

        // --- SORTING LOGIC STARTS HERE ---
        $sort = $request->get('sort');
        if ($sort == 'date') {
            $servicesQuery->orderByDesc('created_at'); // Newest first
        } else {
            $servicesQuery->inRandomOrder(); // Default/random order if no filter
        }
        // --- SORTING LOGIC ENDS HERE ---
        // Paginated, eager loaded services (users and category)
        $services = $servicesQuery->inRandomOrder()->paginate(getPaginate())->appends($request->all());


        // Preprocess all services for ratings, price, etc.
        $processedServices = $services->map(function ($service) use ($ranks, $helpers, $currency, $currencySymbol) {
            // Ratings
            $rate1 = \DB::table('review_ratings')->where('pro_id', $service->user->id)->avg('rating');
            $rate_count = \DB::table('review_ratings')->where('pro_id', $service->user->id)->count();
            $rate3 = \DB::table('review_ratings')->where('provider_id', $service->user->id)->avg('rating');
            $rate_count3 = \DB::table('review_ratings')->where('provider_id', $service->user->id)->count();

            $avg_rating = round((floatval($rate1) + floatval($rate3)) / 2, 1);
            $total_reviews = $rate_count + $rate_count3;

            // Service details
            $service_details = \DB::table('services_details')->where('service_id', $service->id)->first();
            $price = $helpers->convert_to_currency($currency, $service_details->price ?? 0);
            $delivery_time = $service_details->delivery_time ?? 0;

            // Rank & progress
            $income = $service->user->income;
            $rank = 'Rank 1';
            $progress = 25;
            foreach ([5, 4, 3, 2, 1] as $level) {
                if (isset($ranks[$level]) && $income >= $ranks[$level]->amount) {
                    $rank = $level == 5 ? 'Top Rank' : 'Rank ' . $level;
                    $progress = 25 + 15 * ($level - 1);
                    if ($level == 5)
                        $progress = 100;
                    break;
                }
            }

            // Return all for blade
            return (object) [
                'service' => $service,
                'avg_rating' => $avg_rating,
                'total_reviews' => $total_reviews,
                'price' => $price,
                'delivery_time' => $delivery_time,
                'rank' => $rank,
                'progress' => $progress,
            ];
        });


        // Price sorting (Low to High or High to Low)
        if ($sort == 'price_asc') {
            $processedServices = $processedServices->sortBy('price')->values();
        } elseif ($sort == 'price_desc') {
            $processedServices = $processedServices->sortByDesc('price')->values();
        }

        $services->setCollection($processedServices);

        // Professional count
        $professionalCount = Service::where('status', 1)
            ->whereHas('user')
            ->distinct('user_id')
            ->count('user_id');

        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
        //     ['title' => 'Services'],
        // ];
        $user = Auth::user();

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);


        return view($this->activeTemplate . 'service', compact(
            'pageTitle',
            'emptyMessage',
            'categories',
            'professionalCount',
            'currencySymbol',
            'processedServices',
            'services',
            'user',
            'countries'
        ));
    }

    public function servicecategoriesall($slug, $id)
    {
        //echo 'asd';exit;
        $cat = Category::where('id', decrypt($id))->first();
        $pageTitle = @$cat->name . " Sub Categories";
        $emptyMessage = "No Sub Categories found";
        return view($this->activeTemplate . 'service_subcategories', compact('pageTitle', 'cat', 'emptyMessage'));
    }
    public function servicesubcategoriesallservices($slug, $id)
    {
        //echo 'asd';exit;
        $sub_cat = SubCategory::where('id', decrypt($id))->first();
        $pageTitle = @$sub_cat->name . " Services";
        $emptyMessage = "No Services found under " . @$sub_cat->name;
        $subcategoryes = $childcategories = $skills = [];
        $categoryes = DB::table('categories')->where('type', 'service')->get();
        // $childcategories=DB::table('skill_categories')->get();
        // $skills=DB::table('skills')->get();
        //  $subcategoryes=DB::table('sub_categories')->get();
        // $childcategories=DB::table('skill_categories')->get();
        // $skills=DB::table('skills')->get();
        $services = Service::where('status', 1)->where('sub_category_id', $sub_cat->id)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->inRandomOrder()->paginate(getPaginate());
        $services_count = Service::where('status', 1)->where('sub_category_id', $sub_cat->id)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->count();
        $category = Category::where('id', $sub_cat->category_id)->first();
        return view($this->activeTemplate . 'subcatservices', compact('pageTitle', 'services', 'emptyMessage', 'categoryes', 'childcategories', 'subcategoryes', 'sub_cat', 'category', 'services_count'));
    }
    public function servicechildcategoriesallservices($slug, $id)
    {
        //echo 'asd';exit;
        $sub_cat = ChildCategories::where('id', decrypt($id))->first();
        $pageTitle = @$sub_cat->name . " Services";
        $emptyMessage = "No Services found under " . @$sub_cat->name;
        $subcategoryes = $childcategories = $skills = [];
        $categoryes = DB::table('categories')->where('type', 'service')->get();
        // $childcategories=DB::table('skill_categories')->get();
        // $skills=DB::table('skills')->get();
        //  $subcategoryes=DB::table('sub_categories')->get();
        // $childcategories=DB::table('skill_categories')->get();
        // $skills=DB::table('skills')->get();
        $services = Service::where('status', 1)->where('childcat_id', $sub_cat->id)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->inRandomOrder()->paginate(getPaginate());
        $services_count = Service::where('status', 1)->where('childcat_id', $sub_cat->id)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->count();
        $category = Category::where('id', $sub_cat->cat_id)->first();
        $subcategory = SubCategory::where('id', $sub_cat->subcat_id)->first();
        return view($this->activeTemplate . 'childcatservices', compact('pageTitle', 'services', 'emptyMessage', 'categoryes', 'childcategories', 'subcategoryes', 'sub_cat', 'category', 'services_count', 'subcategory'));
    }

    public function categoryservice($id)
    {
        $cat = Category::where('id', decrypt($id))->first();
        $pageTitle = $cat->name . " Jobs";
        $emptyMessage = "No Jobs found";
        $subcategoryes = $childcategories = $skills = [];
        $categoryes = DB::table('categories')->where('type', 'job')->get();
        $jobs = Job::where('status', 1)->where('category_id', $cat->id)->where('job_status', 0)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank', 'jobBiding')->orderBy('id', 'desc')->paginate(8);
        return view($this->activeTemplate . 'cat_services', compact('pageTitle', 'jobs', 'emptyMessage', 'categoryes', 'childcategories', 'subcategoryes', 'cat'));
    }
    public function subcategoryservice($id)
    {
        $cat = SubCategory::where('id', decrypt($id))->first();
        $pageTitle = $cat->name . " Jobs";
        $emptyMessage = "No jobs found";
        $subcategoryes = $childcategories = $skills = [];
        $categoryes = DB::table('categories')->where('type', 'service')->get();
        $jobs = Job::where('status', 1)->where('sub_category_id', $cat->id)->where('job_status', 0)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank', 'jobBiding')->where('sub_category_id', $cat->id)->orderBy('id', 'desc')->paginate(8);
        return view($this->activeTemplate . 'subcat_services', compact('pageTitle', 'jobs', 'emptyMessage', 'categoryes', 'childcategories', 'subcategoryes'));
    }

    public function professionals(Request $request)
    {
        if ($request->type == 'jobs') {
            return $this->searchJob($request);
        } else if ($request->type == 'services') {
            return $this->service($request);
        }

        $pageTitle = "All professionals";
        $emptyMessage = "No data found";
        // $frelancer_memberships = Freelancermembership::orderBy('price','desc')->distinct()->pluck('title')->toArray();
        $freelancer_memberships = membershipsArray();
        $user = Auth::user();

        $professionals = User::with('freelancer_membership_transaction.membership', 'languages')
            ->where('users.status', 1)
            ->where('is_freelancer', 1)
            ->where(function ($query) {
                $query->where('per_hour_rate', '!=', 0)
                    ->orWhere('per_hour_rate', '!=', '');
            });

        if ($request->has('skill') && $request->skill != '') {
            $skill = Skill::where('name', $request->skill)->pluck('id')->toArray();
            if (count($skill) > 0) {
                $professionals->whereHas('profileSkills', function ($query) use ($skill) {
                    $query->whereIn('skill_id', $skill);
                });
            } else {
                $professionals = $professionals->whereRaw('0 = 1');
            }
        }
        if (($request->has('name') && $request->name != '') || $request->search) {
            $professionals->where('username', 'like', '%' . ($request->filled('name') ? $request->name : $request->search) . '%');
        }

        $professionals = $professionals->leftJoin('review_ratings', 'users.id', '=', 'review_ratings.pro_id')
            ->leftJoin('freelancer_membership_transactions', 'users.id', '=', 'freelancer_membership_transactions.user_id')
            ->leftJoin('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')
            ->select('users.*', DB::raw('AVG(review_ratings.likely_rating) as avg_rating'))
            ->groupBy('users.id')
            ->orderByRaw("
                CASE
                    WHEN freelancer_membership.title = 'Premium' THEN 1
                    WHEN freelancer_membership.title = 'Standard' THEN 2
                    WHEN freelancer_membership.title = 'Essentional' THEN 3
                    WHEN freelancer_membership.title = 'Basic' THEN 4
                    WHEN freelancer_membership.title = 'Try it free' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('avg_rating', 'DESC');
        $professionals = $professionals->inRandomOrder()->paginate(6)->withQueryString();
        foreach ($professionals as $professional) {
            $sellerData = $professional->getSellerProfileData($professional->id);
            $professional->completedJobs = $sellerData['completedJobs']->count();
        }

        // Fetch job categories efficiently
        $categories = Category::where('type', 'job')->cursor();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);

        return view($this->activeTemplate . 'professionals', compact('pageTitle', 'professionals', 'emptyMessage', 'user', 'categories', 'countries'));
    }

    public function serviceDetails_old($slug, $id)
    {
        //////////////chat start /////////////
        $us = Auth::user();
        $flag = User::find($us->id);
        $users_ids = $flag->ChatUser($us->id);
        // print_r($users_ids); exit;
        $users = User::whereIn('id', $users_ids)->get();
        $z = array();
        $sid = $us->id;
        @$rid = Service::where('id', decrypt($id))->first()->user_id;

        $user = Chat::where('ch_s_id', $sid)
            ->where('ch_r_id', $rid)
            ->get()->pluck('ch_id');

        foreach ($user as $key) {
            array_push($z, $key);
        }


        $user1 = Chat::where('ch_s_id', $rid)
            ->where('ch_r_id', $sid)
            ->get()->pluck('ch_id');
        foreach ($user1 as $key) {
            array_push($z, $key);
        }

        $chats = Chat::whereIn('ch_id', $z)->get();
        foreach ($chats as $key) {
            $ss = User::where('id', $key->ch_s_id)->first();
            $key->sender_user = $ss;
            $rr = User::where('id', $key->ch_r_id)->first();
            $key->reciver_user = $rr;
        }
        //////////////chat end ///////////////
        $pageTitle = "Service details";
        $service = Service::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('id', decrypt($id))->firstOrFail();

        $otherServices = Service::where('id', '!=', $service->id)->where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('user_id', $service->user_id)->with('user', 'user.rank')->limit(4)->orderBy('id', 'DESC')->get();

        $totalService = Service::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('user_id', $service->user_id)->with('user')->count();

        $activeUser = Auth::user();
        $conversion = null;
        $serviceGetRating = null;
        $serviceUser = User::where('id', $service->user_id)->firstOrFail();
        if ($activeUser) {
            $serviceGetRating = Booking::where('user_id', $activeUser->id)->where('working_status', 1)->where('service_id', $service->id)->first();
            $conversion = Conversation::where(function ($query) use ($activeUser, $serviceUser) {
                $query->orWhere('sender_id', $activeUser->id)
                    ->orWhere('receiver_id', $activeUser->id);
            })->where(function ($query2) use ($activeUser, $serviceUser) {
                $query2->orWhere('sender_id', $serviceUser->id)->orWhere('receiver_id', $serviceUser->id);
            })->first();
        }
        $workInprogress = Booking::where('status', '!=', 0)->where('working_status', 4)->whereHas('service', function ($q) use ($service) {
            $q->where('user_id', $service->user_id);
        })->count();

        $reviewRataingAvg = ReviewRating::whereHas('service', function ($q) use ($service) {
            $q->where('user_id', $service->user_id);
        })->orWhereHas('software', function ($q) use ($service) {
            $q->where('user_id', $service->user_id);
        })->avg('rating');
        $n = ServicesDetail::where('service_id', $service->id)->select('price1', 'price2', 'price3', 'title1', 'title2', 'title3', 'desc1', 'desc2', 'desc3', 'time1', 'time2', 'time3')->first();
        $comments = Comment::where('service_id', $service->id)->with('user', 'commentReply')->paginate(7);
        $reviews = ReviewRating::where('service_id', $service->id)->with('user', 'service')->paginate(7);
        return view($this->activeTemplate . 'service_deatils', compact('chats', 'sid', 'rid', 'pageTitle', 'service', 'otherServices', 'totalService', 'conversion', 'comments', 'reviews', 'serviceGetRating', 'reviewRataingAvg', 'workInprogress', 'n'));
    }

    public function serviceDetails($id)
    {
        $us = Auth::user();
        $sid = 0; //;$us->id;
        $chats = [];

        $service = Service::where('id', decrypt($id))->first();

        if (!$service) {
            // Handle the case where no service was found
            abort(404, 'Service not found.');
        }

        $rid = $service->user_id;

        $pageTitle = "Service details";
        $serviceQuery = Service::query();

        if ($us && $us->is_freelancer != 1) {
            $serviceQuery->where('status', 1);
        }

        $serviceQuery->whereHas('category', function ($q) {
            $q->where('status', 1);
        });

        $service = $serviceQuery->where('id', decrypt($id))->firstOrFail();

        $otherServices = Service::where('id', '!=', $service->id)->where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('user_id', $service->user_id)->with('user', 'user.rank')->limit(4)->orderBy('id', 'DESC')->get();

        $totalService = Service::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('user_id', $service->user_id)->with('user')->count();

        $activeUser = Auth::user();
        $conversion = null;
        $serviceGetRating = null;

        $serviceUser = User::where('id', $service->user_id)->firstOrFail();
        if ($activeUser) {
            $serviceGetRating = Booking::where('seller_id', $activeUser->id)->where('working_status', 1)->where('service_id', $service->id)->first();
            $conversion = Conversation::where(function ($query) use ($activeUser, $serviceUser) {
                $query->orWhere('sender_id', $activeUser->id)
                    ->orWhere('receiver_id', $activeUser->id);
            })->where(function ($query2) use ($activeUser, $serviceUser) {
                $query2->orWhere('sender_id', $serviceUser->id)->orWhere('receiver_id', $serviceUser->id);
            })->first();
        }

        $workInprogress = Booking::where('status', '!=', 0)->where('working_status', 4)
            ->whereHas('service', function ($q) use ($service) {
                $q->where('user_id', $service->user_id);
            })->count();

        $reviewRataingAvg = ReviewRating::whereHas('service', function ($q) use ($service) {
            $q->where('user_id', $service->user_id);
        })->orWhereHas('software', function ($q) use ($service) {
            $q->where('user_id', $service->user_id);
        })->avg('rating');

        $serviceDetails = ServicesDetail::where('is_active',1)->where('service_id', $service->id)->get();


        $availableFeatures = DB::table('service_tiers')
            ->where('type', 'checkbox')
            ->where('cat_id', $service->category_id)
            ->where('subcat_id', $service->sub_category_id)
            ->when($service->childcat_id > 0, function ($q) use ($service) {
                return $q->where('childcat_id', $service->childcat_id);
            })
            ->get();

        $userFeatures = DB::table('service_detail_tiers')
            ->whereIn('service_detail_id', $serviceDetails->pluck('id'))
            ->get()
            ->groupBy('service_detail_id');  // Grouping by service_detail_id  // This gives tiers grouped per package


        $comments = Comment::where('service_id', $service->id)->with('user', 'commentReply')->paginate(7);
        $reviews = ReviewRating::where('service_id', $service->id)->with('user', 'service')->paginate(7);
        $five = ReviewRating::where('service_id', decrypt($id))->where('rating', 5)->count();
        $four = ReviewRating::where('service_id', decrypt($id))->where('rating', 4)->count();
        $three = ReviewRating::where('service_id', decrypt($id))->where('rating', 3)->count();
        $two = ReviewRating::where('service_id', decrypt($id))->where('rating', 2)->count();
        $one = ReviewRating::where('service_id', decrypt($id))->where('rating', 1)->count();
        $all = ReviewRating::where('service_id', decrypt($id))->get();
        $allcount = ReviewRating::where('service_id', decrypt($id))->count();
        $communication = ReviewRating::where('service_id', decrypt($id))->avg('communicationrating');
        $recommend = ReviewRating::where('service_id', decrypt($id))->avg('recommendrating');
        $described = ReviewRating::where('service_id', decrypt($id))->avg('describedrating');
        $skill = ReviewRating::where('service_id', decrypt($id))->avg('skill');
        // other rating
        $otherfive = ReviewRating::where('provider_id', $service->user_id)->where('rating', 5)->count();
        $otherfour = ReviewRating::where('provider_id', $service->user_id)->where('rating', 4)->count();
        $otherthree = ReviewRating::where('provider_id', $service->user_id)->where('rating', 3)->count();
        $othertwo = ReviewRating::where('provider_id', $service->user_id)->where('rating', 2)->count();
        $otherone = ReviewRating::where('provider_id', $service->user_id)->where('rating', 1)->count();
        $otherall = ReviewRating::where('provider_id', $service->user_id)->get();
        $otherallcount = ReviewRating::where('provider_id', $service->user_id)->count();
        $othercommunication = ReviewRating::where('provider_id', $service->user_id)->avg('communicationrating');
        $otherrecommend = ReviewRating::where('provider_id', $service->user_id)->avg('recommendrating');
        $otherdescribed = ReviewRating::where('provider_id', $service->user_id)->avg('describedrating');
        $otherskill = ReviewRating::where('provider_id', $service->user_id)->avg('skill');

        // job rating
        $jobfive = ReviewRating::where('pro_id', $service->user_id)->where('rating', 5)->count();
        $jobfour = ReviewRating::where('pro_id', $service->user_id)->where('rating', 4)->count();
        $jobthree = ReviewRating::where('pro_id', $service->user_id)->where('rating', 3)->count();
        $jobtwo = ReviewRating::where('pro_id', $service->user_id)->where('rating', 2)->count();
        $jobone = ReviewRating::where('pro_id', $service->user_id)->where('rating', 1)->count();
        $joball = ReviewRating::where('pro_id', $service->user_id)->get();
        $joballcount = ReviewRating::where('pro_id', $service->user_id)->count();
        $jobcommunication = ReviewRating::where('pro_id', $service->user_id)->avg('communicationrating');
        $jobrecommend = ReviewRating::where('pro_id', $service->user_id)->avg('recommendrating');
        $jobdescribed = ReviewRating::where('pro_id', $service->user_id)->avg('describedrating');
        $jobskill = ReviewRating::where('pro_id', $service->user_id)->avg('skill');
        // job rating
        $prnt = DB::table('seller_detail_skill_parent')->where('service_id', $service->id)->where('user_id', $service->user_id)->groupBy('parent')->get();
        $newextra = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->get();
        $newextrac = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->count();

        $category = Category::where('id', $service->category_id)->first();
        $subcategory = SubCategory::where('id', $service->sub_category_id)->first();
        $childcategory = ChildCategories::where('id', $service->childcat_id)->first();

        $childcategorycount = ChildCategories::where('id', $service->childcat_id)->count();

        return view($this->activeTemplate . 'service_deatils', compact(
            'chats',
            'sid',
            'rid',
            'pageTitle',
            'service',
            'otherServices',
            'totalService',
            'conversion',
            'comments',
            'reviews',
            'serviceGetRating',
            'reviewRataingAvg',
            'workInprogress',
            'serviceDetails',
            'five',
            'four',
            'three',
            'two',
            'one',
            'all',
            'communication',
            'recommend',
            'described',
            'allcount',
            'otherfive',
            'otherfour',
            'otherthree',
            'othertwo',
            'otherone',
            'otherall',
            'othercommunication',
            'otherrecommend',
            'otherdescribed',
            'otherallcount',
            'otherskill',
            'jobfive',
            'jobfour',
            'jobthree',
            'jobtwo',
            'jobone',
            'joball',
            'jobcommunication',
            'jobrecommend',
            'jobdescribed',
            'joballcount',
            'jobskill',
            'prnt',
            'newextra',
            'newextrac',
            'skill',
            'category',
            'subcategory',
            'childcategory',
            'childcategorycount',
            'availableFeatures',
            'userFeatures'
        ));
    }

    public function software()
    {
        $pageTitle = "Software";
        $emptyMessage = "No data found";
        $softwares = Software::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'software', compact('pageTitle', 'softwares', 'emptyMessage'));
    }


    public function softwareDetails($slug, $id)
    {
        $pageTitle = "Software Details";
        $software = Software::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('id', decrypt($id))->firstOrFail();

        $otherServices = Service::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('user_id', $software->user_id)->with('user')->limit(4)->orderBy('id', 'DESC')->get();

        $totalService = Service::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('user_id', $software->user_id)->count();

        $activeUser = Auth::user();
        $softwareGetRating = null;
        $softwareUser = User::where('id', $software->user_id)->firstOrFail();
        if ($activeUser) {
            $softwareGetRating = Booking::where('user_id', $activeUser->id)->where('status', 3)->where('working_status', 1)->where('software_id', $software->id)->first();
        }

        $workInprogress = Booking::where('status', '!=', 0)->where('working_status', 4)->whereHas('service', function ($q) use ($software) {
            $q->where('user_id', $software->user_id);
        })->count();

        $softwareSales = Booking::where('status', 3)->where('working_status', 1)->whereNotNull('software_id')->where('software_id', $software->id)->count();
        $reviewRataingAvg = ReviewRating::whereHas('service', function ($q) use ($software) {
            $q->where('user_id', $software->user_id);
        })->orWhereHas('software', function ($q) use ($software) {
            $q->where('user_id', $software->user_id);
        })->avg('rating');

        $comments = Comment::where('software_id', $software->id)->with('user', 'commentReply')->paginate(7);
        $reviews = ReviewRating::where('software_id', $software->id)->with('user')->paginate(7);
        return view($this->activeTemplate . 'software_details', compact('pageTitle', 'software', 'otherServices', 'totalService', 'comments', 'reviews', 'softwareGetRating', 'softwareSales', 'reviewRataingAvg', 'workInprogress'));
    }

    public function job(Request $request, $category_name = null)
    {
        $user = Auth::user();
        $type = $request->input('type');
        if($type == 'jobs') {
            $job_title = $request->input('search', null);
        }else {
            $job_title = $request->input('job_title', null);
        }

        if ($type == 'professionals') {
            return searchProfessionals($request);
        } else if ($type == 'services') {
            return searchService($request);
        }
        $location = $request->input('location', null);
        $subCateId = $request->input('subcat_id', null);
        $catId = $request->input('cat_id', null);
        $sort = $request->input('sort', 'Date'); // JobTitle,JobPosted
        $pageTitle = "All Jobs";
        // Fetch categories related to jobs using the Category model
        $categories = Category::where('type', 'job')->get();

        // Fetch job data with the related user using Eloquent
        // Assuming each job has a 'user' relationship for the job poster
        $jobs = Job::where('status', 1)
            ->where('job_status',0)
            ->when(!is_null($category_name) && !empty($category_name), function ($query) use ($category_name) {
                $category = Category::where('name', $category_name)->first();
                if (!is_null($category)) {
                    $category_id = $category?->id;
                    $query->where('category_id', $category_id ?? 1);
                }
            })
            ->when(!is_null($job_title) && !empty($job_title), function ($query) use ($job_title) {
                $query->where('title', 'LIKE', "%{$job_title}%");
            })
            ->when(!is_null($location) && !empty($location), function ($query) use ($location) {
                $query->where('country', 'LIKE', "%{$location}%");
            })
            ->when(!is_null($catId) && !empty($catId), function ($query) use ($catId) {
                $query->where('category_id', $catId);
            })
            ->when(!is_null($subCateId) && !empty($subCateId), function ($query) use ($subCateId) {
                $query->where('sub_category_id', $subCateId);
            })
            ->when(!is_null($sort) && !empty($sort), function ($query) use ($sort) {
                if ($sort == 'JobTitle') {
                    $query->orderBy('title', 'asc');
                } else if ($sort == 'Date') {
                    $query->orderBy('created_at', 'desc');
                } else if ($sort == 'JobPosted') {
                    $query->orderBy('created_at', 'desc');
                }
            })
            ->with('user')
            ->withCount('userBiding')
            ->orderBy('id', 'desc') // Eager load the user related to the job
            ->paginate(10)
            ->withQueryString(); // Adjust pagination as needed
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
        return view('templates.basic.job', compact('pageTitle', 'categories', 'jobs', 'countries','user'));
    }

    public function jobListAjax(Request $request)
    {
        // Get all request data
        $data = $request->all();

        // Extract search and page number from request, and set default values
        $search = isset($data['search']) ? $data['search'] : '';
        $page = isset($data['page']) ? (int) $data['page'] : 1;
        $filters = isset($data['filters']) ? $data['filters'] : null;

        $perPage = 10;

        // Query to get jobs with the applied filters and relations
        $jobs = Job::query()
            ->where('status', 1)
            ->where('job_status', 0)
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            });

        if ($filters && isset($filters['categories'])) {
            $categories = $filters['categories']; // ['1', '2']
            if (!empty($categories)) {
                $jobs->whereIn('category_id', $categories);
            }
        }

        if ($search) {
            $jobs->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $jobs = $jobs->with(['user', 'user.rank', 'jobBiding'])
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $jobs->getCollection()->transform(function ($item) {
            $item->encrypted_job_id = Crypt::encrypt($item->id);
            $item->created_date = Carbon::parse($item->created_at)->diffForHumans();
            $item->bid_count = Userbiding::where('job_id', $item->id)->count();

            switch ($item->exp_type) {
                case 1:
                    $item->experience_level = 'Entry Level';
                    break;
                case 2:
                    $item->experience_level = 'Intermediate';
                    break;
                case 3:
                    $item->experience_level = 'Expert';
                    break;
                default:
                    $item->experience_level = 'Unknown';
                    break;
            }

            $item->collective_price_text = $item->budgettype == 'fixed'
                ? "$ " . $item->amount
                : "$ " . $item->minBudget . ' - ' . $item->maxBudget . ' /hr';

            return $item;
        });

        return $jobs;
    }

    public function jobDetails($id)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.authorization');
        }

        $matchingSkills = $this->getUserSkills($user->id);

        $jobId = decrypt($id);
        $pageTitle = "Job Details";
        // Fetch job details and related data in a single query
        $job = Job::with(['user', 'category', 'subCategory', 'jobBiding.user', 'skills', 'attachments'])->findOrFail($jobId);
        $userId = $user->id;
        $otherJobs = Job::where('user_id', $job->user_id)->where('status', 1)->get();
        $userBiding = Userbiding::where(['job_id' => $jobId, 'user_id' => $userId])->first();
        $checkbidexists = $userBiding !== null;
        $isCancel = $userBiding->check_tag ?? 1;
        $bids = $job->userBiding()->count();
        $offers = $job->buyerCounterOfferData()->count();

        $freelancer_service_count = Freelancerservicescount::where('user_id',$userId)->first();
        $featured_job_bids = $freelancer_service_count ? (int) $freelancer_service_count?->featured_job_bids : 0;

        // Get the buyer's profile and reviews
        $buyerProfile = $job->user->getBuyerProfileData($job->user_id);
        $buyerRatings = ReviewRating::where('user_id', $job->user_id)->avg('rating');
        $totalRatings = ReviewRating::where('user_id', $job->user_id)->count();

        $job_user = User::find($job->user_id);

        // Get total available bids for the user
        $totalUserBid = $this->userBidService->getAvailableBids($userId);
        // Return the view with all data

        return view('templates.basic.job_details', compact(
            'otherJobs',
            'pageTitle',
            'offers',
            'job',
            'user',
            'totalUserBid',
            'checkbidexists',
            'userBiding',
            'isCancel',
            'buyerProfile',
            'bids',
            'featured_job_bids',
            'matchingSkills',
            'buyerRatings',
            'totalRatings',
            'job_user'
        ));
    }

    public function jobBiddingfixed($id)
    {
        $editMode = false;
        $pageTitle = "Bid Details";
        $user = Auth::user();
        if (!$user) {
            return redirect()->back();
        }

        $result = $this->membershipService->isUserBiddingAvailable($user->id);

        $matchingSkills = $this->getUserSkills($user->id);

        if (!$result['status']) {
            return back()->withNotify(['error' => $result['message']]);
        }

        $job = Job::with('user', 'user.jobWorkDeliveryBids')->where('status', 1)->find(decrypt($id));
        if (!$job) {
            return redirect()->back();
        }
        $paymentMethod = null;
        $attachments = [];
        $existingBid = Userbiding::where('job_id', $job->id)->where('user_id', $user->id)->first();
        if ($existingBid) {
            $editMode = true;
            $paymentMethod = $existingBid->payment_method ?? null;

            // Retrieve associated milestones if the bid exists
            $milestones = $existingBid->milestones()->get(['title', 'amount', 'due_date']);
            $reviseBidData = json_encode(['user_milestones' => $milestones]);
            // Fetch associated attachments for the bid
            $attachments = $existingBid->attachments;
        } else {
            $reviseBidData = json_encode([]);
        }
        $deliveryDate = Carbon::parse($job->delivery_date);
        $currentDate = Carbon::now();
        $deliveryTimeInDays = ceil($deliveryDate->diffInHours($currentDate) / 24);
        $formattedDeliveryDate = $deliveryDate->format('d M Y');

        $jobSkills = Jobskill::where('job_id', $job->id)->pluck('skill_id');
        // $matchingSkills = Skill::whereIn('id', UserProfileSkill::whereIn('skill_id', $jobSkills)
        //     ->where('user_id', $user->id)->pluck('skill_id'))->get();

        $membershipFee = $this->membershipService->getMembershipFee($user->id);
        $amntPercent = $membershipFee;
        return view($this->activeTemplate . 'job_bidding_fixed', compact(
            'membershipFee',
            'pageTitle',
            'job',
            'user',
            'matchingSkills',
            'deliveryTimeInDays',
            'formattedDeliveryDate',
            'amntPercent',
            'existingBid',
            'paymentMethod',
            'editMode',
            'reviseBidData',  // Pass reviseBidData to the view
            'attachments',
        ));
    }

    public function jobBiddinghourly($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back();
        }

        // Retrieve the job and check its existence
        $job = Job::where('status', 1)->where('id', decrypt($id))->first();
        if (!$job) {
            return redirect()->back();
        }

        $matchingSkills = $this->getUserSkills($user->id);

        // Retrieve job bids
        $jobBidings = JobBiding::where('job_id', $job->id)->with('user')->inRandomOrder()->paginate(5);
        // Get job skills and matching skills for the user
        $jobSkills = Jobskill::where('job_id', $job->id)->pluck('skill_id')->toArray();

        // $matchingSkills = UserProfileSkill::whereIn('skill_id', $jobSkills)
        //     ->where('user_id', $user->id)
        //     ->pluck('skill_id')->toArray();
        // $matchingSkills = Skill::whereIn('id', $matchingSkills)->get();
        $weeklyHours = $job->weekly_hours;
        $formattedDeliveryDate = Carbon::parse($job->delivery_date)->format('d M Y');
        // Get membership fee
        $membershipFee = $this->membershipService->getMembershipFee($user->id);
        $amntPercent = $membershipFee;
        // Send email for bid information
        sendJobEmailWithNotification($user->id, $job->user_id, $job->id, 'NEW_PROPOSAL_RECEIVED');

        // Pass data to the view
        return view('templates.basic.job_bidding_hourly', [
            'pageTitle' => "Job Bidding",
            'job' => $job,
            'jobBidings' => $jobBidings,
            'user' => $user,
            'membershipFee' => $membershipFee,
            'matchingSkills' => $matchingSkills,
            'weeklyHours' => $weeklyHours,
            'formattedDeliveryDate' => $formattedDeliveryDate,
            'amntPercent' => $amntPercent
        ]);
    }
    public function userProfile($username, $id)
    {
        $pageTitle = "User Profile";
        $userId = decrypt($id);

        // Fetch seller profile data
        $sellerData = User::find($userId)->getSellerProfileData($userId);
        $user = $sellerData['user'];
        $isPersonaVerified = $user['persona'];
        $totalServices = $sellerData['services']->count();
        $inprogressJobs = $sellerData['inprogressJobs']->count();
        $completedJobs = $sellerData['completedJobs']->count();
        $services = $sellerData['services'];

        // Fetch reviews with related service
        $reviews = ReviewRating::where('pro_id', $userId)->with('service')->paginate(7);

        // Calculate average rating and review count
        $totalReviews = ReviewRating::where('pro_id', $userId)->count();
        $averageRating = $totalReviews > 0 ? (float) number_format(ReviewRating::where('pro_id', $userId)->avg('rating'), 1) : 0;

        // Star count calculations
        $starCounts = [
            5 => ReviewRating::where('pro_id', $userId)->where('rating', '>=', 5)->count(),
            4 => ReviewRating::where('pro_id', $userId)->where('rating', '>=', 4)->where('rating', '<', 5)->count(),
            3 => ReviewRating::where('pro_id', $userId)->where('rating', '>=', 3)->where('rating', '<', 4)->count(),
            2 => ReviewRating::where('pro_id', $userId)->where('rating', '>=', 2)->where('rating', '<', 3)->count(),
            1 => ReviewRating::where('pro_id', $userId)->where('rating', '<', 2)->count(),
        ];

        // Calculate rating percentages
        $ratingPercentages = [];
        foreach ($starCounts as $stars => $count) {
            $ratingPercentages[$stars] = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
        }

        $data = [
            'totalServices' => $totalServices,
            'inprogressJobs' => $inprogressJobs,
            'completedJobs' => $completedJobs,
            'isSmsVerified' => $user->sv == 1,
            'isEmailVerified' => $user->ev == 1,
            'isVerified' => $user->status == 1,
            'services' => $services,
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
            'starCounts' => $starCounts,
            'ratingPercentages' => $ratingPercentages,
        ];
        return view($this->activeTemplate . 'profile', compact('pageTitle', 'user', 'data'));
    }

    public function userProfilebk($username, $id)
    {
        try {

            $us = Auth::user();
            Log::info('Authenticated user', ['user' => $us]);

            $sid = 0; // Default sender ID
            $chats = [];

            $rid = null;
            try {
                $rid = User::where('id', decrypt($id))->first()->user_id;
                Log::info('Decrypted ID and retrieved receiver ID', ['receiver_id' => $rid]);
            } catch (\Exception $e) {
                Log::error('Failed to decrypt ID or fetch user', ['error' => $e->getMessage()]);
                return back()->withErrors('Invalid user ID');
            }

            if ($us) {
                Log::info('Authenticated user exists', ['user_id' => $us->id]);

                $flag = User::find($us->id);
                $users_ids = $flag->ChatUser($us->id);
                Log::info('Fetched chat user IDs', ['users_ids' => $users_ids]);

                $users = User::whereIn('id', $users_ids)->get();
                Log::info('Fetched users for chat', ['users' => $users]);

                $z = [];
                $sid = $us->id;
                $rid = decrypt($id);

                $user = Chat::where('ch_s_id', $sid)
                    ->where('ch_r_id', $rid)
                    ->get()->pluck('ch_id');
                Log::info('Fetched chats sent by user', ['chat_ids' => $user]);

                foreach ($user as $key) {
                    array_push($z, $key);
                }

                $user1 = Chat::where('ch_s_id', $rid)
                    ->where('ch_r_id', $sid)
                    ->get()->pluck('ch_id');
                Log::info('Fetched chats received by user', ['chat_ids' => $user1]);

                foreach ($user1 as $key) {
                    array_push($z, $key);
                }

                $chats = Chat::whereIn('ch_id', $z)->get();
                Log::info('Fetched chats between users', ['chats' => $chats]);

                foreach ($chats as $key) {
                    $ss = User::where('id', $key->ch_s_id)->first();
                    $key->sender_user = $ss;
                    $rr = User::where('id', $key->ch_r_id)->first();
                    $key->reciver_user = $rr;
                }
            }

            // Fetch additional profile data
            Log::info('Fetching user profile details', ['username' => $username]);
            $pageTitle = "User Profile";
            $activeUser = Auth::user();
            $user = User::where('username', $username)->with('rank')->firstOrFail();
            Log::info('User profile fetched successfully', ['user' => $user]);

            // Fetch review and rating counts
            $five = ReviewRating::where('pro_id', decrypt($id))->where('rating', 5)->count();
            $four = ReviewRating::where('pro_id', decrypt($id))->where('rating', 4)->count();
            // Log review counts
            Log::info('Fetched review ratings', ['five_star' => $five, 'four_star' => $four]);

            // Add additional logs for other queries as needed...

            return view($this->activeTemplate . 'profile', compact(
                'pageTitle',
                'user',
                'chats',
                'five',
                'four'
                // Add other variables as needed
            ));
        } catch (\Exception $e) {
            Log::error('Error in userProfile function', ['error' => $e->getMessage()]);
            return back()->withErrors('An error occurred while loading the profile.');
        }
    }
    public function hireprofessional($id)
    {
        $pageTitle = "Hire Professional";
        $user = Auth::user();
        if ($user == null) {
            return redirect(url('/login'));
        }
        $userId = $user->id;
        $usermembership1 = null;
        if (!empty($user)) {
            if ($user->is_employer == 1) {
                $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
            }
        }
        $getinvites = Employerservicescount::where('user_id', $user->id)->first();
        if (@$getinvites->invites <= 0) {
            $notify[] = ['error', 'You have no invites left to hire professional'];
            return redirect()->back()->withNotify($notify);
        }

        $checkuserisbuyer = User::where('status', 1)->where('id', $user->id)->firstOrFail();
        if ($checkuserisbuyer->is_employer != 1) {
            $notify[] = ['error', 'You cannot hire professional'];
            return redirect()->back()->withNotify($notify);
        }
        // $checkofferexist = Useroffer::where('job_id', decrypt($id))->where('user_id', $user->id)->get();
        // if (count($checkbidexist) > 0) {
        //     $notify[] = ['error', 'You already submitted proposal to this job'];
        //     return redirect(url('/job'))->withNotify($notify);
        // }
        $professional = User::where('status', 1)
            ->where('is_freelancer', 1)
            ->where('per_hour_rate', '!=', '')
            ->where('id', decrypt($id))
            ->first();
        //professional null due to profile incomplete
        $usermembership = null;
        if (!empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $professional->id)->get();
            }
        }
        $buyersPostedJobs = Job::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return view($this->activeTemplate . 'hire_professional', compact('pageTitle', 'professional', 'usermembership', 'buyersPostedJobs', 'userId'));
    }
    public function hireprofessionaledit($id)
    {
        $pageTitle = "Hire Professional Edit";
        $user = Auth::user();

        $checkuserisbuyer = User::where('status', 1)->where('id', $user->id)->firstOrFail();
        if ($checkuserisbuyer->is_employer != 1) {
            $notify[] = ['error', 'You cannot  hire professional'];
            return redirect()->back()->withNotify($notify);
        }
        // $checkofferexist = Useroffer::where('job_id', decrypt($id))->where('user_id', $user->id)->get();
        // if (count($checkbidexist) > 0) {
        //     $notify[] = ['error', 'You already submitted proposal to this job'];
        //     return redirect(url('/job'))->withNotify($notify);
        // }
        $singlejoboffers = UserOffer::where('id', decrypt($id))->first();
        $professional = User::where('status', 1)->where('is_freelancer', 1)->where('per_hour_rate', '!=', '')->where('id', $singlejoboffers->pro_id)->firstOrFail();

        return view($this->activeTemplate . 'hire_professional_edit', compact('pageTitle', 'professional', 'singlejoboffers'));
    }
    public function selectedjob($id, Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $pageTitle = "Hire Professional";
        $professional = User::where('status', 1)->where('is_freelancer', 1)->where('per_hour_rate', '!=', '')->where('id', decrypt($id))->firstOrFail();
        $checkofferexist = UserOffer::where('job_id', $request->hirejob)->where('buyer_id', $user->id)->where('pro_id', $professional->id)->get();
        if (count($checkofferexist) > 0) {
            $notify[] = ['error', 'You already made offer to this professional on this job'];
            return redirect()->back()->withNotify($notify);
        }
        $usermembership = null;
        if (!empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $professional->id)->get();
            }
        }

        $data = $request->hirejob;
        $buyersPostedJobs = Job::where('user_id', $userId)->get();
        return view($this->activeTemplate . 'hire_professional')->with(['pageTitle' => $pageTitle, 'data' => $data, 'professional' => $professional, 'usermembership' => $usermembership, 'buyersPostedJobs' => $buyersPostedJobs]);
    }
    public function userService($username)
    {
        $pageTitle = "User Profile";
        $activeUser = Auth::user();
        $user = User::where('username', $username)->with('rank')->firstOrFail();
        $reviewCal = ReviewRating::whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orWhereHas('software', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $reviewCount = $reviewCal->count();
        $reviewAvg = $reviewCal->avg('rating');

        $workCompleteCount = Booking::where('status', 3)->where('working_status', 1)->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $workPendingCount = Booking::where('status', 1)->where('working_status', 0)->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $userServices = Service::where('user_id', $user->id)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->paginate(getPaginate());
        return view($this->activeTemplate . 'user_service', compact('pageTitle', 'user', 'reviewCount', 'reviewAvg', 'workCompleteCount', 'workPendingCount', 'workPendingCount', 'userServices'));
    }
    //inserting job offer
    public function addofferfixed(Request $request)
    {

        $user = Auth::user();

        $amount = $request->milestone_amount;
        $total = 0;
        foreach ($amount as $key => $new) {
            $total += $amount[$key];
        }
        $job = Job::where('id', $request->job_id)->first();
        if ($total > $user->balance) {
            $notify[] = ['error', 'You do not have enough money.deposit money in your account to process this request.'];
            return redirect(url('/professionals'))->withNotify($notify);
        }
        //dd($request);
        // print_r($request->all());exit;
        $this->validate($request, [

            'milestine_proposal' => 'required',
        ]);

        $binding = new UserOffer();
        $binding->job_id = $request->job_id;
        $binding->job_type = $request->job_type;
        $binding->buyer_id = $user->id;
        $binding->bid_price = '0';
        $binding->cutout = $request->fee;
        $binding->order_number = getTrx();
        $binding->proposal = $request->milestine_proposal;
        $binding->pro_id = $request->pro_id;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('milestone_attachment')) {
            $file = $request->milestone_attachment;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $binding->file = $filename;
        }



        $done = $binding->save();
        if ($done) {
            $title = $request->milestone_title;
            $amount = $request->milestone_amount;
            $date = $request->milestone_due_date;
            foreach ($title as $key => $new) {
                $ms = new UserOfferMilestone();
                $ms->offer_id = $binding->id;
                $ms->title = $title[$key];
                $ms->due_date = $date[$key];
                $ms->amount = $amount[$key];
                $ms->save();
            }
        }
        //Vikash Change
        $getJobName = Job::where('id', $request->job_id)->first();
        // if ($getJobName) {
        //     $noti = new Notification();
        //     $noti->reciever_id = $request->pro_id;
        //     $noti->sender_id = $user->id;
        //     $noti->type = 'job_offer';
        //     $noti->job_id = $getJobName->id;
        //     $noti->save();

        //     sendRealTimeNotification($request->pro_id, 'job_offer');
        // }
        //decreament of bid after biding
        // $getJobName = Job::where('id', $request->job_id)->first();
        // $biddeduct = Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);

        // $getbids = Freelancerservicescount::where('user_id', $user->id)->first();

        // $transaction_arr[] = [
        //     'user_id' => auth()->user()->id,
        //     'bids' => $biddeduct,
        //     'tr_op' => '-',
        //     'post_balance' => $getbids->bids,
        //     'details' => 'Bid on ' . $getJobName->tittle,

        // ];
        // BidsLog::insert($transaction_arr);

        $usermembership1 = null;
        if (!empty($user)) {
            if ($user->is_employer == 1) {
                $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
            }
        }
        if ($usermembership1[0]->invites != 'unlimited') {
            $getinvites = Employerservicescount::where('user_id', $user->id)->first();
            // if($getinvites->invites <= 0){
            //  $notify[] = ['error', 'You have no ivites left to hire professional'];
            //  return redirect()->back()->withNotify($notify);
            // }
            $invites = $getinvites->invites;
            $up = Employerservicescount::find($getinvites->id);
            $up->invites = $invites - 1;
            $up->save();
        }

        // send email and notification - sender, receiver, job id, type
        sendJobEmailWithNotification($user->id, $request->pro_id, $getJobName->id, 'NEW_PROPOSAL_RECEIVED');

        // // send email
        // $sender = DB::table('users')->where('id', $user->id)->first();
        // $reciever = DB::table('users')->where('id', $request->pro_id)->first();

        // // send email
        // sendEmail($reciever, 'NEW_PROPOSAL_RECEIVED', [
        //     "sender_name" => $sender->username,
        //     "date_received" => Carbon::now()->format("Y-m-d H:i:s"),
        //     "project_name" => $getJobName->title
        // ]);
        // send email
        $notify[] = ['success', 'Proposal successfully submitted'];
        return redirect(url('/user/buyer/dashboard'))->withNotify($notify);
    }
    // edit offer
    public function addofferfixedupdate(Request $request, $id)
    {

        $user = Auth::user();
        $amount = $request->milestone_amount;
        $total = 0;
        foreach ($amount as $key => $new) {
            $total += $amount[$key];
        }
        $job = Job::where('id', $request->job_id)->first();
        if ($total > $user->balance) {
            $notify[] = ['error', 'You do not have enough money.deposit money in your account to process this request.'];
            return redirect(url('/professionals'))->withNotify($notify);
        }
        //dd($request);
        // print_r($request->all());exit;
        $this->validate($request, [

            'milestine_proposal' => 'required',
        ]);

        $binding = UserOffer::findOrFail(decrypt($id));
        $binding->job_id = $request->job_id;
        $binding->job_type = $request->job_type;
        $binding->buyer_id = $user->id;
        $binding->bid_price = '0';
        $binding->cutout = '0';
        $binding->is_cancel_offer = '0';
        $binding->proposal = $request->milestine_proposal;
        $binding->pro_id = $request->pro_id;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('milestone_attachment')) {
            $file = $request->milestone_attachment;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $binding->file = $filename;
        }



        $done = $binding->save();
        if ($done) {
            $ums = UserOfferMilestone::where('offer_id', decrypt($id))->get();
            foreach ($ums as $data) {
                $datadel = UserOfferMilestone::find($data->id);
                $datadel->delete();
            }
            $title = $request->milestone_title;
            $amount = $request->milestone_amount;
            $date = $request->milestone_due_date;
            foreach ($title as $key => $new) {
                $ms = new UserOfferMilestone();
                $ms->offer_id = $binding->id;
                $ms->title = $title[$key];
                $ms->due_date = $date[$key];
                $ms->amount = $amount[$key];
                $ms->save();
            }
        }
        $getJobName = Job::where('id', $request->job_id)->first();
        if ($getJobName) {
            $noti = new Notification();
            $noti->reciever_id = $request->pro_id;
            $noti->sender_id = $user->id;
            $noti->type = 'job_offer';
            $noti->job_id = $getJobName->id;
            $noti->save();

            sendRealTimeNotification($request->pro_id, 'job_offer');
        }
        //decreament of bid after biding
        // $getJobName = Job::where('id', $request->job_id)->first();
        // $biddeduct = Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);

        // $getbids = Freelancerservicescount::where('user_id', $user->id)->first();

        // $transaction_arr[] = [
        //     'user_id' => auth()->user()->id,
        //     'bids' => $biddeduct,
        //     'tr_op' => '-',
        //     'post_balance' => $getbids->bids,
        //     'details' => 'Bid on ' . $getJobName->tittle,

        // ];
        // BidsLog::insert($transaction_arr);


        $notify[] = ['success', 'Proposal successfully updated'];
        return redirect(url('/user/buyer/dashboard'))->withNotify($notify);
    }
    public function addofferhourlyupdate(Request $request, $id)
    {
        // print_r($request->all());exit;
        $user = Auth::user();
        $job = Job::where('id', $request->job_id)->first();
        $totalamount = $request->bid_price * $request->hour_per_week;
        if ($totalamount > $user->balance) {
            $notify[] = ['error', 'You do not have enough money.deposit money in your account to process this request.'];
            return redirect(url('/professionals'))->withNotify($notify);
        }
        //dd($request);
        // print_r($request->all());exit;
        $this->validate($request, [
            'bid_price' => 'required',
            'hour_per_week' => 'required',
            'depositfund' => 'required',
            'proposal' => 'required',
        ]);

        $binding = UserOffer::findOrFail(decrypt($id));
        $binding->job_id = $request->job_id;
        $binding->hourlyrate = $request->bid_price;
        $binding->hours = $request->hour_per_week;
        $binding->deposit = $request->depositfund;
        $binding->job_type = $request->job_type;
        $binding->title = $request->title;
        $binding->buyer_id = $user->id;
        $binding->bid_price = '0';
        $binding->cutout = '0';
        $binding->is_cancel_offer = '0';
        $binding->proposal = $request->proposal;
        $binding->pro_id = $request->pro_id;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $binding->file = $filename;
        }



        $done = $binding->save();
        $getJobName = Job::where('id', $request->job_id)->first();
        if ($getJobName) {
            $noti = new Notification();
            $noti->reciever_id = $getJobName->user_id;
            $noti->sender_id = $user->id;
            $noti->type = 'job_offer';
            $noti->job_id = $getJobName->id;
            $noti->save();

            sendRealTimeNotification($getJobName->user_id, 'job_offer');
        }
        //decreament of bid after biding
        // $getJobName = Job::where('id', $request->job_id)->first();
        // $biddeduct = Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);

        // $getbids = Freelancerservicescount::where('user_id', $user->id)->first();

        // $transaction_arr[] = [
        //     'user_id' => auth()->user()->id,
        //     'bids' => $biddeduct,
        //     'tr_op' => '-',
        //     'post_balance' => $getbids->bids,
        //     'details' => 'Bid on ' . $getJobName->tittle,

        // ];
        // BidsLog::insert($transaction_arr);


        $notify[] = ['success', 'Proposal successfully Updated'];
        return redirect(url('/user/buyer/dashboard'))->withNotify($notify);
    }

    //inserting job biding
    public function addbidinghourly(Request $request, $encryptedJobId)
    {
        $this->validate($request, [
            'bid_price' => 'required',
            'proposal' => 'required',

        ]);

        $jobId = decrypt($encryptedJobId);
        $job = Job::where('id', $jobId)->first();

        $user = Auth::user();

        $freelancerServicesCount = Freelancerservicescount::where('user_id', $user->id)->first();
        $biddeduct = 0;
        if($job->priority == 1) {
            if ($freelancerServicesCount && $freelancerServicesCount->bids > 0) {
                $freelancerServicesCount->bids -= 1;
                $freelancerServicesCount->save();
                $biddeduct = 1;
            } else {
                $notify[] = ['error', 'Your bids are already at 0 , Please update your membership'];
                return back()->withNotify($notify)->withInput();
            }
        }else {
            if ($freelancerServicesCount && $freelancerServicesCount->bids > 0) {
                $freelancerServicesCount->featured_job_bids -= 1;
                $freelancerServicesCount->save();
                $biddeduct = 1;
            } else {
                $notify[] = ['error', 'Your featured bids are already at 0 , Please update your membership'];
                return back()->withNotify($notify)->withInput();
            }
        }

        $binding = new Userbiding();
        $binding->job_id = $jobId;
        $binding->user_id = $user->id;
        $binding->job_type = 'hourly';
        $binding->bid_price = $request->bid_price;
        $binding->title = $request->title;
        $binding->proposal = $request->proposal;
        $binding->cutout = $request->cutfix;
        $binding->order_number = getTrx();
        $binding->created_date = date('Y-m-d');
        $binding->created_time = date('H:i:s');
        $binding->check_tag = 0;
        $binding->seller_offer_responce = 0;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            // Handle the file upload
            $binding->file = uploadFile($request->file('file'), 'user_bidding');
        }
        $binding->save();
        //decreament of bid after biding

        // $getbids = Freelancerservicescount::where('user_id', $user->id)->first();
        // // return $getbids;
        // $biddeduct = Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);
        $transaction_arr[] = [
            'user_id' => auth()->user()->id,
            'bids' => $biddeduct,
            'tr_op' => '-',
            'post_balance' => $freelancerServicesCount->bids,
            'details' => 'Bid on ' . $job->tittle,
        ];

        BidsLog::insert($transaction_arr);


        // send email
        // send email and notification - sender, receiver, job id, type
        // sendJobEmailWithNotification($user->id, $job->user_id, $request->job_id, 'NEW_PROPOSAL_RECEIVED');
        // send email

        try {
            sendJobEmailWithNotification($user->id, $job->user_id, $jobId, 'NEW_PROPOSAL_RECEIVED');
        } catch (\Exception $e) {
            Log::warning('Notification error: ' . $e->getMessage());
        }


        $notify[] = ['success', 'Proposal successfully submitted'];
        return redirect(url('/user/seller/dashboard'))->withNotify($notify);
    }


    public function userSoftware($username)
    {
        $pageTitle = "User Profile";
        $activeUser = Auth::user();
        $user = User::where('username', $username)->firstOrFail();
        $reviewCal = ReviewRating::whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orWhereHas('software', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $reviewCount = $reviewCal->count();
        $reviewAvg = $reviewCal->avg('rating');

        $workCompleteCount = Booking::where('status', 3)->where('working_status', 1)->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $workPendingCount = Booking::where('status', 1)->where('working_status', 0)->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $userSoftwares = Software::where('user_id', $user->id)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->paginate(getPaginate());
        return view($this->activeTemplate . 'user_software', compact('pageTitle', 'user', 'reviewCount', 'reviewAvg', 'workCompleteCount', 'workPendingCount', 'workPendingCount', 'userSoftwares'));
    }


    public function userJob($username)
    {
        $pageTitle = "User Profile";
        $activeUser = Auth::user();
        $user = User::where('username', $username)->firstOrFail();
        $reviewCal = ReviewRating::whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orWhereHas('software', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $reviewCount = $reviewCal->count();
        $reviewAvg = $reviewCal->avg('rating');

        $workCompleteCount = Booking::where('status', 3)->where('working_status', 1)->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $workPendingCount = Booking::where('status', 1)->where('working_status', 0)->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $userJobs = Job::where('user_id', $user->id)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank')->paginate(getPaginate());
        return view($this->activeTemplate . 'user_job', compact('pageTitle', 'user', 'reviewCount', 'reviewAvg', 'workCompleteCount', 'workPendingCount', 'workPendingCount', 'userJobs'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        return view($this->activeTemplate . 'contact', compact('pageTitle'));
    }

    public function contactSubmit(Request $request)
    {

        $attachments = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->id() ?? 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language)
            $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blog()
    {
        $blogs = Frontend::where('data_keys', 'blog.element')->paginate(getPaginate());
        $pageTitle = "Blog Post";
        return view($this->activeTemplate . 'blog', compact('blogs', 'pageTitle'));
    }

    public function blogDetails($id, $slug)
    {
        $fservices = Service::where('status', 1)->where('featured', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->paginate(getPaginate(4));
        $blog = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = "Blog Details";
        return view($this->activeTemplate . 'blog_details', compact('blog', 'pageTitle', 'fservices'));
    }


    public function cookieAccept()
    {
        session()->put('cookie_accepted', true);
        $notify[] = ['success', 'Cookie accepted successfully'];
        return back()->withNotify($notify);
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX = ($imgWidth - $textWidth) / 2;
        $textY = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function adclicked($id)
    {
        $ads = Advertise::where('id', decrypt($id))->firstOrFail();
        $ads->click += 1;
        $ads->save();
        return redirect($ads->redirect_url);
    }


    public function footerMenu($slug, $id)
    {
        $data = Frontend::where('id', $id)->where('data_keys', 'footer.element')->firstOrFail();
        $pageTitle = $data->data_values->menu;
        return view($this->activeTemplate . 'menu', compact('data', 'pageTitle'));
    }


    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $if_exist = Subscriber::where('email', $request->email)->first();
        if (!$if_exist) {
            Subscriber::create([
                'email' => $request->email
            ]);
            return response()->json(['success' => 'Subscribed Successfully']);
        } else {
            return response()->json(['error' => 'Already Subscribed']);
        }
    }
    public function getunreadmessagecount()
    {
        $user = Auth::user();
        if (!empty($user)) {
            //not handle both employer and freelancer at a time ,in future

            $message = Message::where('receiver_id', $user->id)->where('read', 0)->count();
            return ["message_count" => $message];
        }
    }
    public function allnotifications(Request $request)
    {
        $pageTitle = 'Notifications';
        $search = $request->input('search', null);
        $type = $request->input('type', 'all');


        // Fetch all notifications for the authenticated user
        $notifications = DB::table('notifications')
            ->where('notifiable_id', '=', Auth::user()->id)
            ->when($type === 'latest', function ($query) {
                $query->whereNull('read_at');
            })
            ->when($type === 'prior', function ($query) {
                $query->whereNotNull('read_at');
            })
            ->when($type === 'all', function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('read_at')
                        ->orWhereNotNull('read_at');
                });
            })
            ->when(!is_null($search) && !empty($search), function ($query) use ($search) {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.message'))) LIKE ?", ['%' . strtolower($search) . '%']);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                $notification->data = json_decode($notification->data);
                return $notification;
            });

        // Breadcrumbs
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Notifications', 'url' => ''],
        ];

        return view('chat.notification', compact('pageTitle', 'notifications', 'breadcrumbs'));
    }

    public function updallnotifications()
    {
        \DB::table('notifications')
            ->where('notifiable_id', Auth::user()->id)
            ->whereNull('read_at') // Only update unread notifications
            ->update(['read_at' => now()]);

        // Notify the user of successful operation
        $notify[] = ['success', 'All notifications marked as read.'];
        return redirect()->route('all-notifications')->withNotify($notify);
    }


    public function getMembership($id)
    {


        $user = Auth::user();
        $pageTitle = "Hourly Bidding Revise";
        $job = Job::where('id', decrypt($id2))->first();
        $bid = UserBiding::where('job_id', decrypt($id2))->where('id', decrypt($id1))->first();
        $jobBidings = JobBiding::where('job_id', $job->id)->with('user')->inRandomOrder()->paginate(5);
        $usermembership = null;
        if (!empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            }
        }
        $userMem = getMembership();
        return view($this->activeTemplate . 'job_revisebidding_hourly', compact('pageTitle', 'bid', 'job', 'usermembership', 'jobBidings'));
    }
    public function jobreviseBiddinghourly($id1, $id2)
    {
        try {
            $user = Auth::user();
            $pageTitle = "Hourly Bidding Revise";
            $job = Job::where('id', decrypt($id2))->firstOrFail();
            $bid = UserBiding::where('job_id', decrypt($id2))->where('id', decrypt($id1))->firstOrFail();
            $jobBidings = JobBiding::where('job_id', $job->id)->with('user')->inRandomOrder()->paginate(5);
            $usermembership = null;

            if (!empty($user) && $user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            }

            return view($this->activeTemplate . 'job_revisebidding_hourly', compact('pageTitle', 'bid', 'job', 'usermembership', 'jobBidings'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Return an error response for debugging
        }
    }

    public function jobreviseBiddingmilestone($id1, $id2)
    {


        $user = Auth::user();
        $pageTitle = "Milestone Bidding Revise";
        $job = Job::where('id', decrypt($id2))->first();
        $bid = UserBiding::where('job_id', decrypt($id2))->where('id', decrypt($id1))->first();
        $jobBidings = JobBiding::where('job_id', $job->id)->with('user')->inRandomOrder()->paginate(5);
        $usermembership = null;
        if (!empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            }
        }
        return view($this->activeTemplate . 'job_revisebidding_milestone', compact('pageTitle', 'bid', 'job', 'usermembership', 'jobBidings'));
    }
    public function submitrevisebidingproject(Request $request, $id)
    {
        // dd($request);

        $this->validate($request, [
            'fee' => 'required',
            'bid_price' => 'required',
            'job_price' => 'required',
            'proposal' => 'required',
            // 'title' => 'required',
            // 'attachment' => ['required', new FileTypeValidate(['zip'])],
        ]);
        $user = Auth::user();
        $binding = Userbiding::find(decrypt($id));
        $binding->job_id = $request->job_id;
        $binding->title = $request->title;
        $binding->job_type = $request->projectmethod;
        $binding->due_date = $request->due_date;
        $binding->user_id = $user->id;
        $binding->bid_price = $request->bid_price;
        $binding->cutout = $request->fee;
        $binding->proposal = $request->proposal;
        $binding->is_cancel_bid = 0;
        $binding->created_date = date('Y-m-d');
        $binding->created_time = date('H:i:s');
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('attachment')) {
            $file = $request->attachment;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $binding->file = $filename;
        }



        $binding->save();
        //decreament of bid after biding
        $getJobName = Job::where('id', $request->job_id)->first();

        // if ($getJobName) {
        //     $noti = new Notification();
        //     $noti->reciever_id = $getJobName->user_id;
        //     $noti->sender_id = $user->id;
        //     $noti->type = 'revise_bid';
        //     $noti->job_id = $getJobName->id;
        //     $noti->save();
        //     sendRealTimeNotification($getJobName->user_id, 'revise_bid');
        // }


        $biddeduct = Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);

        $getbids = Freelancerservicescount::where('user_id', $user->id)->first();

        $transaction_arr[] = [
            'user_id' => auth()->user()->id,
            'bids' => $biddeduct,
            'tr_op' => '-',
            'post_balance' => $getbids->bids,
            'details' => 'Bid on ' . $getJobName->title,

        ];
        BidsLog::insert($transaction_arr);


        // send email and notification - sender, receiver, job id, type
        sendJobEmailWithNotification($user->id, $getJobName->user_id, $getJobName->id, 'NEW_PROPOSAL_RECEIVED');


        // Revice Bid and send email again
        // NEW_PROPOSAL_RECEIVED
        // sendEmail($binding->job->user, 'NEW_PROPOSAL_RECEIVED', [
        //     "sender_name" => $binding->user->username,
        //     "date_received" => Carbon::now()->format("Y-m-d H:i:s"),
        //     "project_name" => $binding->job->title
        // ]);

        $notify[] = ['success', 'Proposal successfully Revised'];
        return redirect(url('/user/seller/dashboard'))->withNotify($notify);
    }
    public function submitrevisebidingmilestone(Request $request, $id)
    {
        // dd($id);
        $this->validate($request, [

            'milestone_attachment' => ['required', new FileTypeValidate(['zip'])],
        ]);
        $user = Auth::user();
        $binding = Userbiding::find(decrypt($id));
        $binding->job_id = $request->milestone_job;
        $binding->job_type = $request->projectmethod;
        $binding->user_id = $user->id;
        $binding->bid_price = '0';
        $binding->cutout = $request->platformfeemulti;
        $binding->proposal = $request->milestine_proposal;
        $binding->created_date = date('Y-m-d');
        $binding->created_time = date('H:i:s');
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('milestone_attachment')) {
            $file = $request->milestone_attachment;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $binding->file = $filename;
        }



        $done = $binding->save();
        if ($done) {
            $ums = UserMilestone::where('bid_id', decrypt($id))->get();
            foreach ($ums as $data) {
                $datadel = UserMilestone::find($data->id);
                $datadel->delete();
            }

            $title = $request->milestone_title;
            $amount = $request->milestone_amount;
            $date = $request->milestone_due_date;
            foreach ($title as $key => $new) {
                $ms = new UserMilestone();
                $ms->bid_id = $binding->id;
                if (!empty($title[$key])) {
                    $ms->title = $title[$key];
                }
                if (!empty($date[$key])) {
                    $ms->due_date = $date[$key];
                }
                if (!empty($amount[$key])) {
                    $ms->amount = $amount[$key];
                }
                $ms->save();
            }
        }
        //decreament of bid after biding
        $getJobName = Job::where('id', $request->milestone_job)->first();
        if ($getJobName) {
            $noti = new Notification();
            $noti->reciever_id = $getJobName->user_id;
            $noti->sender_id = $user->id;
            $noti->type = 'revise_bid';
            $noti->job_id = $getJobName->id;
            $noti->save();

            sendRealTimeNotification($getJobName->user_id, 'revise_bid');
        }
        $biddeduct = Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);

        $getbids = Freelancerservicescount::where('user_id', $user->id)->first();

        $transaction_arr[] = [
            'user_id' => auth()->user()->id,
            'bids' => $biddeduct,
            'tr_op' => '-',
            'post_balance' => $getbids->bids,
            'details' => 'Bid on ' . $getJobName->tittle,

        ];
        BidsLog::insert($transaction_arr);


        $notify[] = ['success', 'Proposal successfully Revised'];
        return redirect(url('/user/seller/dashboard'))->withNotify($notify);
    }
    public function submitrevisebidinghourly(Request $request, $id)
    {
        $this->validate($request, [
            'bid_price' => 'required',
            'proposal' => 'required',
        ]);
        $user = Auth::user();
        $binding = Userbiding::find(decrypt($id));
        $binding->job_id = $request->job_id;
        $binding->user_id = $user->id;
        $binding->job_type = $request->projectmethod;
        $binding->bid_price = $request->bid_price;
        $binding->title = $request->title;
        $binding->proposal = $request->proposal;
        $binding->created_date = date('Y-m-d');
        $binding->created_time = date('H:i:s');
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $binding->file = $filename;
        }



        $binding->save();
        //decreament of bid after biding
        $getJobName = Job::where('id', $request->job_id)->first();
        if ($getJobName) {
            $noti = new Notification();
            $noti->reciever_id = $getJobName->user_id;
            $noti->sender_id = $user->id;
            $noti->type = 'revise_bid';
            $noti->job_id = $getJobName->id;
            $noti->save();

            sendRealTimeNotification($getJobName->user_id, 'revise_bid');
        }
        $biddeduct = Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);

        $getbids = Freelancerservicescount::where('user_id', $user->id)->first();

        $transaction_arr[] = [
            'user_id' => auth()->user()->id,
            'bids' => $biddeduct,
            'tr_op' => '-',
            'post_balance' => $getbids->bids,
            'details' => 'Bid on ' . $getJobName->tittle,

        ];
        BidsLog::insert($transaction_arr);
        $notify[] = ['success', 'Proposal successfully revised'];
        return redirect(url('/user/seller/dashboard'))->withNotify($notify);
    }
    public function generateInvoicePDF($id)
    {

        $pageTitle = 'Download Invoice';
        $singlejob = Job::where('id', $id)->first();
        $acceptedbid = Userbiding::where('job_id', $id)->where('seller_offer_responce', '2')->first();
        $accepteddata = Acceptedbid::where('job_id', $id)->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();
        $pdf = PDF::loadView('downloadinvoice', compact('singlejob', 'acceptedbid', 'accepteddata', 'pageTitle'));

        return $pdf->download('nicesnippets.pdf');
    }
    public function generateInvoicePDFoffer($id)
    {
        $pageTitle = 'Download Invoice';
        $singlejob = Job::where('id', $id)->first();
        $acceptedoffer = UserOffer::where('job_id', $id)->where('buyer_offer_responce', '2')->first();
        $accepteddata = Acceptedoffer::where('job_id', $id)->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();
        $pdf = PDF::loadView('downloadinvoiceoffer', compact('singlejob', 'acceptedoffer', 'accepteddata', 'pageTitle'));

        return $pdf->download('nicesnippets.pdf');
    }


    public function generateInvoicePDFtwo($id)
    {
        $pageTitle = 'Download Invoice';
        $singlejob = Job::where('id', $id)->first();
        // print_r($singlejob);exit;
        $acceptedbid = Userbiding::where('job_id', $id)->where('seller_offer_responce', '2')->first();
        $accepteddata = Acceptedbid::where('job_id', $id)->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();
        $pdf = PDF::loadView('downloadinvoiceseller', compact('singlejob', 'acceptedbid', 'accepteddata', 'pageTitle'));

        return $pdf->download('nicesnippets.pdf');
    }
    public function generateInvoicePDFoffertwo($id)
    {
        $pageTitle = 'Download Invoice';
        $singlejob = Job::where('id', $id)->first();
        $acceptedoffer = UserOffer::where('job_id', $id)->where('buyer_offer_responce', '2')->first();
        $accepteddata = Acceptedoffer::where('job_id', $id)->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();
        $pdf = PDF::loadView('downloadinvoiceofferseller', compact('singlejob', 'acceptedoffer', 'accepteddata', 'pageTitle'));

        return $pdf->download('nicesnippets.pdf');
    }
    public function generateInvoicePDFserviceseller($id)
    {

        $pageTitle = 'Download Invoice';
        $booking = Booking::where('id', $id)->firstOrFail();
        $pdf = PDF::loadView('downloadinvoicesellerservice', compact('booking', 'pageTitle'));

        return $pdf->download('nicesnippets.pdf');
    }
    public function generateInvoicePDFservicebuyer($id)
    {

        $pageTitle = 'Download Invoice';
        $booking = Booking::where('id', $id)->firstOrFail();
        $pdf = PDF::loadView('downloadinvoicebuyerservice', compact('booking', 'pageTitle'));

        return $pdf->download('nicesnippets.pdf');
    }
    public function changeCurrency($currency = NULL)
    {
        // echo $currency;exit;
        if ($currency == NULL) {
            $currency = 'THB';
        }

        $symbolData = DB::table('currencies')->where('code', $currency)->first();
        Session()->put('currency', $currency);
        Session()->put('currency_symbol', $symbolData->symbol);
        Session::save();
        return redirect()->back();
    }
    public function handle()
    {
        $thb_rates = json_decode(file_get_contents('https://v6.exchangerate-api.com/v6/78a6747b51e1b9b498c3728e/latest/USD'));
        $datetime = date('Y-m-d', $thb_rates->time_last_update_unix);
        $rates = json_encode($thb_rates->conversion_rates);
        $insert_array = [
            'date' => $datetime,
            'rates' => $rates,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        DB::table('currency_rates')->insert($insert_array);
    }

    public function buying_profile(Request $request)
    {
        $postData = $request->all();

        // echo"<pre>"; print_r($postData);exit;
        $user = Auth::user();
        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['about_me'] = $request->about_me;
        $in['email'] = $request->email;
        $in['status'] = 2;

        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        // if ($request->hasFile('bg_image')) {
        //     $location = imagePath()['profile']['user_bg']['path'];
        //     $size = imagePath()['profile']['user_bg']['size'];
        //     $filename = uploadImage($request->bg_image, $location, $size, $user->bg_image);
        //     $in['bg_image'] = $filename;
        // }
        $e = $user->fill($in)->save();


        $lan = new UserCompany();
        $lan->companyname = $request->companyname;
        $lan->companyweb = $request->companyweb;
        $lan->companyind = $request->companyind;
        $lan->director = $request->director;
        $lan->user_id = $user->id;
        $lan->contact = $request->contact;
        $lan->timezone = $request->timezone;
        $lan->country = $request->country;
        $lan->state = $request->state;
        $lan->zip = $request->zip;
        $lan->address = $request->address;
        $lan->companyname = $request->companyname;
        $lan->companyname = $request->companyname;
        $lan->companyname = $request->companyname;

        $lan->save();

        $alert = new UserProfileAlerts();
        // $alert->jobposted = $request->jobposted;
        // $alert->offermodified = $request->offermodified;
        // $alert->proposalrecieved = $request->proposalrecieved;
        // $alert->jobpostexpiresoon = $request->jobpostexpiresoon;
        // $alert->jobpostexpired = $request->jobpostexpired;
        // $alert->contractstart = $request->contractstart;
        // $alert->contractmodify = $request->contractmodify;
        // $alert->contractend = $request->contractend;
        // $alert->offerdecline = $request->offerdecline;
        // $alert->offeraccept = $request->offeraccept;
        $alert->Servicebookaccept_buyer = $request->Servicebookaccept_buyer;
        $alert->Servicebookwork_buyer = $request->Servicebookwork_buyer;
        $alert->Servicebookdisputeoffer_buyer = $request->Servicebookdisputeoffer_buyer;
        $alert->Servicebookdisputeofferyes_buyer = $request->Servicebookdisputeofferyes_buyer;
        $alert->Servicebookdisputeofferno_buyer = $request->Servicebookdisputeofferno_buyer;
        $alert->Servicebookdisputeadmin_buyer = $request->Servicebookdisputeadmin_buyer;
        $alert->Servicebookrr_buyer = $request->Servicebookrr_buyer;

        $alert->jobreview_buyer = $request->jobreview_buyer;
        $alert->jobdisputeadmin_buyer = $request->jobdisputeadmin_buyer;
        $alert->jobdisputeofferno_buyer = $request->jobdisputeofferno_buyer;
        $alert->jobdisputeofferyes_buyer = $request->jobdisputeofferyes_buyer;
        $alert->jobdisputeoffer_buyer = $request->jobdisputeoffer_buyer;
        $alert->jobdispute_buyer = $request->jobdispute_buyer;
        $alert->jobworkno_buyer = $request->jobworkno_buyer;
        $alert->jobworkyes_buyer = $request->jobworkyes_buyer;
        $alert->jobcounterbid_buyer = $request->jobcounterbid_buyer;
        $alert->joboffercounteroffer_buyer = $request->joboffercounteroffer_buyer;
        $alert->joboffer_buyer = $request->joboffer_buyer;
        $alert->type = '1';
        $alert->user_id = $user->id;
        $alert->save();

        $notify[] = ['success', 'Buyer Profile added successfully.'];
        return redirect()->route('user.buyer.dashboard')->withNotify($notify);
    }
    public function updateBuyerPersonalInfo(Request $request, $id)
    {
        session()->flash('activeStep', 1);
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $data = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'city' => $request->city,
        ];
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->email = $request['email'];
        $user->country = $request['country'];
        $user->country_code = $request['country'];
        $user->address = $data;
        $user->about_me = $request->about_me;
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $fileName = time() . '-' . $image->getClientOriginalName();
            $filePath = $image->storeAs('profile_pictures', $fileName, 'public');
            $user->image = $filePath;
        }

        if ($user->completed_step < 1) {
            $user->completed_step = 1;
        }

        $user->save();

        // $company = UserCompany::firstOrNew(['user_id' => $user->id]);
        // $company->country = $request->country;
        // $company->save();

        // Save the company record
        $notify[] = ['success', 'Buyer Personal Info updated successfully.'];
        return redirect()->back()->withNotify($notify)->with('activeStep', 1);
    }

    public function updateBuyerBusinessInfo(Request $request)
    {
        session()->flash('activeStep', 3);
        $request->validate([
            'companyname' => 'required|string|max:100',
            'companyweb' => [
                'nullable',
                'string',
                'regex:/^((https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,})(\/.*)?$/'
            ],
            'companyind' => 'required|string|max:100',
            'description' => 'nullable|string',
        ], [
            'companyweb.regex' => 'The website URL must include "www" and end with ".com"',
        ]);

        $user = Auth::user();
        $company = UserCompany::firstOrNew(['user_id' => $user->id]);

        // Update company details
        $company->companyname = $request->companyname;
        $company->companyweb = $request->companyweb;
        $company->companyind = $request->companyind;
        $company->description = $request->description;
        if ($user->completed_step <= 2) {
            $user->completed_step = 3;
            $user->save();
        }
        $company->save();
        $notify[] = ['success', 'Buyer Business Info updated successfully.'];
        return redirect()->back()->withNotify($notify)->with('activeStep', 3);
    }

    public function updateBuyerBusinessContact(Request $request)
    {
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $mobileCodes = implode(',', array_column($countryData, 'dial_code'));
        $countryCodes = implode(',', array_keys($countryData));

        session()->flash('activeStep', 4);
        // Validate the request inputs
        $request->validate([
            'director' => 'required|string|max:100',
            'contact' => 'required', // Ensure valid phone number length
            'mbl_no' => 'required|in:' . $mobileCodes, // Ensure valid phone number length
            'timezone' => 'required|string|max:100',
            'bus_country' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country_code' => 'required|in:' . $countryCodes,
        ], [
            'director.required' => 'The Director/Proprietor field is required.',
            'contact.required' => 'The Contact Number field is required.',
            'timezone.required' => 'The Time Zone field is required.',
            'bus_country.required' => 'The Country field is required.',
            'country_code.required' => 'The Country Code field is required.',
        ]);
        // Retrieve the authenticated user
        $user = Auth::user();
        // $user->country_code = $request->country_code;
        // $user->save();

        // Retrieve or create the UserCompany record
        $company = UserCompany::firstOrNew(['user_id' => $user->id]);

        // Update the company details
        $company->director = $request->director;
        $company->contact = $request->contact;
        $company->timezone = $request->timezone;
        $company->country = $request->bus_country;
        $company->state = $request->state;
        $company->address = $request->address;
        $company->zip = $request->zip;

        // Save the company record
        $company->save();
        $user->status = 1;
        if ($user->completed_step <= 3) {
            $user->completed_step = 4;
        }
        $user->save();
        // Redirect back with success message
        $notify[] = ['success', 'Buyer Business Contact updated successfully.'];
        return redirect()->back()->withNotify($notify)->with('activeStep', 4);
    }
    public function buyerToggleNotificationStatus(Request $request)
    {
        session()->flash('activeStep', 5);
        $validator = Validator::make($request->all(), [
            'template_ids' => 'required|array',
            'template_ids.*' => 'integer|exists:email_sms_templates,id',
            'notifications' => 'nullable|array',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('activeStep', 5);
        }
        try {
            DB::beginTransaction();
            $user = Auth::user();


            $process_controller = new ProcessController();
            $response = $process_controller->createStripeAccount();
            $account_id = $response['account_id'];
            $process_controller->accountSession();
            $process_controller->onBoarding($account_id);

            $userId = $user->id; // Get the authenticated user ID

            // Loop through all template IDs
            foreach ($request->template_ids as $templateId) {
                // Check if the notification is toggled on or off
                $status = isset($request->notifications[$templateId]) ? 1 : 0;

                // Check if an entry already exists for the user and the template
                $alert = DB::table('user_profile_alerts')
                    ->where('user_id', $userId)
                    ->where('email_sms_template_id', $templateId)
                    ->first();

                if ($alert) {
                    // Update existing entry
                    DB::table('user_profile_alerts')
                        ->where('user_id', $userId)
                        ->where('email_sms_template_id', $templateId)
                        ->update(['status' => $status]);
                } else {
                    // Insert new entry
                    DB::table('user_profile_alerts')->insert([
                        'user_id' => $userId,
                        'email_sms_template_id' => $templateId,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            if ($user->completed_step <= 4) {
                $user->completed_step = 5;
            }
            $user->status = 2;
            $user->save();
            sendAdminRealTimeNotification($user, ' profile completed by '. $user->username, route('admin.users.detail',['id'=>$user->id]));
            DB::commit();
            $notify[] = ['success', 'Buyer Profile Created Successfully.'];
            return redirect()->route('user.buyer.dashboard')->withNotify($notify);
        } catch (\Exception $e) {
            Log::info("Notification Error =>" . $e->getMessage());
            DB::rollback();
            $notify[] = ['error', $e->getMessage()];
            return redirect()->route('user.buyer.dashboard')->withNotify($notify);
        }
    }
    public function buying_profile_update(Request $request, $id)
    {
        $postData = $request->all();

        // echo"<pre>"; print_r($postData);exit;
        $user = Auth::user();
        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['about_me'] = $request->about_me;
        $in['email'] = $request->email;

        if ($request->hasFile('profile_picture')) {
            $wallpaper_image = $request->file('profile_picture');
            $fileName = time() . '-' . $wallpaper_image->getClientOriginalName();
            $wallpaper_image->move('assets/images/user/buyer/profile/', $fileName);
            $in['image'] = $fileName;
        }

        $e = $user->fill($in)->save();


        $lan = UserCompany::find($id);
        $lan->companyname = $request->companyname;
        $lan->companyweb = $request->companyweb;
        $lan->companyind = $request->companyind;
        $lan->director = $request->director;
        $lan->user_id = $user->id;
        $lan->contact = $request->contact;
        $lan->timezone = $request->timezone;
        $lan->country = $request->country;
        $lan->state = $request->state;
        $lan->zip = $request->zip;
        $lan->address = $request->address;
        $lan->companyname = $request->companyname;
        $lan->companyname = $request->companyname;
        $lan->companyname = $request->companyname;

        $lan->save();

        $alert = UserProfileAlerts::where('id', $request->notiid)->first();
        //        $alert->proposalrecieved = $request->proposalrecieved;
        // $alert->jobposted = $request->jobposted;
        // $alert->offermodified = $request->offermodified;
        // $alert->jobpostexpiresoon = $request->jobpostexpiresoon;
        // $alert->jobpostexpired = $request->jobpostexpired;
        // $alert->contractstart = $request->contractstart;
        // $alert->contractmodify = $request->contractmodify;
        // $alert->contractend = $request->contractend;
        // $alert->offerdecline = $request->offerdecline;
        // $alert->offeraccept = $request->offeraccept;
        $alert->Servicebookaccept_buyer = @$request->Servicebookaccept_buyer;
        $alert->Servicebookwork_buyer = @$request->Servicebookwork_buyer;
        $alert->Servicebookdisputeoffer_buyer = @$request->Servicebookdisputeoffer_buyer;
        $alert->Servicebookdisputeofferyes_buyer = @$request->Servicebookdisputeofferyes_buyer;
        $alert->Servicebookdisputeofferno_buyer = @$request->Servicebookdisputeofferno_buyer;
        $alert->Servicebookdisputeadmin_buyer = @$request->Servicebookdisputeadmin_buyer;
        $alert->Servicebookrr_buyer = @$request->Servicebookrr_buyer;
        $alert->jobreview_buyer = @$request->jobreview_buyer;
        $alert->jobdisputeadmin_buyer = @$request->jobdisputeadmin_buyer;
        $alert->jobdisputeofferno_buyer = @$request->jobdisputeofferno_buyer;
        $alert->jobdisputeofferyes_buyer = @$request->jobdisputeofferyes_buyer;
        $alert->jobdisputeoffer_buyer = @$request->jobdisputeoffer_buyer;
        $alert->jobdispute_buyer = @$request->jobdispute_buyer;
        $alert->jobworkno_buyer = @$request->jobworkno_buyer;
        $alert->jobworkyes_buyer = @$request->jobworkyes_buyer;
        $alert->jobcounterbid_buyer = @$request->jobcounterbid_buyer;
        $alert->joboffercounteroffer_buyer = @$request->joboffercounteroffer_buyer;
        $alert->joboffer_buyer = @$request->joboffer_buyer;
        $alert->save();

        $notify[] = ['success', 'Buyer Profile updated successfully.'];
        //echo 'yes';exit;
        return redirect()->route('user.buyer.dashboard')->withNotify($notify);
    }
    public function buyeruserProfile($username, $id)
    {
        $pageTitle = "User Profile";
        $userId = decrypt($id);

        // Fetch the buyer's profile details using the generalized method in User model
        $buyerData = User::find($userId)->getBuyerProfileData($userId);
        // Extract data for easy use in the view
        $user = $buyerData['user'];
        $jobs = $buyerData['jobs'];
        $isPersonaVerified = $user['persona'];

        //$comments = Comment::where('pro_id', $userId)->with('commentReply')->paginate(7);
        $reviews = ReviewRating::where('user_id', $userId)->with('service')->paginate(7);

        $data = [
            'servicesBought' => $buyerData['serviceBoughtCount'],
            'postedJobs' => $buyerData['totalJobsPosted'],
            'completedJobs' => $buyerData['completedProjects'],
            'pendingJobs' => $buyerData['pendingProjects'],
            'isSmsVerified' => $user->sv == 1,
            'isEmailVerified' => $user->ev == 1,
            'isPaymentVerified' => $user->is_payment_verified == 1,
            'jobs' => $jobs,
            'reviews' => $reviews,
            'isPersonaVerified' => $isPersonaVerified == 1 ? true : false
        ];
        // Pass data to the view
        return view($this->activeTemplate . 'buyerprofile', compact('pageTitle', 'user', 'data'));
    }
    public function jobcategoriesall($slug, $id)
    {
        //echo 'asd';exit;
        $cat = Category::where('id', decrypt($id))->first();
        $pageTitle = @$cat->name . " Sub Categories";
        $emptyMessage = "No Sub Categories found";
        return view($this->activeTemplate . 'job_subcategories', compact('pageTitle', 'cat', 'emptyMessage'));
    }
    public function jobsubcategoriesalljobs($slug, $id)
    {
        //echo 'asd';exit;
        $sub_cat = SubCategory::where('id', decrypt($id))->first();
        $pageTitle = @$sub_cat->name . " Services";
        $emptyMessage = "No Jobs found under " . @$sub_cat->name;
        $subcategoryes = $childcategories = $skills = [];
        $categoryes = DB::table('categories')->where('type', 'service')->get();
        // $childcategories=DB::table('skill_categories')->get();
        // $skills=DB::table('skills')->get();
        //  $subcategoryes=DB::table('sub_categories')->get();
        // $childcategories=DB::table('skill_categories')->get();
        // $skills=DB::table('skills')->get();


        $jobs = Job::where('status', 1)->where('sub_category_id', $sub_cat->id)->where('job_status', 0)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank', 'jobBiding')->orderBy('id', 'desc')->get();
        $jobs_count = Job::where('status', 1)->where('sub_category_id', $sub_cat->id)->where('job_status', 0)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with('user', 'user.rank', 'jobBiding')->count();
        $category = Category::where('id', $sub_cat->category_id)->first();
        return view($this->activeTemplate . 'subcatjobs', compact('pageTitle', 'jobs', 'emptyMessage', 'categoryes', 'childcategories', 'subcategoryes', 'sub_cat', 'category', 'jobs_count'));
    }
    public function OfferDetail(Request $request, $id)
    {
        $pageTitle = "Offer Details";
        $user = Auth::user();
        $buyerCounterOfferId = decrypt($id);  // Decrypt the bid ID
        $search = $request->input('search');

        // Fetch the offer details from buyer_counter_offers table
        $query = Buyercounteroffer::where('id', $buyerCounterOfferId)->where('reciever_id', $user->id)->with(['buyerCounterOfferData', 'job']);

        // Apply search filter if search is provided
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        // Fetch the offer record
        $data = $query->first();

        // If no record is found, handle it (optional)
        if (!$data) {
            return abort(404, 'Offer not found.');
        }

        // Fetch the corresponding user bidding entry to get seller_offer_responce
        // $userBidding = Userbiding::where('job_id', $data->job_id)->where('user_id', $user->id)->first();
        // $sellerOfferResponse = $userBidding->seller_offer_responce_text ?? 'Pending';
        // $sellerOfferResponseReason = $userBidding->seller_offer_responce_reason;
        // Add seller_offer_responce to $data object

        $data->job_type = $data->type;
        // Platform Fee percentage (can be dynamic, but using 10% as an example)
        $platformFeePercentage = $this->membershipService->getMembershipFee($user->id);
        $jobPosterName = $user->username;

        // Initialize expected total price
        $expected_total_price = 0;

        // Initialize milestones array
        $milestones = [];
        $offerData = Buyercounterofferdata::where('counter_offer_id', $data->id)->get();

        if($data->type == 'hourly') {
            $expected_total_price = $offerData->reduce(function ($carry, $item) use (&$milestones) {
                // Access amount, title, and due_date directly as individual values per milestone row
                $amount = $item->amount * $item->days;
                $title = $item->title;
                $due_date = $item->due_date; // Assuming 'days' stores the due date in each row

                // Accumulate the total amount
                $carry += $amount;

                // Populate the milestones array with each milestone's details
                $milestones[] = (object) [
                    'title' => $title,
                    'amount' => $amount,
                    'due_date' => $due_date ?? 'No Due Date'
                ];

                return $carry;
            }, 0);
        }else {
            // Calculate the expected total price from offer data
            $expected_total_price = $offerData->reduce(function ($carry, $item) use (&$milestones) {
                // Access amount, title, and due_date directly as individual values per milestone row
                $amount = $item->amount;
                $title = $item->title;
                $due_date = $item->due_date; // Assuming 'days' stores the due date in each row

                // Accumulate the total amount
                $carry += $amount;

                // Populate the milestones array with each milestone's details
                $milestones[] = (object) [
                    'title' => $title,
                    'amount' => $amount,
                    'due_date' => $due_date ?? 'No Due Date'
                ];

                return $carry;
            }, 0);
        }

        // Fetch offer details from the buyer_counter_offers_data table


        // Calculate platform fee
        $platform_fee = $expected_total_price * ($platformFeePercentage / 100);

        // Calculate total after platform fee deduction
        $total_after_fee = $expected_total_price - $platform_fee;

        // Encrypt job ID
        $encryptedBuyerCounterOfferId = Crypt::encrypt($data->id);
        $encryptedJobId = Crypt::encrypt($data->job_id);
        //$encrypted_bid_id = Crypt::encrypt($userBidding->id);
        $status = $data->status; // Assume status is stored in buyer_counter_offers table
        //$encryptedUserBidId = encrypt($userBidding->id);
        //$isCancel = $userBidding->is_cancel_bid;
        // Return the view with data and platform fee information
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Offers', 'url' => url('user/seller/job/list/ajax/offers')],
            ['title' => 'View Offer'],
        ];
        return view('templates.basic.offered_details', compact(
            'pageTitle',
            'data',
            'offerData',
            'milestones',  // Pass milestones to the view
            'platformFeePercentage',
            'status',
            'expected_total_price',
            'platform_fee',
            'total_after_fee',
            'encryptedBuyerCounterOfferId',
            'jobPosterName',
            'breadcrumbs',
            'encryptedJobId'
        ));
    }
    public function checkAppInstalled(Request $request)
    {
        // Here you can add custom logic to determine if the app is installed
        // Example: Checking platform, session info, or browser metadata
        $isAppInstalled = false; // Set true if installed (example placeholder logic)

        if ($isAppInstalled) {
            return response()->json([
                'installed' => true,
            ]);
        } else {
            return response()->json([
                'installed' => false,
                'downloadUrl' => url('/download-auto-tracker'), // Provide download link
            ]);
        }
    }
    public function markNotificationAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');

        // Mark the notification as read
        $notification = DB::table('notifications')->where('id', $notificationId)->first();

        if ($notification) {
            DB::table('notifications')->where('id', $notificationId)->update(['read_at' => now()]);
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.']);
    }
    public function toggleNotificationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_ids' => 'required|array',
            'template_ids.*' => 'integer|exists:email_sms_templates,id',
            'notifications' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('activeStep', 9);
        }
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $process_controller = new ProcessController();
            $response = $process_controller->createStripeAccount();
            $account_id = $response['account_id'];
            $process_controller->accountSession();
            $process_controller->onBoarding($account_id);

            $userId = $user->id; // Get the authenticated user ID

            // Loop through all template IDs
            foreach ($request->template_ids as $templateId) {
                // Check if the notification is toggled on or off
                $status = isset($request->notifications[$templateId]) ? 1 : 0;

                // Check if an entry already exists for the user and the template
                $alert = DB::table('user_profile_alerts')
                    ->where('user_id', $userId)
                    ->where('email_sms_template_id', $templateId)
                    ->first();

                if ($alert) {
                    // Update existing entry
                    DB::table('user_profile_alerts')
                        ->where('user_id', $userId)
                        ->where('email_sms_template_id', $templateId)
                        ->update(['status' => $status]);
                } else {
                    // Insert new entry
                    DB::table('user_profile_alerts')->insert([
                        'user_id' => $userId,
                        'email_sms_template_id' => $templateId,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            if ($user->completed_step < 9) {
                $user->completed_step = 9;
            }
            $user->status = 2;
            $user->save();
            sendJobAdminRealTimeNotification($user, 'SELLER_PROFILE_COMPLETION', '#');
            sendAdminRealTimeNotification($user, ' profile completed by '. $user->username, route('admin.users.detail',['id'=>$user->id]));
            DB::commit();
            $notify[] = ['success', 'Professional profile updated successfully.'];
            return redirect()->route('user.home')->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollback();
            $notify[] = ['error', $e->getMessage()];
            return redirect()->route('user.home')->withNotify($notify);
        }
    }
    public function deleteAllNotifications()
    {
        try {
            $userId = auth()->id();

            // Delete all notifications for the current user
            DB::table('user_profile_alerts')
                ->where('user_id', $userId)
                ->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function inviteFreelancers($job_id)
    {
        $pageTitle = "Invite Professionals";
        $user = Auth::user();
        if ($user == null) {
            return redirect(url('/login'));
        }
        $userId = $user->id;
        $usermembership1 = null;
        if (!empty($user)) {
            if ($user->is_employer == 1) {
                $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
            }
        }
        $getinvites = Employerservicescount::where('user_id', $user->id)->first();
        if (@$getinvites->invites <= 0) {
            $notify[] = ['error', 'You have no invites left to hire professional'];
            return redirect()->back()->withNotify($notify);
        }

        $checkuserisbuyer = User::where('status', 1)->where('id', $user->id)->firstOrFail();
        if ($checkuserisbuyer->is_employer != 1) {
            $notify[] = ['error', 'You cannot hire professional'];
            return redirect()->back()->withNotify($notify);
        }
        // $checkofferexist = Useroffer::where('job_id', decrypt($id))->where('user_id', $user->id)->get();
        // if (count($checkbidexist) > 0) {
        //     $notify[] = ['error', 'You already submitted proposal to this job'];
        //     return redirect(url('/job'))->withNotify($notify);
        // }

        $sellerIds = Jobworkdeliverybid::with([
            'buyer' => function ($query) {
                $query->where('status', 1)
                    ->where('is_freelancer', 1)
                    ->where('per_hour_rate', '>',0);
            }
        ])
        ->where('buyer_id', $user?->id)
        ->distinct('seller_id')
        ->pluck('seller_id')
        ->toArray();

        $professionals = User::whereIn('id', $sellerIds)
            ->where('is_freelancer', 1)
            ->get(['id', 'firstname', 'lastname', 'designation', 'username']);

        $usermembership = null;
        if (!empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $professional->id)->get();
            }
        }
        $membershipFee = $this->membershipService->getMembershipFee($user->id);
        $buyersPostedJobs = Job::where('user_id', $userId)->orderBy('created_at', 'desc')->get();


        return view($this->activeTemplate . 'invite_freelancers', compact('pageTitle', 'job_id', 'professionals', 'usermembership', 'buyersPostedJobs', 'userId','membershipFee'));
    }

    public function getFreelancerSkills($id)
    {
        $freelancer = User::where('id', $id)
            ->where('is_freelancer', 1)
            ->first();

        if (!$freelancer) {
            return response()->json(['success' => false, 'message' => 'Freelancer not found']);
        }

        $skills = [];

        // skills from skillsprofilenew
        if ($freelancer->skillsprofilenew) {
            foreach ($freelancer->skillsprofilenew as $skill) {
                $skills[] = $skill->name;
            }
        }

        // skills from profileSkills
        if ($freelancer->profileSkills) {
            foreach ($freelancer->profileSkills as $skill) {
                $name = DB::table('skills')->where('id', $skill->skill_id)->value('name');
                if ($name) {
                    $skills[] = $name;
                }
            }
        }

        return response()->json([
            'success' => true,
            'skills' => $skills,
        ]);
    }

    public function updatePersona(Request $request)
    {
        $user = Auth::user();
        $user->persona = $request->status == 'completed' ? "1" : "0";
        $user->inquiry_id = $request->inquiry_id ?? null;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function getFreshOnboardingLink()
    {
        try {
            $user = Auth::user();

            // Check if user already has an account_id
            $existingAccount = StripeResponse::where([
                ['user_id', $user->id],
                ['type', 'create_account']
            ])->first();

            $account_id = null;

            if ($existingAccount && isset($existingAccount->response['id'])) {
                // Use existing account
                $account_id = $existingAccount->response['id'];
            } else {
                // Create new account
                $process_controller = new ProcessController();
                $response = $process_controller->createStripeAccount();
                $account_id = $response['account_id'];
            }

            sendJobAdminRealTimeNotification($user, 'SELLER_ONBOARDING', '#');

            // Generate fresh onboarding link
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

            $accountLink = $stripe->accountLinks->create([
                'account' => $account_id,
                'refresh_url' => route('user.withdraw'), // Current page
                'return_url' => route('user.withdraw') . '?onboarding=complete', // Success redirect
                'type' => 'account_onboarding',
                'collection_options' => ['fields' => 'eventually_due'],
            ]);

            // Delete old onboarding links
            StripeResponse::where([
                ['user_id', $user->id],
                ['type', 'onBoarding']
            ])->delete();

            // Save new onboarding link
            $stripe_response = new StripeResponse();
            $stripe_response->user_id = $user->id;
            $stripe_response->response = $accountLink;
            $stripe_response->type = 'onBoarding';
            $stripe_response->save();

            return response()->json([
                'success' => true,
                'url' => $accountLink->url,
                'message' => 'Fresh onboarding link generated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBuyerFreshOnboardingLink()
    {
        try {
            $user = Auth::user();

            // Check if user already has an account_id
            $existingAccount = StripeResponse::where([
                ['user_id', $user->id],
                ['type', 'create_account']
            ])->first();
            sendJobAdminRealTimeNotification($user, 'BUYER_ONBOARDING', '#');
            $account_id = null;

            if ($existingAccount && isset($existingAccount->response['id'])) {
                // Use existing account
                $account_id = $existingAccount->response['id'];
            } else {
                // Create new account
                $process_controller = new ProcessController();
                $response = $process_controller->createStripeAccount();
                $account_id = $response['account_id'];
            }

            // Generate fresh onboarding link
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

            $accountLink = $stripe->accountLinks->create([
                'account' => $account_id,
                'refresh_url' => route('user.buyer.withdraw'), // Current page
                'return_url' => route('user.buyer.withdraw') . '?onboarding=complete', // Success redirect
                'type' => 'account_onboarding',
                'collection_options' => ['fields' => 'eventually_due'],
            ]);

            // Delete old onboarding links
            StripeResponse::where([
                ['user_id', $user->id],
                ['type', 'onBoarding']
            ])->delete();

            // Save new onboarding link
            $stripe_response = new StripeResponse();
            $stripe_response->user_id = $user->id;
            $stripe_response->response = $accountLink;
            $stripe_response->type = 'onBoarding';
            $stripe_response->save();

            return response()->json([
                'success' => true,
                'url' => $accountLink->url,
                'message' => 'Fresh onboarding link generated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserSkills($user_id)
    {
        return UserProfileSkill::with([
            'skill' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->where('user_id', $user_id)
            ->get()
            ->pluck('skill.name');
    }

    private function searchJob(Request $request)
    {
        $job_title = $request->input('search', null);
        $location = $request->input('location', null);
        $sort = $request->input('sort', 'Date'); // JobTitle,JobPosted
        $pageTitle = "All Jobs";
        // Fetch categories related to jobs using the Category model
        $categories = Category::where('type', 'job')->get();

        // Fetch job data with the related user using Eloquent
        // Assuming each job has a 'user' relationship for the job poster
        $jobs = Job::where('status', 1)
            ->when(!is_null($job_title) && !empty($job_title), function ($query) use ($job_title) {
                $query->where('title', 'LIKE', "%{$job_title}%");
            })
            ->when(!is_null($location) && !empty($location), function ($query) use ($location) {
                $query->where('country', 'LIKE', "%{$location}%");
            })
            ->when(!is_null($sort) && !empty($sort), function ($query) use ($sort) {
                if ($sort == 'JobTitle') {
                    $query->orderBy('title', 'asc');
                } else if ($sort == 'Date') {
                    $query->orderBy('created_at', 'desc');
                } else if ($sort == 'JobPosted') {
                    $query->orderBy('created_at', 'desc');
                }
            })
            ->with('user')
            ->withCount('userBiding')
            ->orderBy('id', 'desc') // Eager load the user related to the job
            ->paginate(10)
            ->withQueryString(); // Adjust pagination as needed
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
        return view('templates.basic.job', compact('pageTitle', 'categories', 'jobs', 'countries'));
    }

    public function searchProfessionals(Request $request)
    {
        $search = $request->search ?? $request->name;

        $pageTitle = "All professionals";
        $emptyMessage = "No data found";
        // $frelancer_memberships = Freelancermembership::orderBy('price','desc')->distinct()->pluck('title')->toArray();
        $freelancer_memberships = membershipsArray();
        $user = Auth::user();

        $professionals = User::with('freelancer_membership_transaction.membership', 'languages')
            ->where('users.status', 1)
            ->where('is_freelancer', 1)
            ->where(function ($query) {
                $query->where('per_hour_rate', '!=', 0)
                    ->orWhere('per_hour_rate', '!=', '');
            });

        if ($request->has('skill') && $request->skill != '') {
            $skill = Skill::where('name', $request->skill)->pluck('id')->toArray();
            if (count($skill) > 0) {
                $professionals->whereHas('profileSkills', function ($query) use ($skill) {
                    $query->whereIn('skill_id', $skill);
                });
            } else {
                $professionals = $professionals->whereRaw('0 = 1');
            }
        }
        if ($search && $search != '') {
            $professionals->where('username', 'like', '%' . $search . '%');
        }

        $professionals = $professionals->leftJoin('review_ratings', 'users.id', '=', 'review_ratings.pro_id')
            ->leftJoin('freelancer_membership_transactions', 'users.id', '=', 'freelancer_membership_transactions.user_id')
            ->leftJoin('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')
            ->select('users.*', DB::raw('AVG(review_ratings.likely_rating) as avg_rating'))
            ->groupBy('users.id')
            ->orderByRaw("
                CASE
                    WHEN freelancer_membership.title = 'Premium' THEN 1
                    WHEN freelancer_membership.title = 'Standard' THEN 2
                    WHEN freelancer_membership.title = 'Essentional' THEN 3
                    WHEN freelancer_membership.title = 'Basic' THEN 4
                    WHEN freelancer_membership.title = 'Try it free' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('avg_rating', 'DESC');
        $professionals = $professionals->inRandomOrder()->paginate(6)->withQueryString();
        foreach ($professionals as $professional) {
            $sellerData = $professional->getSellerProfileData($professional->id);
            $professional->completedJobs = $sellerData['completedJobs']->count();
        }

        // Fetch job categories efficiently
        $categories = Category::where('type', 'job')->cursor();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);

        return view($this->activeTemplate . 'professionals', compact('pageTitle', 'professionals', 'emptyMessage', 'user', 'categories', 'countries'));
    }

    public function searchServices(Request $request)
    {
        $search = $request->search ?? $request->name;

        $pageTitle = "All Services";
        $emptyMessage = "No services found";

        $user = Auth::user();

        $services = Service::with(['user.freelancer_membership_transaction.membership', 'category'])
            ->where('services.status', 1)
            ->whereHas('user', function ($q) {
                $q->where('status', 1)
                ->where('is_freelancer', 1);
            });

        // Skill filter
        if ($request->has('skill') && $request->skill != '') {
            $skill = Skill::where('name', $request->skill)->pluck('id')->toArray();

            if (count($skill) > 0) {
                $services->whereHas('skills', function ($query) use ($skill) {
                    $query->whereIn('skill_id', $skill);
                });
            } else {
                $services = $services->whereRaw('0 = 1');
            }
        }

        // Search by title
        if ($search && $search != '') {
            $services->where('services.title', 'like', '%' . $search . '%');
        }

        $services = $services
            ->leftJoin('review_ratings', 'services.id', '=', 'review_ratings.service_id')
            ->leftJoin('users', 'services.user_id', '=', 'users.id')
            ->leftJoin('freelancer_membership_transactions', 'users.id', '=', 'freelancer_membership_transactions.user_id')
            ->leftJoin('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')

            ->select('services.*', DB::raw('AVG(review_ratings.likely_rating) as avg_rating'))

            ->groupBy('services.id')

            ->orderByRaw("
                CASE
                    WHEN freelancer_membership.title = 'Premium' THEN 1
                    WHEN freelancer_membership.title = 'Standard' THEN 2
                    WHEN freelancer_membership.title = 'Essentional' THEN 3
                    WHEN freelancer_membership.title = 'Basic' THEN 4
                    WHEN freelancer_membership.title = 'Try it free' THEN 5
                    ELSE 6
                END
            ")

            ->orderBy('avg_rating', 'DESC');

        $services = $services->inRandomOrder()->paginate(6)->withQueryString();

        $categories = Category::where('type', 'job')->cursor();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);

        return view($this->activeTemplate . 'service', compact(
            'pageTitle',
            'services',
            'emptyMessage',
            'user',
            'categories',
            'countries'
        ));
    }
}
