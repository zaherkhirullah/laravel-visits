<?php

namespace Hayrullah\LaravelVisits;

use Illuminate\Support\ServiceProvider;

/**
 * This file is part of Laravel Favorite,
 *
 * @license MIT
 * @package ChristianKuri/laravel-favorite
 *
 * Copyright (c) 2016 Christian Kuri
 */
class VisitServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 * 
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__.'/../migrations/create_visits_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_visits_table.php'),
        ], 'migrations');	
	}

	/**
	 * Register the application services.
	 * 
	 * @return void
	 */
	public function register()
	{
		# code...
	}
}