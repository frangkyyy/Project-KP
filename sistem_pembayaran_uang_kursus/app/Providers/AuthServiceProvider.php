<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        if (! app()->runningInConsole()) {
            // Inisialisasi permissionArray sebagai array kosong
            $permissionArray = [];

            // Ambil semua roles beserta permissions-nya
            $roles = Role::with('permissions')->get();

            // Buat permissionArray dengan daftar roles yang memiliki setiap permission
            foreach ($roles as $role) {
                foreach ($role->permissions as $permission) {
                    // Isi permissionArray dengan permission title dan role id
                    $permissionArray[$permission->title][] = $role->id;
                }
            }

            // Definisikan setiap permission di Gate
            foreach ($permissionArray as $title => $roles) {
                Gate::define($title, function (User $user) use ($roles) {
                    // Periksa apakah user memiliki salah satu role yang terkait dengan permission ini
                    return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                });
            }
        }
    }
}
