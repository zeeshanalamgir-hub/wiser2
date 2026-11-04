<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Acceptedbid;
use App\Models\BidsLog;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Employerservicescount;
use App\Models\GeneralSetting;
use App\Models\Job;
use App\Models\JobAttachements;
use App\Models\Jobreviews;
use App\Models\Jobskill;
use App\Models\Newskillbyuser;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Userbiding;
use App\Services\BuyerJobService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception as MailerException;
use PHPMailer\PHPMailer\PHPMailer;

class JobController extends Controller
{
    public function __construct(private BuyerJobService $buyerJobService)
    {
        $this->activeTemplate = activeTemplate();
    }

    public function create( Request $request )
    {
        $user = Auth::user();
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $servicescount = $this->buyerJobService->remainingBuyerCreateJobCount($user->id);
        if ($servicescount == 0) {
            $notify[] = ['error', 'Your Job posting limit is full.'];

            return redirect(url('/user/buyer/dashboard'))->withNotify($notify);
        }

        $oldCategory = old('category');  // Old category value from the previous input
        $oldSubcategory = old('subcategory'); // Old subcategory value from the previous input

        $categories = Category::where('type', 'job')->get(); // Fetch all categories for the dropdown
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Create Job'],
        ];

        $pageTitle = 'Job Create';

        return view('templates.basic.user.buyer.job.create', compact('categories', 'pageTitle', 'oldCategory', 'oldSubcategory', 'breadcrumbs'));
    }

    public function editJob(Request $request, $encryptedJobId)
    {
        $user = Auth::user();

        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        try {
            $jobId = Crypt::decrypt($encryptedJobId);
        } catch (DecryptException $e) {
            $notify[] = ['error', 'Invalid job ID.'];

            return redirect()->back()->withNotify($notify);
        }

        $job = Job::with(['attachments', 'skills', 'category', 'subcategory', 'extraSkills'])->find($jobId);
        if (! $job) {
            $notify[] = ['error', 'Job not found.'];

            return redirect()->back()->withNotify($notify);
        }

        // Fetch categories and subcategories
        $categories = Category::all();
        $subcategories = SubCategory::where('category_id', $job->category_id)->get();

        // Fetch existing skills for display
        $existingSkills = $job->skills;
        // Fetch extra skills
        $extraSkills = $job->extraSkills;
        // Fetch skills for the job's subcategory
        $skillsBySubcategory = collect(); // Initialize as a Laravel collection
        if ($job->sub_category_id) {
            $skillsBySubcategory = SkillCategory::where('sub_category_id', $job->sub_category_id)
                ->with('skills') // Fetch related skills
                ->get()
                ->map(function ($skillCategory) {
                    return [
                        'id' => $skillCategory->id,
                        'name' => $skillCategory->name,
                        'skills' => $skillCategory->skills, // Use the relationship
                    ];
                });
        }
        $pageTitle = 'Edit Job';
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Edit Job'],
        ];

        return view('templates.basic.user.buyer.job.edit-job', compact('pageTitle', 'job', 'categories', 'subcategories', 'existingSkills', 'extraSkills', 'skillsBySubcategory', 'breadcrumbs'));
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
        $search = $request->input('search', '');
        $page = $request->input('page', 1);

        // Define the page title and empty message
        $pageTitle = 'Manage Jobs';
        $emptyMessage = 'No data found';

        // Fetch only necessary columns and apply search filter if needed
        $query = Job::select('id', 'title', 'budgettype', 'status', 'created_at')
            ->where('user_id', $user->id);

        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        // Paginate the results
        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        // Transform data to include additional computed fields
        $jobs->getCollection()->transform(function ($item) {
            $item->encrypted_job_id = Crypt::encrypt($item->id);
            $item->created_date = Carbon::parse($item->created_at)->format('Y-m-d');
            $item->bid_count = Userbiding::where('job_id', $item->id)->count();
            $item->status_text = $item->status_text; // Use accessor for status text

            return $item;
        });

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Manage Jobs'],
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('templates.basic.user.buyer.job.ajax_index', compact('jobs', 'emptyMessage'))->render(),
                'breadcrumbs' => view('components.breadcrumbs', compact('breadcrumbs'))->render(),
            ]);
        }

        return view('templates.basic.user.buyer.job.index', compact('pageTitle', 'emptyMessage', 'jobs', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction(); 
        try {
            $validatedData = $this->validateJobData($request);
            if ($validatedData instanceof \Illuminate\Http\RedirectResponse) {
                return $validatedData; // Return the redirect response with validation errors
            }

            $user = Auth::user();
            //new code 17th april 26

            if($user->is_payment_verified == 0) {
                return redirect()->back()->withNotify([
                    'error' => 'Your payment is not verified. Please deposit the required amount to proceed with the job.'
                ])->withInput();
            }

            if($request->job_type == 'fixed') {
                if($user->balance < $request->amount) {
                    return redirect()->back()->withNotify(['error' => 'You do not have enough balance to create this job.'])->withInput();
                }
            }else if($request->job_type == 'hourly') {
                if($user->balance < ($request->weekly_hours * $request->amount)) {
                    return redirect()->back()->withNotify(['error' => 'You do not have enough balance to create this job.'])->withInput();
                }
            }

            
            $general = GeneralSetting::first();

            // Validate job credits for priority jobs
            if (! $this->checkJobCredits($request, $user)) {
                return redirect()->back()->withNotify(['error' => 'You do not have enough job credits to create this job.'])->withInput();
            }
            // Prepare job data
            $job = $this->createJob($request, $user, $general);

            // Handle file attachments
            $this->handleFileAttachments($request, $job, $user);

            // Handle job skills
            $this->saveJobSkills($request, $job);

            // Notify users about the job
            $this->notifyUsersAboutJob($job, $user);

            // Send admin notification
            // sendJobAdminRealTimeNotification($user,'BUYER_CREATE_NEW_JOB',urlPath('admin.job.details', $job->id));

            sendAdminRealTimeNotification(Auth::user(), 'New Job Created By '.Auth::user()->username, route('admin.job.pending'));

            // sendJobAdminNotification($user, $job, 'BUYER_CREATE_NEW_JOB');

            // If review flag is present
            if ($request->has('review') && $request->review !== '') {
                DB::commit(); // Commit the transaction

                return redirect('/user/buyer/job/edit/'.$job->title.'/'.$job->id.'?review=true');
            }

            DB::commit(); // Commit the transaction

            return redirect('/user/buyer/job/index')->withNotify(['success' => 'Job has been created successfully!']);
        } catch (MailerException $e) {
            DB::rollBack(); // Rollback all changes
            Log::error('PHPMailer error during job creation: '.$e->getMessage());

            // Return to the previous page with a PHPMailer-specific error message
            return redirect()->back()->withNotify(['error' => 'Job created but email notification failed.'])->withInput();
        } catch (Exception $e) {
            DB::rollBack(); // Rollback all changes
            Log::error('Job creation error: '.$e->getMessage());

            // Return to the previous page with a general error message
            return redirect()->back()->withNotify(['error' => 'Job creation failed. Please try again later.'])->withInput();
        }
    }

    /**
     * Validate job data
     */
    protected function validateJobData($request)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'subcategory' => 'required|exists:sub_categories,id',
            'due_date' => [
                'nullable', // Make it nullable
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('job_type') == 'fixed' && is_null($value)) {
                        $fail('The due date is required for fixed type jobs.');
                    }
                },
            ],
            'job_description' => 'required|string|max:5000',
            'requirements' => 'required|string|max:5000',
            'experience_level' => 'required|integer|in:1,2,3',
            'job_type' => 'required|in:fixed,hourly',
            'amount' => [
                'required',
                'numeric',
                'min:0',
               ],
            'skills' => 'required|array',
            'skills.*.id' => 'required|integer|exists:skills,id',
            'skills.*.name' => 'required|string|max:255',
            'extra_skills' => 'required|array|max:5',
            'extra_skills.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    $decodedSkill = json_decode($value, true);
                    if (! $decodedSkill || ! isset($decodedSkill['id'], $decodedSkill['name'])) {
                        $fail("The $attribute must be a valid skill with an ID and name.");
                    }
                },
               ],
            'attachment.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'priority' => 'required|in:1,2',
        ], [
               // Custom Error Messages
               'job_title.required' => 'Please provide a job title.',
               'category.required' => 'Please select a category.',
               'category.exists' => 'The selected category is invalid.',
               'subcategory.required' => 'Please select a subcategory.',
               'subcategory.exists' => 'The selected subcategory is invalid.',
               'due_date.date' => 'The delivery date must be a valid date.',
               'due_date.after_or_equal' => 'The delivery date cannot be in the past.',
               'job_description.required' => 'Please provide a job description.',
               'requirements.required' => 'Please specify the job requirements.',
               'experience_level.required' => 'Please choose an experience level.',
               'experience_level.in' => 'The selected experience level is invalid.',
               'job_type.required' => 'Please select a job type (fixed or hourly).',
               'amount.required' => 'Please provide an amount.',
               'amount.numeric' => 'The amount must be a valid number.',
               'amount.min' => 'The amount cannot be negative.',
               'skills.required' => 'Please select at least one skill.',
               'skills.*.id.exists' => 'One of the selected skills is invalid.',
               'extra_skills.*.exists' => 'One of the extra skills does not exist in the database.',
               'attachment.*.mimes' => 'Attachments must be a file of type: pdf, jpg, jpeg, png.',
               'attachment.*.max' => 'Each attachment may not be greater than 1MB.',
               'priority.required' => 'Please specify the job priority.',
               'priority.in' => 'The selected priority is invalid.',
           ]);

        if ($request->input('job_type') === 'hourly') {
            // Only apply these rules if the job type is 'hourly'
            $validator->sometimes('weekly_hours', 'required|integer|min:0', function ($input) {
                return ! is_null($input->weekly_hours);
            }, [
                'weekly_hours.required' => 'Please provide weekly hours for hourly jobs.',
                'weekly_hours.integer' => 'Weekly hours must be a valid number.',
                'weekly_hours.min' => 'Weekly hours cannot be negative.',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return $validator->validated();
    }

    protected function updateValidateJobData($request)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'subcategory' => 'required|exists:sub_categories,id',
            'due_date' => [
                'nullable', // Make it nullable
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('job_type') == 'fixed' && is_null($value)) {
                        $fail('The due date is required for fixed type jobs.');
                    }
                },
            ],
            'job_description' => 'required|string',
            'requirements' => 'required|string',
            'experience_level' => 'required|integer|in:1,2,3',
            'job_type' => 'required|in:fixed,hourly',
            'amount' => [
                'required',
                'numeric',
                'min:0',
               ],
            'skills' => 'nullable|array',
            'skills.*.id' => 'nullable|integer|exists:skills,id',
            'skills.*.name' => 'nullable|string|max:255',
            'extra_skills' => 'nullable|array|max:5',
            'extra_skills.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $decodedSkill = json_decode($value, true);
                    if (! $decodedSkill || ! isset($decodedSkill['id'], $decodedSkill['name'])) {
                        $fail("The $attribute must be a valid skill with an ID and name.");
                    }
                },
               ],
            'attachment.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'priority' => 'required|in:1,2',
        ], [
               // Custom Error Messages
               'job_title.required' => 'Please provide a job title.',
               'category.required' => 'Please select a category.',
               'category.exists' => 'The selected category is invalid.',
               'subcategory.required' => 'Please select a subcategory.',
               'subcategory.exists' => 'The selected subcategory is invalid.',
               'due_date.date' => 'The delivery date must be a valid date.',
               'due_date.after_or_equal' => 'The delivery date cannot be in the past.',
               'job_description.required' => 'Please provide a job description.',
               'requirements.required' => 'Please specify the job requirements.',
               'experience_level.required' => 'Please choose an experience level.',
               'experience_level.in' => 'The selected experience level is invalid.',
               'job_type.required' => 'Please select a job type (fixed or hourly).',
               'amount.required' => 'Please provide an amount.',
               'amount.numeric' => 'The amount must be a valid number.',
               'amount.min' => 'The amount cannot be negative.',
               'skills.required' => 'Please select at least one skill.',
               'skills.*.id.exists' => 'One of the selected skills is invalid.',
               'extra_skills.*.exists' => 'One of the extra skills does not exist in the database.',
               'attachment.*.mimes' => 'Attachments must be a file of type: pdf, jpg, jpeg, png.',
               'attachment.*.max' => 'Each attachment may not be greater than 1MB.',
               'priority.required' => 'Please specify the job priority.',
               'priority.in' => 'The selected priority is invalid.',
           ]);

        if ($request->input('job_type') === 'hourly') {
            // Only apply these rules if the job type is 'hourly'
            $validator->sometimes('weekly_hours', 'required|integer|min:0', function ($input) {
                return ! is_null($input->weekly_hours);
            }, [
                'weekly_hours.required' => 'Please provide weekly hours for hourly jobs.',
                'weekly_hours.integer' => 'Weekly hours must be a valid number.',
                'weekly_hours.min' => 'Weekly hours cannot be negative.',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return $validator->validated();
    }

    /**
     * Check if user has enough job credits
     */
    protected function checkJobCredits($request, $user)
    {
        $coloum_name = $request->priority == 1 ? 'normal_jobs' : 'featured_jobs';
        $userServiceCount = Employerservicescount::where('user_id', $user->id)->first();

        if ($userServiceCount->$coloum_name <= 0) {
            return false;
        }

        Employerservicescount::where('user_id', $user->id)->decrement($coloum_name, 1);
        BidsLog::create([
            'user_id' => $user->id,
            'bids' => $userServiceCount->$coloum_name,
            'tr_op' => '-',
            'post_balance' => $userServiceCount->normal_jobs + $userServiceCount->priority_jobs + $userServiceCount->featured_jobs,
            'details' => 'Job used on '.$request->title,
        ]);

        return true;
    }

    /**
     * Check if budget is valid based on user's wallet
     */
    protected function checkBudget($request, $user)
    {
        $buyerWalletAmount = $user->balance;

        if ($request->budgettype == 'fixed' && $request->amount > $buyerWalletAmount) {
            return false;
        }

        if ($request->budgettype == 'per_hour' && $request->maxBudget > $buyerWalletAmount) {
            return false;
        }

        return true;
    }

    /**
     * Create job
     */
    protected function createJob($request, $user, $general)
    {
        $priority = $request->has('priority') ? $request->input('priority') : 1;
        $job = new Job;
        $dueDate = $request->due_date;
        if ($dueDate) {
            $dueDate = Carbon::parse($dueDate)->setTime(23, 59, 59)->toDateTimeString();
        }
        $job->fill([
            'title' => $request->job_title,
            'description' => ($request->job_description),
            'requirements' => ($request->requirements),
            'user_id' => $user->id,
            'category_id' => $request->category,
            'sub_category_id' => $request->subcategory,
            'amount' => $request->amount ?? 0,
            'delivery_date' => $dueDate, // Use the preprocessed due date
            'priority' => $priority,
            'exp_type' => $request->experience_level,
            'budgettype' => $request->job_type,
            'country' => $user?->address?->country,
            'no_budget' => $request->has('no_budget') ? 1 : 0,
            'weekly_hours' => $request->weekly_hours,
        ]);

        if ($general->approval_post == 1) {
            $job->status = 1;
        }
        if ($request->has('skills') && ! empty($request->input('skills'))) {
            $skills = collect($request->input('skills'))->map(function ($skill) {
                return is_array($skill) ? $skill['name'] : json_decode($skill, true)['name'];
            })->unique()->toArray();
            $job->skill = $skills;
        } else {
            $job->skill = null;
        }
        $job->save();

        return $job;

    }

    protected function mapExperienceLevel($experienceLevel)
    {
        $map = [
            'beginner' => 1,
            'intermediate' => 2,
            'expert' => 3,
        ];

        return $map[$experienceLevel] ?? null; // Return null or a default value if not found
    }

    /**
     * Handle file attachments
     */
    protected function handleFileAttachments(Request $request, Job $job, $user)
    {
        try {
            // Handle new file attachments
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $attachment) {
                    $attachmentFileName = uniqid().'.'.$attachment->getClientOriginalName();
                    $path = $attachment->storeAs('job_attachments', $attachmentFileName, 'public');

                    // Save the attachment record in the database
                    JobAttachements::create([
                        'user_id' => $user->id,
                        'job_id' => $job->id,
                        'image_url' => $path,
                    ]);
                }
            }

            // Handle attachments to be removed
            if ($request->has('remove_attachments')) {
                $attachmentsToRemove = json_decode($request->input('remove_attachments'), true);
                foreach ($attachmentsToRemove as $attachmentId) {
                    $attachment = JobAttachements::find($attachmentId);
                    if ($attachment) {
                        // Delete the file from storage
                        \Storage::disk('public')->delete($attachment->image_url);

                        // Delete the database record
                        $attachment->delete();
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception('Error handling attachments: '.$e->getMessage());
        }
    }

    /**
     * Save job skills
     */
    protected function saveJobSkills(Request $request, Job $job)
    {
        try {
            // First, delete existing skills associated with the job
            JobSkill::where('job_id', $job->id)->delete();

            // Insert new existing skills
            if ($request->has('skills')) {
                $skills = collect($request->input('skills'))->map(function ($skill) use ($job) {
                    return [
                        'skill_id' => $skill['id'],
                        'job_id' => $job->id,
                    ];
                });
                JobSkill::insert($skills->toArray());
            }

            // Insert new skills (IDs only)
            if ($request->has('extra_skills')) {
                // NewSkillByUser::where('term_id', $job->id)->delete();
                $newSkills = collect($request->extra_skills)->map(function ($skillJson) use ($job) {
                    $skill = json_decode($skillJson, true); // Decode the JSON string

                    return [
                        'term_id' => $job->id, // Associate with the job ID
                        'type' => 0, // Set the type if applicable
                        'name' => $skill['name'], // Use the name from the skill
                    ];
                });

                // Insert the prepared skills into the database
                NewSkillByUser::insert($newSkills->toArray());
            }
        } catch (Exception $e) {
            throw new Exception('Error saving job skills: '.$e->getMessage());
        }
    }

    /**
     * Notify users about the job
     */
    protected function notifyUsersAboutJob($job, $user)
    {
        $freelancers = User::where('is_freelancer', 1)->get();

        foreach ($freelancers as $freelancer) {
            $percentage = getUserSkillsVsJobSkillsPercentage($freelancer, $job);
            Log::error('Percentage match : '.$percentage);
            if ($percentage > 60) {
                sendJobEmailWithNotification($job->user_id, $freelancer->id, $job->id, 'JOB_POSTING_ALERTS');
            }
        }
    }

    public function edit(Request $req, $slug, $id)
    {
        if (isset($req->review)) {
            $review = 'true';
            $pageTitle = 'Review Job';
        } else {
            $review = 'false';
            $pageTitle = 'Update Job';
        }
        $user = Auth::user();
        // $pageTitle = "Job Update";
        $job = Job::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $userservices = Employerservicescount::where('user_id', $user->id)->get();
        $categoryss = DB::table('categories')->where('type', 'job')->get();

        return view($this->activeTemplate.'user.buyer.job.edit', compact('pageTitle', 'job', 'userservices', 'categoryss', 'review'));
    }

    public function delete(Request $req, $id)
    {
        $user = Auth::user();
        $job = Job::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        if ($job) {
            $job->delete();

            $notify[] = ['success', 'Job deleted successfully.'];

            return back()->withNotify($notify);
        } else {
            $notify[] = ['success', 'Job not Found.'];

            return back()->withNotify($notify);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->updateValidateJobData($request, $id);
        if ($validatedData instanceof \Illuminate\Http\RedirectResponse) {
            return $validatedData; // Return the redirect response with validation errors
        }

        $user = Auth::user();
        $job = Job::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $general = GeneralSetting::first();
        $dueDate = $request->due_date;
        if ($dueDate) {
            $dueDate = Carbon::parse($dueDate)->setTime(23, 59, 59)->toDateTimeString();
        }
        // Prepare job data
        $job->fill([
            'title' => $request->job_title,
            'user_id' => $user->id,
            'category_id' => $request->category,

            'sub_category_id' => $request->subcategory ?? null,
            'amount' => $request->amount ?? 0,
            'delivery_time' => $request->delivery,
            'budgettype' => $request->job_type,
            'delivery_date' => $dueDate, // Use the preprocessed due date
            'description' => ($request->job_description),
            'requirements' => ($request->requirements),
            'priority' => $request->priority,
            'no_budget' => $request->has('no_budget') ? 1 : 0,
            'status' => $general->approval_post == 1 ? 1 : 0,
            'created_at' => $general->approval_post == 1 ? null : Carbon::now(),
            'updated_at' => Carbon::now(),
            'weekly_hours' => $request->weekly_hours,
            'exp_type' => $request->experience_level,
        ]);

        // if ($request->has('skills') && !empty($request->input('skills'))) {
        //     $skills = collect($request->input('skills'))->map(function ($skill) {
        //         return is_array($skill) ? $skill['name'] : json_decode($skill, true)['name'];
        //     })->unique()->toArray();
        //     $job->skill = $skills;
        // } else {
        //     $job->skill = null;
        // }
        $job->save();
        // Handle image upload
        $this->handleFileAttachments($request, $job, $user);

        // Update job skills
        $this->saveJobSkills($request, $job);

        $notify[] = ['success', 'Job has been updated.'];

        return redirect('/user/buyer/job/index')->withNotify($notify);
    }

    private function fileValidate($file)
    {
        $allowedExts = ['jpeg', 'jpg', 'png'];
        $ext = strtolower($file->getClientOriginalExtension());
        if (! in_array($ext, $allowedExts)) {
            $notify = 'Only jpeg, jpg, png files are allowed';

            return back()->withNotify($notify);
        }
    }

    public function cancelBy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jobs,id',
        ]);
        $user = Auth::user();
        $job = Job::where('user_id', $user->id)->where('id', $request->id)->firstOrFail();
        $job->status = 2;
        $job->closed_at = Carbon::now();
        $job->save();
        $notify[] = ['success', 'Job has been closed.'];

        return back()->withNotify($notify);
    }

    public function addedtocompleted(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jobs,id',
        ]);
        $user = Auth::user();
        $job = Job::where('user_id', $user->id)->where('id', $request->id)->firstOrFail();
        $job->is_completed = 1;
        $job->is_completed_type = $request->type;
        $job->completed_at = Carbon::now();
        $job->save();
        Acceptedbid::where('job_id', $job->id)->update(['status' => '1']);
        $notify[] = ['success', 'Job has been added to completed.'];

        return back()->withNotify($notify);
    }

    public function jobresponse()
    {
        $user = Auth::user();
        $bidingresponse = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->join('users as freelancer', 'user_biding.user_id', '=', 'freelancer.id')->where('jobs.user_id', $user->id)->select('user_biding.*', 'user_biding.id as bid_id', 'jobs.*', 'freelancer.firstname', 'freelancer.lastname')->where('user_biding.status', 0)->orderBy('bid_id', 'desc')->get();
        $pageTitle = 'Job Responses';
        $emptyMessage = 'No data found';

        // dd($bidingresponse);
        return view($this->activeTemplate.'user.buyer.job.jobresponse', compact('pageTitle', 'emptyMessage', 'bidingresponse'));
    }

    public function assignjoblist()
    {
        $user = Auth::user();
        $bidingresponse = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->join('users as freelancer', 'user_biding.user_id', '=', 'freelancer.id')->where('jobs.user_id', $user->id)->select('user_biding.*', 'user_biding.id as bid_id', 'jobs.*', 'freelancer.firstname', 'freelancer.lastname')->where('user_biding.status', 1)->orderBy('bid_id', 'desc')->get();
        $pageTitle = 'Job Responses';
        $emptyMessage = 'No data found';

        // dd($bidingresponse);
        return view($this->activeTemplate.'user.buyer.job.assignjoblist', compact('pageTitle', 'emptyMessage', 'bidingresponse'));
    }

    public function completejoblist()
    {

        $user = Auth::user();
        $bidingresponse = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->join('users as freelancer', 'user_biding.user_id', '=', 'freelancer.id')->where('jobs.user_id', $user->id)->select('user_biding.*', 'user_biding.id as bid_id', 'jobs.*', 'freelancer.firstname', 'freelancer.lastname')->where('user_biding.status', 2)->orderBy('bid_id', 'desc')->get();
        $pageTitle = 'Job Responses';
        $emptyMessage = 'No data found';

        // dd($bidingresponse);
        return view($this->activeTemplate.'user.buyer.job.completejoblist', compact('pageTitle', 'emptyMessage', 'bidingresponse'));
    }

    public function jobresponsedetail($id)
    {
        $user = Auth::user();
        $bidingresponse = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->join('users as freelancer', 'user_biding.user_id', '=', 'freelancer.id')->where('user_biding.id', $id)->select('user_biding.*', 'user_biding.user_id as freelancer_id', 'jobs.*', 'freelancer.firstname', 'freelancer.lastname')->get();
        $pageTitle = 'Job Responses Detail';
        $emptyMessage = 'No data found';
        // dd($bidingresponse);
        // check if conversation exist
        $checkchat = Conversation::where('job_id', $bidingresponse[0]->job_id)->where('receiver_id', $bidingresponse[0]->freelancer_id)->get();
        $check = false;
        $conversation_id = '';
        if (count($checkchat) > 0) {
            $check = true;
            $conversation_id = $checkchat[0]->id;
            // dd($conversation_id);
        }

        return view($this->activeTemplate.'user.buyer.job.jobresponsedetail', compact('pageTitle', 'emptyMessage', 'bidingresponse', 'check', 'conversation_id'));
    }

    public function assignjob(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'job_id' => 'required',
            'f_id' => 'required',
        ]);
        // update job status
        $update_job = Job::where('id', $request->job_id)->update([
            'job_status' => 1, // mark as assinged
        ]);
        // update user binding status
        $update_userbid = Userbiding::where('job_id', $request->job_id)->where('user_id', $request->f_id)->update([
            'status' => 1,
        ]);
        $notify[] = ['success', 'Job Succesfully assigned.'];

        return redirect(url('/user/buyer/dashboard'))->withNotify($notify);
    }

    public function completed_job(Request $request)
    {
        $request->validate([
            'job_id' => 'required',
            'f_id' => 'required',
            'review' => 'required',
            'rate' => 'required',
        ]);
        $user = Auth::user();
        // getting user biding details
        $getdetail = Userbiding::where('job_id', $request->job_id)->where('user_id', $request->f_id)->select('bid_price')->get();
        $balance = $getdetail[0]->bid_price;
        // update job status
        $update_job = Job::where('id', $request->job_id)->update([
            'job_status' => 2, // mark as completed
        ]);
        // update user binding status
        $update_userbid = Userbiding::where('job_id', $request->job_id)->where('user_id', $request->f_id)->update([
            'status' => 2,
        ]);
        // update user balance
        $updateuser_balance = User::where('id', $request->f_id)->update([
            'balance' => $balance,
        ]);
        // get job price
        $jobdetail = Job::where('id', $request->job_id)->get();
        $job_price = $jobdetail[0]->amount;
        // save review
        $addreview = new Jobreviews;
        $addreview->job_id = $request->job_id;
        $addreview->user_id = $request->f_id;
        $addreview->rating = $request->rate;
        $addreview->review = $request->review;
        $addreview->save();
        // add transaction
        $transaction = new Transaction;
        $transaction->user_id = $user->id;
        $transaction->amount = $job_price;
        $transaction->details = 'Marked job complted';
        $transaction->trx_type = '-';
        $transaction->post_balance = $user->balance;
        $transaction->save();
        //
        $notify[] = ['success', 'Mark Job completed succesfully.'];

        return redirect(url('/user/buyer/dashboard'))->withNotify($notify);
    }
    

    public function removeExtraSkill(Request $request, $id)
    {
        $request->merge(['id' => $id]); // Add the ID to the request data for validation
        $request->validate([
            'id' => 'required|exists:newskill_byuser,id',
        ]);
        

        $extraSkill = NewSkillByUser::find($id);

        if ($extraSkill) {
            $extraSkill->delete();

            return response()->json(['success' => true, 'message' => 'Extra skill removed successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Extra skill not found.'], 404);
        }
    }
}
