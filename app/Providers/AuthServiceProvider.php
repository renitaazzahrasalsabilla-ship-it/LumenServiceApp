<?php
namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('update-post', function ($user, $post) {
            // Jika admin, boleh update semua post
            if ($user->role == 'admin') {
                return true;
            }

            // Jika editor, hanya boleh update post miliknya
            if ($user->role == 'editor') {
                return $post->user_id == $user->id;
            }

            return false;
        });

        Gate::define('show-post', function ($user, $post) {
            if ($user->role == 'admin') {
                return true;
            }

            if ($user->role == 'editor') {
                return $post->user_id == $user->id;
            }

            return false;
        });

        Gate::define('delete-post', function ($user, $post) {
            if ($user->role == 'admin') {
                return true;
            }

            if ($user->role == 'editor') {
                return $post->user_id == $user->id;
            }

            return false;
        });

        Gate::define('create-post', function ($user) {
            return $user->role == 'admin' || $user->role == 'editor';
        });
    }
}