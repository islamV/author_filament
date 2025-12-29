<?php

namespace App\Models;

use App\Traits\FollowersTrait;
use App\Traits\HasViewsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, FollowersTrait, HasViewsTrait,Billable,HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'status',
        'is_active',
        'refuse_reason',
        'image',
        'phone',
        'address',
        'country_id',
        'role_id',
        'email',
        'password',
        'description',
        'work_link',
        'device_token',
        'invitation_code',
        'invitation_points',
        'package_points',
        'is_trial_user', 
        'trial_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'trial_expires_at' => 'datetime',
            'is_trial_user' => 'boolean',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    /**
     * Get the user's full name.
     * This is required by Filament for getUserName().
     */
    public function getNameAttribute(): string
    {
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';
        
        $fullName = trim($firstName . ' ' . $lastName);
        
        // Return email if name is empty to prevent null return
        return $fullName !== '' ? $fullName : ($this->email ?? 'User');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }


    /**
     * Invitation codes where this user is the owner (user_id in invitation_codes table)
     */
    public function ownedInvitationCodes()
    {
        return $this->hasMany(InvitationCode::class, 'user_id');
    }

    /**
     * Invitation codes that this user has used (used_by field in invitation_codes table)
     */
    public function usedInvitationCodes()
    {
        return $this->hasMany(InvitationCode::class, 'used_by');
    }
    public function hasFavorited($model)
    {
        return Favourite::where("user_id", Auth::user()->id)
            ->where('favoritable_type', get_class($model))
            ->where('favoritable_id', $model->id)
            ->exists();
    }

    public function favourite($model)
    {
        Favourite::create([
            'user_id' => $this->id,
            'favoritable_id' => $model->id,
            'favoritable_type' => get_class($model),
        ]);
    }

    public function unfavourite($model)
    {
        Favourite::where('user_id', $this->id)
            ->where('favoritable_type', get_class($model))
            ->where('favoritable_id', $model->id)
            ->delete();
    }

    public function favorites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(UserNotification::class, 'user_notification_user', 'user_id', 'notification_id')
            ->withPivot('is_read')
            ->withTimestamps();
    }

    public function totalBooksViewsCount()
    {
        return $this->books->sum(function ($book) {
            return $book->viewsCount();
        });
    }

    public function getAuthorAverageRatingAttribute()
    {
        $books = $this->books;

        if ($books->isEmpty()) {
            return 0;
        }


        $totalRating = $books->sum(function ($book) {
            return $book->getAverageRatingAttribute();
        });

        $totalBooks = $books->count();

        return round($totalRating / $totalBooks, 1);
    }

    public function calculateEarnings($startDate = null, $endDate = null): float
    {
        $earnings = 0;

        foreach ($this->books as $book) {
            $query = $book->views()->selectRaw('country, COUNT(*) as total_views')->groupBy('country');

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $viewsByCountry = $query->get();
            foreach ($viewsByCountry as $viewData) {
                $countryName = $viewData->country;
                $totalViews = $viewData->total_views;

                $country = \App\Models\Country::where('name', $countryName)->first();
                if (!$country || $country->view_count == 0) continue;

                $earnings += ($totalViews / $country->view_count) * $country->price;
            }
        }

        return round($earnings, 3);
    }

    public function calculateDailyEarnings($startDate, $endDate): array
    {
        $dailyEarnings = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            $earnings = 0;

            foreach ($this->books as $book) {
                $query = $book->views()->selectRaw('country, COUNT(*) as total_views')
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->groupBy('country');

                $viewsByCountry = $query->get();
                foreach ($viewsByCountry as $viewData) {
                    $countryName = $viewData->country;
                    $totalViews = $viewData->total_views;

                    $country = \App\Models\Country::where('name', $countryName)->first();
                    if (!$country || $country->view_count == 0) continue;

                    $earnings += ($totalViews / $country->view_count) * $country->price;
                }
            }

            $dailyEarnings[$date->toDateString()] = round($earnings, 3);
        }

        return $dailyEarnings;
    }


    /**
     * Generate a unique random invitation code
     * 
     * @return string 8-character alphanumeric code (uppercase)
     */
    public function generateInvitationCode(): string
    {
        $maxAttempts = 100;  
        $attempts = 0;
        
        do {
             $code = strtoupper(Str::random(8));
            $attempts++;
            
             if ($attempts >= $maxAttempts) {
                // Fallback: use timestamp + random if too many attempts
                $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
                break;
            }
        } while (User::where('invitation_code', $code)->exists());

        return $code;
    }

    /**
     * Create invitation code for this user and save it in users table
     */
    public function createInvitationCode(): void
    {

        $this->invitation_code = $this->generateInvitationCode();
        $this->invitation_points = 0; 
        $this->save();
    }

   public static function booted()
{
    /*
    static::created(function ($user) {

        // Make a private chat with the admin (so user can talk to admin)
        \App\Models\ChatRoom::create([
            'sender_id' => $user->id, // this new user
            'receiver_id' => 1,       // admin
            'chat_type_id' => 1,      // type 1 = private chat
        ]);
        
        // If the user is an author or editor, give them a public chat room
        if ($user->role_id == 2 || $user->role_id == 3) {
            \App\Models\ChatRoom::create([
                'sender_id' => $user->id, // this new user again
                'chat_type_id' => 3,      // type 3 = public chat
            ]);
        }
    */
        // Only run this line now
       // $user->createInvitationCode();
    // });
}


    public function registrationCountry()
    {
        return $this->belongsTo(RegistrationCountry::class, 'country_id');
    }


    public function comments()
    {
        return $this->hasMany(Comment::class); 
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }


    public function authorRequestPayments()
    {
        return $this->hasMany(AuthorRequestPayment::class, 'user_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }



  


}
