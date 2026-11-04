<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Rank;
use App\Models\Service;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function search(Request $request)
    {

        // echo "<pre>";print_r($request->all());echo "</pre>";exit;



        $keyword  = $request->input('keyword');
        $type  = $request->input('type');
        $loc  = $request->input('location');

        if ($type == 'services') {
            $pageTitle = "Service Search";
            $emptyMessage = "No data found";
            $query = Service::where('status', 1);
            if (isset($keyword) && $keyword != '') {
                $query->where('services.title', 'Like', '%' . $keyword . '%')->orWhere('services.description', 'Like', '%' . $keyword . '%')->orWhere('services.tag', 'Like', '%' . $keyword . '%')->orWhere('services.cat_name', 'Like', '%' . $keyword . '%')->orWhere('services.subcat_name', 'Like', '%' . $keyword . '%')->orWhere('services.childcat_name', 'Like', '%' . $keyword . '%');
            }
            $query->select('services.*');
            $services = $query->whereHas('category', function ($q) {
                // $q->where('status', 1);
            })->with('user', 'user.rank')->paginate(8);
            $subcategoryes = $childcategories = $skills = [];
            // $categoryes=DB::table('categories')->where('type','service')->get();
            $categoryes = DB::table('categories')->where('type', 'job')->get();
            if ($services->count() > 0) {
                // echo"<pre>";print_r($user);exit; //ab test kro
                $rank1 = Rank::where('level', 1)->first();
                $rank2 = Rank::where('level', 2)->first();
                $rank3 = Rank::where('level', 3)->first();
                $rank4 = Rank::where('level', 4)->first();
                $rank5 = Rank::where('level', 5)->first();
                // echo"<pre>";print_r($services);exit; //ab test kro
                return view($this->activeTemplate . 'allsearches/services', compact('pageTitle', 'services', 'emptyMessage', 'services', 'categoryes', 'childcategories', 'subcategoryes', 'skills', 'rank1', 'rank2', 'rank3', 'rank4', 'rank5'));
            } else {
                $notify[] = ['error', 'No Search Record Found'];
                return redirect()->back()->withNotify($notify);
            }
        }
        // if($type == 'jobs'){
        //    $pageTitle = "Jobs Search";
        //        $emptyMessage = "No data found";
        //    $query = User::with('rank');
        //        $query->select('users.*');
        //        if (isset($keyword) && $keyword != '') {
        //          $query->where('users.designation', 'Like', '%' . $keyword . '%');
        //        }
        //        if (isset($loc) && $loc != '') {
        //          $query->where('users.address->country', 'Like', '%' . $loc . '%');
        //        }
        //           $user = $query->get()->pluck('id');

        //           echo"<pre>";print_r($user->id);exit;
        //            $services = Service::where('user_id', $user->id)->whereHas('category', function ($q) {
        //            $q->where('status', 1);
        //        })->with('user', 'user.rank')->get();
        //             if($user->count() > 0){
        //             $jobs = Job::whereIn('user_id', $user)->whereHas('category', function ($q) {
        //            $q->where('status', 1);
        //        })->with('user', 'user.rank')->orderBy('id','desc')->paginate(8);
        //            echo"<pre>";print_r($user);exit;
        //        return view($this->activeTemplate . 'allsearches/jobs', compact('pageTitle', 'jobs', 'emptyMessage'));
        //          }else{
        //           $notify[] = ['error', 'No Search Record Found'];
        //            return redirect()->back()->withNotify($notify);
        //          }

        // }
        if ($type == 'jobs') {
            $pageTitle = "Jobs Search";
            $emptyMessage = "No data found";
            $query = Job::where('status', 1);
            if (isset($keyword) && $keyword != '') {
                $query->where('jobs.title', 'Like', '%' . $keyword . '%')->orWhere('jobs.skill', 'Like', '%' . $keyword . '%')->orWhere('jobs.cat_name', 'Like', '%' . $keyword . '%')->orWhere('jobs.subcat_name', 'Like', '%' . $keyword . '%');
            }
            $query->select('jobs.*');
            $jobs = $query->where('job_status', 0)->whereHas('category', function ($q) {
                $q->where('status', 1);
            })->with('user', 'user.rank', 'jobBiding')->orderBy('id', 'desc')->get();
            $subcategoryes = $childcategories = $skills = [];
            $categoryes = DB::table('categories')->where('type', 'job')->get();
            // echo"<pre>";print_r($jobs);exit;
            if ($jobs->count() > 0) {
                return view($this->activeTemplate . 'allsearches/jobs', compact('pageTitle', 'jobs', 'emptyMessage', 'subcategoryes', 'categoryes'));
            } else {
                $notify[] = ['error', 'No Search Record Found'];
                return redirect()->back()->withNotify($notify);
            }
        }
        if ($type == 'professionals') {
            $pageTitle = "Professionals Search";
            $emptyMessage = "No data found";
            $query = User::with('rank');
            $query->select('users.*');
            $query->where('users.designation', 'Like', '%' . $keyword . '%');
            // $query->orWhere('users.designation', 'Like', '%' . $keyword . '%')->orWhere('user_profile_skills.designation', 'Like', '%' . $keyword . '%');
            if (isset($loc) && $loc != '') {
                $query->where('users.address->country', 'Like', '%' . $loc . '%');
            }
            $user = $query->get()->pluck('id');
            // echo"<pre>";print_r($user);exit; //ab test kro
            //       $services = Service::whereIn('user_id', $user)->whereHas('category', function ($q) {
            //     // $q->where('status', 1);
            // })->with('user', 'user.rank')->get();
            // $a = $query->first();
            // echo"<pre>";print_r($user->id);exit;
            if ($user->count() > 0) {
                $user = User::whereIn('id', $user)->where('status', 1)->where('is_freelancer', 1)->get();
                // echo"<pre>";print_r($user);exit;
                $subcategoryes = $childcategories = $skills = [];
                $categoryes = DB::table('categories')->where('type', 'job')->get();
                return view($this->activeTemplate . 'allsearches/professional', compact('pageTitle', 'user', 'emptyMessage', 'categoryes', 'childcategories', 'subcategoryes', 'skills'));
            } else {
                $notify[] = ['error', 'No Search Record Found'];
                return redirect()->back()->withNotify($notify);
            }
        }
    }

    public function filterjobs(Request $request)
    {
        $return_array = [];

        if ($request->all()) {
            $postData = $request->all();
            $jobs = $this->filters_jobs($postData, 'count'); // Get filtered jobs

            if (count($jobs) > 0) {
                $return_array['status'] = 'true';
                $return_array['jobs'] = view('jobs_filtered')->with('jobs', $jobs)->render();
                $return_array['count'] = count($jobs);
            } else {
                $return_array['status'] = 'false';
                $return_array['jobs'] = view('jobs_filtered')->with('jobs', $jobs)->render();
                $return_array['count'] = 0;
            }
        } else {
            $return_array['status'] = 'false';
            $return_array['jobs'] = [];
            $return_array['count'] = 0;
        }

        return response()->json($return_array);
    }

    function filters_jobs($postData = NULL, $param = NULL)
    {
        $query = Job::query()->where('status', '1');

        // if (isset($postData['keyword']) && !empty($postData['keyword'])) {
        //     $query->where('jobs.title', 'Like', '%' . $postData['keyword'] . '%')->orWhere('jobs.skill', 'Like', '%' . $postData['keyword'] . '%');
        // }
        if (isset($postData['job_title']) && !empty($postData['job_title'])) {
            $query->where('title', 'LIKE', "%{$postData['job_title']}%");
        }
        if (isset($postData['search_location']) && !empty($postData['search_location'])) {
            $query->where('country', 'LIKE', "%{$postData['search_location']}%");
        }
        if (isset($postData['cat_id']) && !empty($postData['cat_id'])) {
            $query->where('category_id', $postData['cat_id']);
        }
        if (isset($postData['subcategory']) && count($postData['subcategory']) > 0) {
            $query->whereIn('sub_category_id', $postData['subcategory']);
        }
        if (isset($postData['job_type']) && count($postData['job_type']) > 0) {
            $query->whereIn('priority', $postData['job_type']);
        }
        if (isset($postData['keyword']) && !empty($postData['keyword'])) {
            $query->where('title', 'like', '%' . $postData['keyword'] . '%')
                ->orWhere('skill', 'like', '%' . $postData['keyword'] . '%');
        }
        if (isset($postData['category']) && !empty($postData['category'])) {
            $query->where('category_id', $postData['category']);
        }
        if (isset($postData['subcategory']) && !empty($postData['subcategory'])) {
            $query->whereIn('sub_category_id', $postData['subcategory']);
        }
        // if (isset($postData['skills']) && !empty($postData['skills'])) {
        //     $query->where('jobs.skill', 'Like', '%' . $postData['skills'] . '%');
        // }
        if (isset($postData['skills']) && !empty($postData['skills'])) {
            $skillIds = $postData['skills'];
            $query->whereHas('skills', function ($q) use ($skillIds) {
                $q->whereIn('skills.id', $skillIds);
            });
        }
        if (isset($postData['exp']) && !empty($postData['exp'])) {
            $query->where('jobs.exp_type', $postData['exp']);
        }
        if (isset($postData['countries']) && !empty($postData['countries'])) {
            $query->whereIn('jobs.country', $postData['countries']);
        }
        if (isset($postData['jtype']) && !empty($postData['jtype'])) {
            $query->where('jobs.budgettype', $postData['jtype']);
        }
        if (isset($postData['minbudget']) && !empty($postData['minbudget'])) {
            $minbudget = $postData['minbudget'];
            $query->where('jobs.amount', '>=', $minbudget);
        }
        if (isset($postData['maxbudget']) && !empty($postData['maxbudget'])) {
            $maxbudget = $postData['maxbudget'];
            $query->where('jobs.amount', '<=', $maxbudget);
        }
        if (isset($postData['minhoursperweek']) && !empty($postData['minhoursperweek'])) {
            $minWeeklyHours = $postData['minhoursperweek'];
            $query->where('jobs.weekly_hours', '>=', $minWeeklyHours);
        }
        if (isset($postData['maxhoursperweek']) && !empty($postData['maxhoursperweek'])) {
            $maxWeeklyHours = $postData['maxhoursperweek'];
            $query->where('jobs.weekly_hours', '<=', $maxWeeklyHours);
        }

        // $content_data = $query->orderBy('id', 'desc')->get();
        $content_data = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return $content_data;
    }

    public function filterservices(Request $request)
    {
        $return_array = array();
        if ($request->all()) {
            $postData = $request->all();
            // print_r($postData); die();
            $serviceData = $this->filters_services($postData, 'count');
            $count =  count($serviceData);
            $pageData['serviceData'] = $serviceData;
            $return_array['status'] = 'true';
            $return_array['content_data'] = view('service_filtered')->with('pageData', $pageData)->render();
            // $return_array['content_data'] = view('categorywise_product')->with('pageData', $pageData)->render();
            // print_r($return_array['content_data']); die();
            $return_array['count'] = $count;
        } else {
            $return_array['status'] = 'false';
            $return_array['content_data'] = '';
            $return_array['count'] = 0;
        }
        echo json_encode($return_array);
    }

    function filters_services_old($postData = NULL, $param = NULL)
    {
        $query = User::with('rank');
        $query->select('users.*');

        if (isset($postData['keyword']) && !empty($postData['keyword'])) {
            $query->where('users.designation', 'Like', '%' . $postData['keyword'] . '%');
        }
        if (isset($postData['location']) && !empty($postData['location'])) {
            $query->where('users.address->country', 'Like', '%' . $postData['location'] . '%');
        }

        if (isset($postData['minbudget']) && !empty($postData['minbudget'])) {
            $minbudget = $postData['minbudget'];
            $query->where('users.per_hour_rate', '>=', $minbudget);
        }
        if (isset($postData['maxbudget']) && !empty($postData['maxbudget'])) {
            $maxbudget = $postData['maxbudget'];
            $query->where('users.per_hour_rate', '<=', $maxbudget);
        }
        if (isset($postData['exp']) && !empty($postData['exp'])) {
            $query->whereIn('users.level', $postData['exp']);
        }
        if (isset($postData['fix']) && !empty($postData['fix'])) {
            $query->where('users.budgettype', $postData['fix']);
        }
        if (isset($postData['category']) && !empty($postData['category'])) {
            $query->where('users.cat_id', $postData['category']);
        }
        if (isset($postData['subcategory']) && !empty($postData['subcategory'])) {
            $query->where('users.subcat_id', $postData['subcategory']);
        }

        $user = $query->get()->pluck('id');
        $services = Service::whereIn('user_id', $user)
            // ->whereHas('category', function ($q) {
            //       $q->where('status', 1);
            //  })
            ->with('user', 'user.rank');

        $content_data = $services->get();
        return $content_data;
    }


    function filters_services($postData = NULL, $param = NULL)
    {
        $query1 = DB::table('users');
        $query1->select(DB::raw("GROUP_CONCAT(users.id) as user_id"));
        if (isset($postData['lang_level']) && !empty($postData['lang_level'])) {
            $query1->join('user_languages', 'user_languages.user_id', '=', 'users.id');
            $query1->where('user_languages.type', $postData['lang_level'])->where('user_languages.name', 'English');
            // $ulids = UserLanguage::where('name','english')->where('type',$postData['lang_level'])->get()->pluck('user_id');
        } elseif (isset($postData['otherlang']) && !empty($postData['otherlang'])) {
            $query1->join('user_languages', 'user_languages.user_id', '=', 'users.id');
            $query1->where('user_languages.name', $postData['otherlang']);
        }

        // if (isset($postData['otherlang']) && !empty($postData['otherlang'])) {
        //   $ulot = UserLanguage::where('name',$postData['otherlang'])->get()->pluck('user_id');
        // }

        $userl = $query1->first();
        $ulids = explode(',', $userl->user_id);
        // echo $userl;exit;
        // print_r($ulids);exit;

        // skills
        $query2 = DB::table('users');
        $query2->select(DB::raw("GROUP_CONCAT(users.id) as user_id"));
        if (isset($postData['skills']) && !empty($postData['skills'])) {
            $query2->join('user_profile_skills', 'user_profile_skills.user_id', '=', 'users.id');
            $query2->where('user_profile_skills.skill_id', $postData['skills']);
        }
        $usersk = $query2->first();
        // echo $usersk;exit;
        $uskids = explode(',', $usersk->user_id);
        // print_r($uskids);exit;

        $query3 = DB::table('users');
        $query3->select(DB::raw("GROUP_CONCAT(users.id) as user_id"));
        if (isset($postData['keyword']) && !empty($postData['keyword'])) {
            $query3->join('services', 'services.user_id', '=', 'users.id');
            $query3->where('services.title', 'Like', '%' . $postData['keyword'] . '%')->orWhere('services.tag', 'Like', '%' . $postData['keyword'] . '%');
        } elseif (isset($postData['category']) && !empty($postData['category'])) {
            $query3->join('services', 'services.user_id', '=', 'users.id');
            $query3->where('services.category_id', $postData['category']);
        } elseif (isset($postData['subcategory']) && !empty($postData['subcategory'])) {
            $query3->join('services', 'services.user_id', '=', 'users.id');
            $query3->where('services.sub_category_id', $postData['subcategory']);
        } elseif (isset($postData['skills']) && !empty($postData['skills'])) {
            $query3->join('services', 'services.user_id', '=', 'users.id');
            $query3->where('services.tag', 'Like', '%' . $postData['skills'] . '%');
        }
        $userti = $query3->first();
        $ustit = explode(',', $userti->user_id);


        $query4 = DB::table('users');
        $query4->select(DB::raw("GROUP_CONCAT(users.id) as user_id"));
        if (isset($postData['minbudget']) && !empty($postData['minbudget'])) {
            $query4->join('services_details', 'services_details.user_id', '=', 'users.id');
            $minbudget = $postData['minbudget'];
            $query4->where('services_details.price1', '>=', $minbudget);
        } elseif (isset($postData['maxbudget']) && !empty($postData['maxbudget'])) {
            $query4->join('services_details', 'services_details.user_id', '=', 'users.id');
            $maxbudget = $postData['maxbudget'];
            $query4->where('services_details.price1', '<=', $maxbudget);
        }
        $userserdet = $query4->first();
        $usservdet = explode(',', $userserdet->user_id);



        $query = DB::table('users');
        if (isset($ulids) && count($ulids)) {
            $query->whereIn('id', $ulids);
        }
        if (isset($uskids) && count($uskids)) {
            $query->whereIn('id', $uskids);
        }
        if (isset($ustit) && count($ustit)) {
            $query->whereIn('id', $ustit);
        }
        if (isset($usservdet) && count($usservdet)) {
            $query->whereIn('id', $usservdet);
        }

        // if(isset($ulot) && count($ulot)){
        //   $query->whereIn('id',$ulot);
        //  }

        // if (isset($postData['keyword']) && !empty($postData['keyword'])) {
        //   $query->where('users.designation', 'Like', '%' . $postData['keyword'] . '%');
        // }
        if (isset($postData['location']) && !empty($postData['location'])) {
            $query->where('users.address->country', 'Like', '%' . $postData['location'] . '%');
        }

        // if (isset($postData['minbudget']) && !empty($postData['minbudget'])) {
        //      $minbudget = $postData['minbudget'];
        //      $query->where('users.per_hour_rate', '>=', $minbudget);
        //    }
        //    if (isset($postData['maxbudget']) && !empty($postData['maxbudget'])) {
        //      $maxbudget = $postData['maxbudget'];
        //      $query->where('users.per_hour_rate', '<=', $maxbudget);
        //    }
        if (isset($postData['exp']) && !empty($postData['exp'])) {
            $query->whereIn('users.level', $postData['exp']);
        }
        if (isset($postData['fix']) && !empty($postData['fix'])) {
            $query->where('users.budgettype', $postData['fix']);
        }


        $user = $query->get()->pluck('id');
        $services = Service::whereIn('user_id', $user)
            // ->whereHas('category', function ($q) {
            //       $q->where('status', 1);
            //  })
            ->with('user', 'user.rank');


        $content_data = $services->get();
        return $content_data;
    }

    public function filterpros(Request $request)
    {
        $return_array = array();
        if ($request->all()) {
            $postData = $request->all();
            $proData = $this->filters_pro($postData, 'count');
            if (count($proData) > 0) {
                $return_array['status'] = 'true';
                $return_array['professionals'] = view('pro_filtered')->with('professionals', $proData)->render();
                $return_array['count'] = count($proData);
                $return_array['total_records'] = $proData->total();
            } else {
                $return_array['status'] = 'false';
                $return_array['professionals'] = view('pro_filtered')->with('professionals', $proData)->render();
                $return_array['count'] = 0;
                $return_array['total_records'] = 0;
            }
        } else {
            $return_array['status'] = 'false';
            $return_array['professionals'] = '';
            $return_array['count'] = 0;
            $return_array['total_records'] = 0;
        }

        return response()->json($return_array);
    }

    function filters_pro($postData = NULL, $param = NULL)
    {
        // Initialize the main query
        $query = DB::table('users')->select('users.*');

        // Handle rating filter
        if (isset($postData['rating']) && !empty($postData['rating'])) {
            $rating = (int) $postData['rating'];

            // Subquery to get user IDs based on average rating
            $userIds = DB::table('users')
                ->join('review_ratings', 'review_ratings.pro_id', '=', 'users.id')
                ->select('users.id')
                ->groupBy('users.id')
                ->havingRaw('AVG(review_ratings.rating) >= ? AND AVG(review_ratings.rating) < ?', [$rating, $rating + 1])
                ->havingRaw('COUNT(review_ratings.id) > 0')
                ->pluck('users.id')
                ->toArray();

            // Apply user IDs filter to the main query
            if (!empty($userIds)) {
                $query->whereIn('users.id', $userIds);
            } else {
                // If no users match the rating criteria, return no results
                $query->whereRaw('1 = 0');
            }
        }

        // Handle skill filter
        if (isset($postData['skill']) && !empty($postData['skill'])) {
            $skill_ids = Skill::where('name', $postData['skill'])->pluck('id')->toArray();
            if (!empty($skill_ids)) {
                $query->join('user_profile_skills', 'user_profile_skills.user_id', '=', 'users.id')
                    ->whereIn('user_profile_skills.skill_id', $skill_ids);
            }
        }

        // Other filters
        if (isset($postData['lang_level']) && !empty($postData['lang_level'])) {
            $query->join('user_languages', 'user_languages.user_id', '=', 'users.id')
                ->whereIn('user_languages.type', $postData['lang_level']);
        }
        // elseif (isset($postData['otherlang']) && !empty($postData['otherlang'])) {
        //     $query->join('user_languages', 'user_languages.user_id', '=', 'users.id')
        //         ->where('user_languages.name', $postData['otherlang']);
        // }

        if (isset($postData['languages']) && !empty($postData['languages'])) {
            $query->whereExists(function ($subQuery) use ($postData) {
                $subQuery->select(DB::raw(1))
                    ->from('user_languages')
                    ->whereIn('user_languages.name', (array) $postData['languages'])
                    ->whereColumn('user_languages.user_id', 'users.id');
            });
        }

        if (isset($postData['rank']) && count($postData['rank']) > 0) {
            $query->whereIn('rank_id', $postData['rank']);
        }

        if (isset($postData['name']) && !empty($postData['name'])) {
            $query->where('username', 'like', '%' . $postData['name'] . '%');
        }

        if (isset($postData['cat_id']) && !empty($postData['cat_id'])) {
            $query->where('cat_id', $postData['cat_id']);
        }

        if (isset($postData['keyword']) && !empty($postData['keyword'])) {
            $query->where('designation', 'like', '%' . $postData['keyword'] . '%');
        }

        if (isset($postData['location']) && !empty($postData['location'])) {
            $query->where('address->country', 'like', '%' . $postData['location'] . '%');
        }

        if (isset($postData['minbudget']) && !empty($postData['minbudget'])) {
            $query->where('per_hour_rate', '>=', $postData['minbudget']);
        }

        if (isset($postData['maxbudget']) && !empty($postData['maxbudget'])) {
            $query->where('per_hour_rate', '<=', $postData['maxbudget']);
        }

        if (isset($postData['exp']) && !empty($postData['exp'])) {
            $query->whereIn('level', (array) $postData['exp']);
        }

        if (isset($postData['fix']) && !empty($postData['fix'])) {
            $query->where('budgettype', $postData['fix']);
        }

        if (isset($postData['category']) && !empty($postData['category'])) {
            $query->where('cat_id', $postData['category']);
        }

        if (isset($postData['subcategory']) && !empty($postData['subcategory'])) {
            $query->whereIn('subcat_id', (array) $postData['subcategory']);
        }

        if (isset($postData['country']) && !empty($postData['country'])) {
            $query->whereIn('country', (array) $postData['country']);
        }

        if (isset($postData['min_hourly_rate']) && !empty($postData['min_hourly_rate'])) {
            if (isset($postData['max_hourly_rate']) && !empty($postData['max_hourly_rate'])) {
                $query->whereBetween('per_hour_rate', [
                    $postData['min_hourly_rate'],
                    $postData['max_hourly_rate']
                ]);
            } else {
                $query->where('per_hour_rate', '>=', $postData['min_hourly_rate']);
            }
        } elseif (isset($postData['max_hourly_rate']) && !empty($postData['max_hourly_rate'])) {
            $query->where('per_hour_rate', '<=', $postData['max_hourly_rate']);
        }
        // Weekly Hours
        if (isset($postData['min_weekly_hours']) && !empty($postData['min_weekly_hours'])) {
            $query->where(function ($q) use ($postData) {
                $q->where('weekly_hours', '<=', $postData['min_weekly_hours'])
                    ->orWhere('weekly_hours', 'open_offer');
            });
        } elseif (isset($postData['max_weekly_hours']) && !empty($postData['max_weekly_hours'])) {
            $query->where(function ($q) use ($postData) {
                $q->where('weekly_hours', '>=', $postData['max_weekly_hours'])
                    ->orWhere('weekly_hours', 'open_offer');
            });
        }

        // Final query with user details and average rating
        $avgRatingSubquery = DB::table('review_ratings')
            ->select('pro_id', DB::raw('AVG(likely_rating) as avg_rating'))
            ->groupBy('pro_id');

        $users = User::with('freelancer_membership_transaction.membership')
            ->whereIn('users.id', $query->pluck('id'))
            ->where('users.status', 1)
            ->where('users.is_freelancer', 1)
            ->where('users.per_hour_rate', '!=', '')
            ->leftJoinSub($avgRatingSubquery, 'avg_ratings', function ($join) {
                $join->on('users.id', '=', 'avg_ratings.pro_id');
            })
            ->leftJoin('freelancer_membership_transactions', 'users.id', '=', 'freelancer_membership_transactions.user_id')
            ->leftJoin('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')
            ->select('users.*', DB::raw('COALESCE(avg_ratings.avg_rating, 0) as avg_rating'))
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

        $content_data = $users->paginate(6)->withQueryString();
        return $content_data;
    }
    // function filters_pro($postData = NULL, $param = NULL)
    // {
    //     // if (!empty($postData['skill']))
    //     //     $skill_ids = Skill::where('name', $postData['skill'])->get()->pluck('id');
    //     // $query1 = DB::table('users');
    //     // $query1->select(DB::raw("GROUP_CONCAT(users.id) as user_id"));
    //     // if (isset($postData['lang_level']) && !empty($postData['lang_level'])) {
    //     //     $query1->join('user_languages', 'user_languages.user_id', '=', 'users.id');
    //     //     $query1->where('user_languages.type', $postData['lang_level'])->where('user_languages.name', 'English');
    //     //     // $ulids = UserLanguage::where('name','english')->where('type',$postData['lang_level'])->get()->pluck('user_id');
    //     // } elseif (isset($postData['otherlang']) && !empty($postData['otherlang'])) {
    //     //     $query1->join('user_languages', 'user_languages.user_id', '=', 'users.id');
    //     //     $query1->where('user_languages.name', $postData['otherlang']);
    //     // }
    //     // if (isset($postData['languages']) && !empty($postData['languages'])) {
    //     //     $ulot = UserLanguage::whereIn('name', (array) $postData['languages'])->pluck('user_id');
    //     //     $query1->whereIn('users.id', $ulot);
    //     // }
    //     // if (isset($postData['rating']) && !empty($postData['rating'])) {
    //     //     $rating = (int) $postData['rating'];

    //     //     $query1->join('review_ratings', 'review_ratings.pro_id', '=', 'users.id')
    //     //         ->select('users.*')
    //     //         ->selectRaw('AVG(review_ratings.rating) as avg_rating')
    //     //         ->groupBy('users.id');
    //     //     if ($rating == 5) {
    //     //         $query1->havingRaw('AVG(review_ratings.rating) = 5');
    //     //     } else {
    //     //         $query1->havingRaw('AVG(review_ratings.rating) >= ? AND AVG(review_ratings.rating) < ?', [$rating, $rating + 1]);
    //     //     }
    //     // }
    //     // $userl = $query1->first();
    //     // $ulids = explode(',', $userl->user_id);

    //     if (!empty($postData['skill'])) {
    //         $skill_ids = Skill::where('name', $postData['skill'])->get()->pluck('id');
    //     }

    //     $query1 = DB::table('users');

    //     // Only select GROUP_CONCAT if we're not using rating filter
    //     if (!isset($postData['rating']) || empty($postData['rating'])) {
    //         $query1->select(DB::raw("GROUP_CONCAT(users.id) as user_id"));
    //     } else {
    //         // For rating filter, we'll handle the selection differently
    //         $query1->select('users.id');
    //     }

    //     // Your existing joins and conditions
    //     if (isset($postData['lang_level']) && !empty($postData['lang_level'])) {
    //         $query1->join('user_languages', 'user_languages.user_id', '=', 'users.id');
    //         $query1->where('user_languages.type', $postData['lang_level'])->where('user_languages.name', 'English');
    //     } elseif (isset($postData['otherlang']) && !empty($postData['otherlang'])) {
    //         $query1->join('user_languages', 'user_languages.user_id', '=', 'users.id');
    //         $query1->where('user_languages.name', $postData['otherlang']);
    //     }

    //     if (isset($postData['languages']) && !empty($postData['languages'])) {
    //         $ulot = UserLanguage::whereIn('name', (array) $postData['languages'])->pluck('user_id');
    //         $query1->whereIn('users.id', $ulot);
    //     }


    //     if (isset($postData['rating']) && !empty($postData['rating'])) {
    //         $rating = (int) $postData['rating'];

    //         $userIds = DB::table('users')
    //             ->join('review_ratings', 'review_ratings.pro_id', '=', 'users.id')
    //             ->select('users.id')
    //             ->groupBy('users.id')
    //             ->when($rating == 5, function ($q) {
    //                 $q->havingRaw('AVG(review_ratings.rating) = 5');
    //             }, function ($q) use ($rating) {
    //                 $q->havingRaw('AVG(review_ratings.rating) >= ? AND AVG(review_ratings.rating) < ?', [$rating, $rating + 1]);
    //             })
    //             ->havingRaw('COUNT(review_ratings.id) > 0')
    //             ->pluck('users.id')
    //             ->toArray();

    //         $ulids = $userIds;
    //     } else {
    //         $userl = $query1->first();
    //         if ($userl && property_exists($userl, 'user_id')) {
    //             $ulids = explode(',', $userl->user_id);
    //         } else {
    //             $ulids = [];
    //         }
    //     }
    //     // skills
    //     $query2 = DB::table('users');
    //     $query2->select(DB::raw("GROUP_CONCAT(users.id) as user_id"));


    //     if (isset($postData['skill']) && !empty($postData['skill'])) {
    //         $query2->join('user_profile_skills', 'user_profile_skills.user_id', '=', 'users.id');
    //         $query2->whereIn('user_profile_skills.skill_id', $skill_ids);
    //     }
    //     if (isset($postData['name']) && !empty($postData['name'])) {
    //         $query2->where('username', 'like', '%' . $postData['name'] . '%');
    //     }
    //     $usersk = $query2->first();
    //     $uskids = explode(',', $usersk->user_id);

    //     $query = DB::table('users');
    //     if (isset($ulids) && count($ulids)) {
    //         $query->whereIn('id', $ulids);
    //     }
    //     if (isset($postData['cat_id'])) {
    //         $query->where('cat_id', $postData['cat_id']);
    //     }
    //     if (isset($uskids) && count($uskids)) {
    //         $query->whereIn('id', $uskids);
    //     }
    //     if (isset($postData['keyword']) && !empty($postData['keyword'])) {
    //         $query->where('users.designation', 'Like', '%' . $postData['keyword'] . '%');
    //     }
    //     if (isset($postData['location']) && !empty($postData['location'])) {
    //         $query->where('users.address->country', 'Like', '%' . $postData['location'] . '%');
    //     }

    //     if (isset($postData['minbudget']) && !empty($postData['minbudget'])) {
    //         $minbudget = $postData['minbudget'];
    //         $query->where('users.per_hour_rate', '>=', $minbudget);
    //     }
    //     if (isset($postData['maxbudget']) && !empty($postData['maxbudget'])) {
    //         $maxbudget = $postData['maxbudget'];
    //         $query->where('users.per_hour_rate', '<=', $maxbudget);
    //     }
    //     if (isset($postData['exp']) && !empty($postData['exp'])) {
    //         $query->whereIn('users.level', $postData['exp']);
    //     }
    //     if (isset($postData['fix']) && !empty($postData['fix'])) {
    //         $query->where('users.budgettype', $postData['fix']);
    //     }
    //     if (isset($postData['category']) && !empty($postData['category'])) {
    //         $query->where('users.cat_id', $postData['category']);
    //     }
    //     if (isset($postData['subcategory']) && !empty($postData['subcategory'])) {
    //         $query->whereIn('users.subcat_id', $postData['subcategory']);
    //     }

    //     if (isset($postData['country']) && !empty($postData['country'])) {
    //         $query->whereIn('users.country', $postData['country']);
    //     }

    //     if (isset($postData['min_hourly_rate']) && !empty($postData['min_hourly_rate'])) {
    //         if (isset($postData['max_hourly_rate']) && !empty($postData['max_hourly_rate'])) {
    //             $query->whereBetween('users.per_hour_rate', [
    //                 $postData['min_hourly_rate'],
    //                 $postData['max_hourly_rate']
    //             ]);
    //         } else {
    //             $query->where('users.per_hour_rate', $postData['min_hourly_rate']);
    //         }
    //     } elseif (isset($postData['max_hourly_rate']) && !empty($postData['max_hourly_rate'])) {
    //         $query->where('users.per_hour_rate', '<=', $postData['max_hourly_rate']);
    //     }
    //     $query->select('users.*');
    //     $user = $query->get()->pluck('id');

    //     $users = User::with('freelancer_membership_transaction.membership')
    //         ->whereIn('users.id', $user)
    //         ->where('users.status', 1)
    //         ->where('users.is_freelancer', 1)
    //         ->where('users.per_hour_rate', '!=', '');

    //     $avgRatingSubquery = DB::table('review_ratings')
    //         ->select('pro_id', DB::raw('AVG(likely_rating) as avg_rating'))
    //         ->groupBy('pro_id');

    //     if (isset($postData['languages']) && !empty($postData['languages'])) {
    //         $query->whereExists(function ($subQuery) use ($postData) {
    //             $subQuery->select(DB::raw(1))
    //                 ->from('user_languages')
    //                 ->whereIn('user_languages.name', (array) $postData['languages'])
    //                 ->whereColumn('user_languages.user_id', 'users.id');
    //         });
    //     }

    //     $users->leftJoinSub($avgRatingSubquery, 'avg_ratings', function ($join) {
    //         $join->on('users.id', '=', 'avg_ratings.pro_id');
    //     })

    //         ->leftJoin('freelancer_membership_transactions', 'users.id', '=', 'freelancer_membership_transactions.user_id')
    //         ->leftJoin('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')
    //         ->select('users.*', DB::raw('COALESCE(avg_ratings.avg_rating, 0) as avg_rating'))
    //         ->orderByRaw("
    //         CASE
    //             WHEN freelancer_membership.title = 'Premium' THEN 1
    //             WHEN freelancer_membership.title = 'Standard' THEN 2
    //             WHEN freelancer_membership.title = 'Essentional' THEN 3
    //             WHEN freelancer_membership.title = 'Basic' THEN 4
    //             WHEN freelancer_membership.title = 'Try it free' THEN 5
    //             ELSE 6
    //         END
    //     ")
    //     ->orderBy('avg_rating', 'DESC');

    //     $content_data = $users->paginate(6)->withQueryString();
    //     return $content_data;
    // }



    // search other langs
    public function search_other_langs(Request $request)
    {
        $return_array = array();
        if ($request->all()) {
            $postData = $request->all();
            // print_r($postData); die();
            $lngData = $this->search_lng($postData, 'count');
            $count =  count($lngData);
            $pageData['lngData'] = $lngData;
            $return_array['status'] = 'true';
            $return_array['content_data'] = view('lng_searched')->with('pageData', $pageData)->render();
            // $return_array['content_data'] = view('categorywise_product')->with('pageData', $pageData)->render();
            // print_r($return_array['content_data']); die();
            $return_array['count'] = $count;
        } else {
            $return_array['status'] = 'false';
            $return_array['content_data'] = '';
            $return_array['count'] = 0;
        }
        // echo '<pre>'; print_r($this->db->last_query());exit;
        // echo "<pre>";print_r($pageData['strain_data']);exit;
        // echo "<pre";print_r($view_array);echo "</pre>";exit;
        echo json_encode($return_array);
    }

    function search_lng($postData = NULL, $param = NULL)
    {
        $query = DB::table('profile_languages');



        if (isset($postData['search']) && !empty($postData['search'])) {
            $query->where('profile_languages.name', 'Like', '%' . $postData['search'] . '%');
        }




        $content_data = $query->get();
        return $content_data;
    }

    public function getbidjobdetail(Request $req)
    {
        $es = DB::table('user_biding')->where('id', $req->id)->first();
        return response()->json($es);
    }

    public function countfee_multi_bids(Request $req)
    {
        $arrToSend = $req->arrbidamount;
        foreach ($arrToSend as $key => $visitor) {

            $sum = [$key];
        }
        $return_array['status'] = 'true';
        $return_array['content_data'] = $sum;
        echo json_encode($return_array);
    }
}
