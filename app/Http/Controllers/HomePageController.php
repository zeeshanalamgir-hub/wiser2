<?php

namespace App\Http\Controllers;

use App\Models\Employermembership;
use App\Models\Freelancermembership;
use App\Models\HelpCenter;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function aboutUs(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.about-us');
    }

    public function whyChooseUs(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.why-choose-us');
    }

    public function howOurPlatformWorks(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.how-platform-works');
    }

    public function tipsForSuccessSeller(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.tips-success-seller');
    }

    public function tipsForSuccessBuyer(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.tips-success-buyer');
    }

    public function helpSupportCenter(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        $buyers = HelpCenter::where('type','buyer')->get();
        $sellers = HelpCenter::where('type','seller')->get();
        return view('homepages.help-support-center',compact('buyers','sellers'));
    }

    public function privacy(Request $request)
    {
        $policy =  HelpCenter::with('details')
        ->whereType('policy')
        ->firstOrFail();
        return view('homepages.privacy',compact('policy'));
    }

    public function terms(Request $request)
    {
        $helpCenter = $this->getByType('terms');

        return view('homepages.term-services',compact('helpCenter'));
    }

    public function contactUs(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.contact-us');
    }

    public function authReg(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.auth-reg');
    }

    public function becomeBuyer(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.become-buyer');
    }

    public function workingProfessional(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.working-professional');
    }

    public function authRegProf(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.auth-reg-prof');
    }

    public function billingPayment(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.biling-payment');
    }

    public function policies(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.policies');
    }

    public function profileSetting(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }
        return view('homepages.profile-setting');
    }

    public function memberships(Request $request)
    {
        if ($request->type == 'jobs') {
            return searchJob($request);
        } else if ($request->type == 'professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'services') {
            return searchService($request);
        }

        $pageTitle = 'Membership Pricing Options';

        $freelancerMemberships = Freelancermembership::orderBy('price')
            ->get();

        $freelancerMonthly = $freelancerMemberships->where('freelancer_payment_plan_id', 1);
        $freelancerYearly = $freelancerMemberships->where('freelancer_payment_plan_id', 2);

        foreach ($freelancerMonthly as $membership) {
            $membership->yearly_price = $membership->price * 12 * 0.8; // 20% off
            $membership->yearly_saving = ($membership->price * 12) - $membership->yearly_price;

            $membership->features = [
                'service_fee' => $membership->service_cost . '%',
                'job_cost' => $membership->fee . '%',
                'bids' => $membership->bids ?? '0',
                'featured_bids' => $membership->featuredjobquotes ? 'Yes' : 'No',
                'annual_bids_rollover' => $membership->annual_bids_rollover ?? '0',
                'purchase_additional_bids' => $membership->purchase_additional_bids ? 'Yes' : 'No',
                'normal_services' => $membership->normal_services ?? '0',
                'featured_services' => $membership->featured_services ?? '0',
                'annual_services_rollover' => $membership->annual_services_rollover ?? '0',
                'purchase_service' => $membership->purchase_service ? 'Yes' : 'No',
                'portfolio' => $membership->portfolio_size ?? '0',
                'verified' => $membership->verifiedaccount ? 'Yes' : 'No',
                'support' => $membership->support ? 'Priority' : 'Basic',
                'safe_payment' => $membership->safe_payment ? 'Yes' : 'No',
            ];
        }

        foreach ($freelancerYearly as $membership) {
            $membership->features = [
                'service_fee' => $membership->service_cost . '%',
                'job_cost' => $membership->fee . '%',
                'bids' => $membership->bids ?? '0',
                'featured_bids' => $membership->featuredjobquotes ? 'Yes' : 'No',
                'annual_bids_rollover' => $membership->annual_bids_rollover ?? '0',
                'purchase_additional_bids' => $membership->purchase_additional_bids ? 'Yes' : 'No',
                'normal_services' => $membership->normal_services ?? '0',
                'featured_services' => $membership->featured_services ?? '0',
                'annual_services_rollover' => $membership->annual_services_rollover ?? '0',
                'purchase_service' => $membership->purchase_service ? 'Yes' : 'No',
                'portfolio' => $membership->portfolio_size ?? '0',
                'verified' => $membership->verifiedaccount ? 'Yes' : 'No',
                'support' => $membership->support ? 'Priority' : 'Basic',
                'safe_payment' => $membership->safe_payment ? 'Yes' : 'No',
            ];
        }

        $employerMemberships = Employermembership::orderBy('price')
            ->get();

        foreach ($employerMemberships as $membership) {
            $membership->features = [
                'service_fee' => $membership->service_cost ?? '5%',
                'regular_jobs' => $membership->regular_jobs ?? '5',
                'featured_jobs' => $membership->featured_jobs ?? '1',
                'job_invites' => $membership->job_invites ?? '30',
                'featured_tags' => isset($membership->featured_tags) && $membership->featured_tags ? 'Yes' : 'No',
                'assistance' => 'Yes',
                'safe_payment' => 'Yes',
                'support' => $membership->priority_support ? 'Priority' : 'Basic',
                'verification' => 'Yes',
            ];
        }

        return view('homepages.memberships', compact(
            'pageTitle',
            'freelancerMonthly',
            'freelancerYearly',
            'employerMemberships'
        ));
    }

    public function helpCenterDetails($slug)
    {

        $help = $this->response($slug);
        return view('homepages.help-detail',compact('help'));
    }

    public function response($slug)
    {
        return HelpCenter::with('details')
        ->whereSlug($slug)
        ->firstOrFail();
    }

    public function getByType($type)
    {
        return HelpCenter::with('details')
        ->whereType($type)
        ->get();
    }

}
