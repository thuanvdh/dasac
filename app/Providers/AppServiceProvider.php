<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\TheLoai;
use App\Slide;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $theloai = TheLoai::all();
        $slide = Slide::all();
        view()->share('theloai', $theloai); 
        view()->share('slide',$slide);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
