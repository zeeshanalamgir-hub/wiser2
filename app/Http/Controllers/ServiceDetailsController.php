<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\ChildCategories;
use App\Models\Comment;
use App\Models\Conversation;
use App\Models\ReviewRating;
use App\Models\Service;
use App\Models\ServicesDetail;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceDetailsController extends Controller
{
    public $activeTemplate = null;
    public function __construct(
    ) {
        $this->activeTemplate = activeTemplate();
    }

    // public function serviceDetails($id)
    // {
    //     $us = Auth::user();
    //     $sid = 0; //;$us->id;
    //     $chats = [];

    //     $service = Service::with('skills','extraSkills')->where('id', decrypt($id))->first();

    //     if (!$service) {
    //         // Handle the case where no service was found
    //         abort(404, 'Service not found.');
    //     }

    //     $rid = $service->user_id;

    //     $pageTitle = "Service details";
    //     $serviceQuery = Service::query();

    //     if ($us && $us->is_freelancer != 1) {
    //         $serviceQuery->where('status', 1);
    //     }

    //     $serviceQuery->whereHas('category', function ($q) {
    //         $q->where('status', 1);
    //     });

    //     $service = $serviceQuery->with('skills','extraSkills')->where('id', decrypt($id))->firstOrFail();

    //     $otherServices = Service::where('id', '!=', $service->id)
    //         ->where('status', 1)
    //         ->whereHas('category', function ($q) {
    //             $q->where('status', 1);
    //         })
    //         ->where('user_id', $service->user_id)
    //         ->with('user', 'user.rank', 'latestDetail')
    //         ->limit(4)
    //         ->orderBy('id', 'DESC')
    //         ->get();


    //     $titleKeywords = preg_split('/\s+/', $service->title);

    //     $releatedServices = Service::where('id', '!=', $service->id)
    //         ->where('status', 1)
    //         ->whereHas('category', function ($q) {
    //             $q->where('status', 1);
    //         })
    //         ->where(function ($q) use ($titleKeywords) {
    //             foreach ($titleKeywords as $keyword) {
    //                 $q->orWhere('title', 'like', "%{$keyword}%");
    //             }
    //         })
    //         ->with('user', 'user.rank','skills','extraSkills')
    //         ->orderBy('id', 'DESC')
    //         ->get();


    //     $totalService = Service::where('status', 1)->whereHas('category', function ($q) {
    //         $q->where('status', 1);
    //     })->where('user_id', $service->user_id)->with('user','skills','extraSkills')->count();

    //     $activeUser = Auth::user();
    //     $conversion = null;
    //     $serviceGetRating = null;

    //     $serviceUser = User::where('id', $service->user_id)->firstOrFail();
    //     if ($activeUser) {
    //         $serviceGetRating = Booking::where('seller_id', $activeUser->id)->where('working_status', 1)->where('service_id', $service->id)->first();
    //         $conversion = Conversation::where(function ($query) use ($activeUser, $serviceUser) {
    //             $query->orWhere('sender_id', $activeUser->id)
    //                 ->orWhere('receiver_id', $activeUser->id);
    //         })->where(function ($query2) use ($activeUser, $serviceUser) {
    //             $query2->orWhere('sender_id', $serviceUser->id)->orWhere('receiver_id', $serviceUser->id);
    //         })->first();
    //     }

    //     $workInprogress = Booking::where('status', '!=', 0)->where('working_status', 4)
    //         ->whereHas('service', function ($q) use ($service) {
    //             $q->where('user_id', $service->user_id);
    //         })->count();

    //     $reviewRataingAvg = ReviewRating::whereHas('service', function ($q) use ($service) {
    //         $q->where('user_id', $service->user_id);
    //     })->orWhereHas('software', function ($q) use ($service) {
    //         $q->where('user_id', $service->user_id);
    //     })->avg('rating');

    //     $serviceDetails = ServicesDetail::where('service_id', $service->id)->where('is_active',1)->get();


    //     $availableFeatures = DB::table('service_tiers')
    //         ->where('type', 'checkbox')
    //         ->where('cat_id', $service->category_id)
    //         ->where('subcat_id', $service->sub_category_id)
    //         ->when($service->childcat_id > 0, function ($q) use ($service) {
    //             return $q->where('childcat_id', $service->childcat_id);
    //         })
    //         ->get();

    //     $userFeatures = DB::table('service_detail_tiers')
    //         ->whereIn('service_detail_id', $serviceDetails->pluck('id'))
    //         ->get()
    //         ->groupBy('service_detail_id')
    //         ->mapWithKeys(function ($item, $key) {
    //             return [$key => $item];
    //         });

    //     $userFeatures = collect($userFeatures);
    //     foreach ($serviceDetails as $detail) {
    //         if (!isset($userFeatures[$detail->id])) {
    //             $userFeatures[$detail->id] = collect();
    //         }
    //     }

    //     $totalReviews = ReviewRating::where('service_id', $service->id)->count();

    //     $comments = Comment::where('service_id', $service->id)->with('user', 'commentReply')->paginate(7);
    //     $reviews = ReviewRating::where('service_id', $service->id)->with('user', 'service')->paginate(7);
    //     $five = ReviewRating::where('service_id', $service?->id)->where('rating','>=', 5)->count();
    //     $four = ReviewRating::where('service_id', $service?->id)->where('rating','>', 4)->where('rating','<', 5)->count();
    //     $three = ReviewRating::where('service_id', $service?->id)->where('rating','>', 3)->where('rating','<', 4)->count();
    //     $two = ReviewRating::where('service_id', $service?->id)->where('rating','>', 2)->where('rating','<', 3)->count();
    //     $one = ReviewRating::where('service_id', $service?->id)->where('rating','>', 1)->where('rating','<', 2)->count();
    //     $all = ReviewRating::where('service_id', $service?->id)->get();
    //     $allcount = ReviewRating::where('service_id', $service?->id)->count();
    //     $communication = ReviewRating::where('service_id', $service?->id)->avg('communicationrating');
    //     $recommend = ReviewRating::where('service_id', $service?->id)->avg('recommendrating');
    //     $described = ReviewRating::where('service_id', $service?->id)->avg('describedrating');
    //     $skills = ReviewRating::where('service_id', $service?->id)->avg('skill');
    //     // other rating
    //     $otherfive = ReviewRating::where('service_id', $service->id)->where('rating','>=', 5)->count();
    //     $otherfour = ReviewRating::where('service_id', $service->id)->where('rating','>', 4)->where('rating','<', 5)->count();
    //     $otherthree = ReviewRating::where('service_id', $service->id)->where('rating','>', 3)->where('rating','<', 4)->count();
    //     $othertwo = ReviewRating::where('service_id', $service->id)->where('rating','>', 2)->where('rating','<', 3)->count();
    //     $otherone = ReviewRating::where('service_id', $service->id)->where('rating','>', 1)->where('rating','<', 2)->count();
    //     $otherall = ReviewRating::where('service_id', $service->id)->get();
    //     $otherallcount = ReviewRating::where('service_id', $service->id)->count();
    //     $othercommunication = ReviewRating::where('service_id', $service->id)->avg('communicationrating');
    //     $otherrecommend = ReviewRating::where('service_id', $service->id)->avg('recommendrating');
    //     $otherdescribed = ReviewRating::where('service_id', $service->id)->avg('describedrating');
    //     $otherskill = ReviewRating::where('service_id', $service->id)->avg('skill');

    //     // job rating
    //     // $jobfive = ReviewRating::where('service_id', $service->id)->where('rating', 5)->count();
    //     // $jobfour = ReviewRating::where('service_id', $service->id)->where('rating', 4)->count();
    //     // $jobthree = ReviewRating::where('service_id', $service->id)->where('rating', 3)->count();
    //     // $jobtwo = ReviewRating::where('service_id', $service->id)->where('rating', 2)->count();
    //     // $jobone = ReviewRating::where('service_id', $service->id)->where('rating', 1)->count();
    //     // $joball = ReviewRating::where('service_id', $service->id)->get();
    //     // $joballcount = ReviewRating::where('service_id', $service->id)->count();
    //     // $jobcommunication = ReviewRating::where('service_id', $service->id)->avg('communicationrating');
    //     // $jobrecommend = ReviewRating::where('service_id', $service->id)->avg('recommendrating');
    //     // $jobdescribed = ReviewRating::where('service_id', $service->id)->avg('describedrating');
    //     // $jobskill = ReviewRating::where('service_id', $service->id)->avg('skill');

    //     // $otherfive = ReviewRating::where('provider_id', $service->user_id)->where('rating', 5)->count();
    //     // $otherfour = ReviewRating::where('provider_id', $service->user_id)->where('rating', 4)->count();
    //     // $otherthree = ReviewRating::where('provider_id', $service->user_id)->where('rating', 3)->count();
    //     // $othertwo = ReviewRating::where('provider_id', $service->user_id)->where('rating', 2)->count();
    //     // $otherone = ReviewRating::where('provider_id', $service->user_id)->where('rating', 1)->count();
    //     // $otherall = ReviewRating::where('provider_id', $service->user_id)->get();
    //     // $otherallcount = ReviewRating::where('provider_id', $service->user_id)->count();
    //     // $othercommunication = ReviewRating::where('provider_id', $service->user_id)->avg('communicationrating');
    //     // $otherrecommend = ReviewRating::where('provider_id', $service->user_id)->avg('recommendrating');
    //     // $otherdescribed = ReviewRating::where('provider_id', $service->user_id)->avg('describedrating');
    //     // $otherskill = ReviewRating::where('provider_id', $service->user_id)->avg('skill');

    //     // // job rating
    //     // $jobfive = ReviewRating::where('pro_id', $service->user_id)->where('rating', 5)->count();
    //     // $jobfour = ReviewRating::where('pro_id', $service->user_id)->where('rating', 4)->count();
    //     // $jobthree = ReviewRating::where('pro_id', $service->user_id)->where('rating', 3)->count();
    //     // $jobtwo = ReviewRating::where('pro_id', $service->user_id)->where('rating', 2)->count();
    //     // $jobone = ReviewRating::where('pro_id', $service->user_id)->where('rating', 1)->count();
    //     $joball = ReviewRating::where('pro_id', $service->user_id)->get();
    //     $joballcount = ReviewRating::where('pro_id', $service->user_id)->count();
    //     // $jobcommunication = ReviewRating::where('pro_id', $service->user_id)->avg('communicationrating');
    //     // $jobrecommend = ReviewRating::where('pro_id', $service->user_id)->avg('recommendrating');
    //     // $jobdescribed = ReviewRating::where('pro_id', $service->user_id)->avg('describedrating');
    //     // $jobskill = ReviewRating::where('pro_id', $service->user_id)->avg('skill');
    //     // job rating
    //     $prnt = DB::table('seller_detail_skill_parent')->where('service_id', $service->id)->where('user_id', $service->user_id)->groupBy('parent')->get();
    //     $newextra = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->get();
    //     $newextrac = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->count();

    //     $category = Category::where('id', $service->category_id)->first();
    //     $subcategory = SubCategory::where('id', $service->sub_category_id)->first();
    //     $childcategory = ChildCategories::where('id', $service->childcat_id)->first();

    //     $childcategorycount = ChildCategories::where('id', $service->childcat_id)->count();
    //     $user = Auth::user();

    //     return view($this->activeTemplate . 'service_deatils', compact(
    //         'chats',
    //         'totalReviews',
    //         'sid',
    //         'rid',
    //         'pageTitle',
    //         'service',
    //         'otherServices',
    //         'totalService',
    //         'conversion',
    //         'comments',
    //         'reviews',
    //         'serviceGetRating',
    //         'reviewRataingAvg',
    //         'workInprogress',
    //         'serviceDetails',
    //         'five',
    //         'four',
    //         'three',
    //         'two',
    //         'one',
    //         'all',
    //         'communication',
    //         'recommend',
    //         'described',
    //         'allcount',
    //         'otherfive',
    //         'otherfour',
    //         'otherthree',
    //         'othertwo',
    //         'otherone',
    //         'otherall',
    //         'othercommunication',
    //         'otherrecommend',
    //         'otherdescribed',
    //         'otherallcount',
    //         'otherskill',
    //         'joball',
    //         'prnt',
    //         'newextra',
    //         'newextrac',
    //         'category',
    //         'subcategory',
    //         'childcategory',
    //         'childcategorycount',
    //         'availableFeatures',
    //         'userFeatures',
    //         'user',
    //         'releatedServices',
    //         'skills'
    //     ));
    // }

    public function serviceDetails($id)
    {
        $us = Auth::user();
        $sid = 0;
        $chats = [];
        $service = Service::with('skills','extraSkills')->where('id', decrypt($id))->first();
        if (!$service) {
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

        $service = $serviceQuery->with('skills','extraSkills')->where('id', decrypt($id))->firstOrFail();

        $otherServices = Service::where('id', '!=', $service->id)
            ->where('status', 1)
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })
            ->where('user_id', $service->user_id)
            ->with('user', 'user.rank', 'latestDetail')
            ->limit(4)
            ->orderBy('id', 'DESC')
            ->get();

        $titleKeywords = preg_split('/\s+/', $service->title);

        $releatedServices = Service::where('id', '!=', $service->id)
            ->where('status', 1)
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })
            ->where(function ($q) use ($titleKeywords) {
                foreach ($titleKeywords as $keyword) {
                    $q->orWhere('title', 'like', "%{$keyword}%");
                }
            })
            ->with('user', 'user.rank','skills','extraSkills')
            ->orderBy('id', 'DESC')
            ->get();

        $totalService = Service::where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->where('user_id', $service->user_id)->with('user','skills','extraSkills')->count();

        $activeUser = Auth::user();
        $conversion = null;
        $serviceGetRating = null;

        $joballcount = ReviewRating::where('service_id', $service->id)->count();


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

        $serviceDetails = ServicesDetail::where('service_id', $service->id)->where('is_active',1)->get();

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
            ->groupBy('service_detail_id')
            ->mapWithKeys(function ($item, $key) {
                return [$key => $item];
            });

        $userFeatures = collect($userFeatures);
        foreach ($serviceDetails as $detail) {
            if (!isset($userFeatures[$detail->id])) {
                $userFeatures[$detail->id] = collect();
            }
        }

        $totalReviews = ReviewRating::where('service_id', $service->id)->count();

        $comments = Comment::where('service_id', $service->id)->with('user', 'commentReply')->paginate(7);
        $reviews = ReviewRating::where('service_id', $service->id)->with('user', 'service')->paginate(7);
        
        // Calculate ratings for current service (bucketed by nearest whole star)
        $five = ReviewRating::where('service_id', $service->id)->whereRaw('ROUND(rating) = 5')->count();
        $four = ReviewRating::where('service_id', $service->id)->whereRaw('ROUND(rating) = 4')->count();
        $three = ReviewRating::where('service_id', $service->id)->whereRaw('ROUND(rating) = 3')->count();
        $two = ReviewRating::where('service_id', $service->id)->whereRaw('ROUND(rating) = 2')->count();
        $one = ReviewRating::where('service_id', $service->id)->whereRaw('ROUND(rating) <= 1')->count();
        $all = ReviewRating::where('service_id', $service->id)->with('user')->get();
        $allcount = ReviewRating::where('service_id', $service->id)->count();
        $communication = ReviewRating::where('service_id', $service->id)->avg('communicationrating');
        $recommend = ReviewRating::where('service_id', $service->id)->avg('recommendrating');
        $described = ReviewRating::where('service_id', $service->id)->avg('describedrating');
        $skills = ReviewRating::where('service_id', $service->id)->avg('skill');
        
        // Calculate ratings for all other seller services (excludes the current service)
        $otherServiceReviews = ReviewRating::where('pro_id', $service->user_id)
            ->where('type', 'service')
            ->where('service_id', '!=', $service->id);

        $otherfive = (clone $otherServiceReviews)->whereRaw('ROUND(rating) = 5')->count();
        $otherfour = (clone $otherServiceReviews)->whereRaw('ROUND(rating) = 4')->count();
        $otherthree = (clone $otherServiceReviews)->whereRaw('ROUND(rating) = 3')->count();
        $othertwo = (clone $otherServiceReviews)->whereRaw('ROUND(rating) = 2')->count();
        $otherone = (clone $otherServiceReviews)->whereRaw('ROUND(rating) <= 1')->count();
        $otherall = (clone $otherServiceReviews)->with('user')->get();
        $otherallcount = (clone $otherServiceReviews)->count();
        $othercommunication = (clone $otherServiceReviews)->avg('communicationrating');
        $otherrecommend = (clone $otherServiceReviews)->avg('recommendrating');
        $otherdescribed = (clone $otherServiceReviews)->avg('describedrating');
        $otherskill = (clone $otherServiceReviews)->avg('skill');

        // Calculate ratings for all jobs
        $joball = ReviewRating::where('pro_id', $service->user_id)->whereIn('type', ['bid', 'offer'])->with('user')->get();
        
        $prnt = DB::table('seller_detail_skill_parent')->where('service_id', $service->id)->where('user_id', $service->user_id)->groupBy('parent')->get();
        $newextra = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->get();
        $newextrac = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->count();

        $category = Category::where('id', $service->category_id)->first();
        $subcategory = SubCategory::where('id', $service->sub_category_id)->first();
        $childcategory = ChildCategories::where('id', $service->childcat_id)->first();
        $childcategorycount = ChildCategories::where('id', $service->childcat_id)->count();
        
        $user = Auth::user();
        $s_id = decrypt($id);

        return view($this->activeTemplate . 'service_deatils', compact(
            'chats', 'totalReviews', 'sid', 'rid', 'pageTitle', 'service', 'otherServices',
            'totalService', 'conversion', 'comments', 'reviews', 'serviceGetRating', 'reviewRataingAvg',
            'workInprogress', 'serviceDetails', 'five', 'four', 'three', 'two', 'one', 'all',
            'communication', 'recommend', 'described', 'allcount', 'otherfive', 'otherfour',
            'otherthree', 'othertwo', 'otherone', 'otherall', 'othercommunication', 'otherrecommend',
            'otherdescribed', 'otherallcount', 'otherskill', 'joball', 'prnt', 'newextra', 'newextrac',
            'category', 'subcategory', 'childcategory', 'childcategorycount', 'availableFeatures',
            'userFeatures', 'user', 'releatedServices', 'skills','joballcount',
            's_id'
        ));
    }
}
