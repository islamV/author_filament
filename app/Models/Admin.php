<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Default guard for Spatie roles assigned to admins.
     */
    protected string $guard_name = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'phone_number',
        'role_id', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Allow access to the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Display name inside Filament.
     */
    public function getFilamentName(): string
    {
        return $this->name ?? $this->email;
    }

    /**
     * Optional avatar for Filament.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }


    public function reviewedBooks()
    {
        return $this->hasMany(Book::class, 'reviewer_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->role_id) {
                $model->syncRoles([]);
                $roleName = $model->role?->name;
                if ($roleName) {
                    $model->assignRole($roleName);
                    $permissions = \Spatie\Permission\Models\Role::where('name', $roleName)->first()?->permissions ?? collect();
                    $model->syncPermissions([]);
                    if ($permissions->isNotEmpty()) {
                        $model->syncPermissions($permissions);
                    }
                }
            }

            if (is_null($model->password)) {
                unset($model->password);
            }
        });
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}