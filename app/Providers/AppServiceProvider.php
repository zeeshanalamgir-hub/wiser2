<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Category;
use App\Models\Features;
use App\Models\Rank;
use App\Models\Service;
use App\Models\Software;
use App\Models\Job;
use App\Models\Notification;
use App\Models\Withdrawal;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //$this->app['request']->server->set('HTTPS', true);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('viewPulse', function (User $user) {
            return $user;
        });
        $activeTemplate = activeTemplate();
        $general = GeneralSetting::first();
        $viewShare['paginator']=Paginator::useBootstrap();
        $viewShare['general'] = $general;
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language'] = Language::all();
        $viewShare['categorys'] = Category::where('status', 1)->orderby('id', 'DESC')->inRandomOrder()->get();
        $viewShare['ranks'] = Rank::where('status', 1)->get();
        $viewShare['features'] = Features::latest()->get();
        $viewShare['fservices'] = Service::where('status', 1)->where('featured', 1)->whereHas('category', function($q){
            $q->where('status', 1);
        })->paginate(4);
        view()->share($viewShare);

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'banned_users_count'           => User::banned()->count(),
                'email_unverified_users_count' => User::emailUnverified()->count(),
                'sms_unverified_users_count'   => User::smsUnverified()->count(),
                'pending_ticket_count'         => SupportTicket::whereIN('status', [0,2])->count(),
                'pending_deposits_count'    => Deposit::pending()->count(),
                'pending_withdraw_count'    => Withdrawal::pending()->count(),
                'servicePending'    => Service::where('status', 0)->count(),
                'softwarePending'    => Software::where('status', 0)->count(),
                'jobPending'    => Job::where('status', 0)->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'=>Notification::where([
                    ['read_at',null],
                    ['notifiable_type',Admin::class]
                ])
                ->orderBy('created_at','desc')
                ->get(),
            ]);
        });

        view()->composer('templates.basic.partials.new_header', function ($view) {

       
        $categories = Category::with('subCategory')
            ->where('type', 'job')
                ->where('status', 1)
                ->withCount('jobs')
                ->get(['id', 'name', 'status', 'type']);

            $view->with('categories', $categories);
        });

        $service_categories = Category::with('subCategory')->where('type', 'service')->where('status', 1)
            ->get(['id', 'name', 'status', 'type', 'image']);

        view()->composer('templates.basic.partials.wiserlance.seller-new-header', function ($view) use($service_categories) {
            $view->with('service_categories', $service_categories);
        });


        view()->composer('templates.basic.partials.wiserlance.buyer-new-header', function ($view) use($service_categories) {
            $view->with('service_categories', $service_categories);
        });

        view()->composer('templates.basic.partials.wiserlance.footer-dashboard', function ($view) use($service_categories) {
            $view->with('service_categories', $service_categories);
        });


        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if($general->force_ssl){
           \URL::forceScheme('https');
        }



        Paginator::useBootstrap();

    }
}