<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderTag;
use App\Models\Store;
use App\Models\StoreSetting;
use App\Policies\OrderPolicy;
use App\Policies\OrderTagsPolicy;
use App\Policies\StorePolicy;
use App\Policies\StoreSettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        OrderTag::class => OrderTagsPolicy::class,
        Store::class => StorePolicy::class,
        StoreSetting::class => StoreSettingPolicy::class,
        Order::class => OrderPolicy::class
    ];

    /**
     * Register any authentication / authorization service.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
