<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->app['auth']->viaRequest('session', function ($request) {		
			$username = $request->header('UserName') ? $request->header('UserName') : '';
			$token = $request->header('Token') ? $request->header('Token') : '';
			$user = User::where([['UserName', $username], ['Token', $token]])->first();
			
			if (!$user) { 
				header('Content-Type: application/json; charset=UTF-8');
				header('Status: 501');
				echo json_encode(['status' => '501', 'message' => 'Unauthorized']);				
				exit;
			}
			
			return $user;
		});
    }
}
