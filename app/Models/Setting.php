<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        // Basic site info
        'site_name',
        'site_logo',
        'site_favicon',
        'logo_image',
        'site_description',
        'site_email',
        'site_phone',
        'site_address',

        // Social media
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'tiktok',
        'snapchat',
        'pinterest',
        'whatsapp',
        'telegram',

        // Policies & content
        'terms_conditions',
        'privacy_policy',
        'refund_policy',
        'about_us',
        'contact_info',
        'publish_policy',

        'direct_post_user_id',
        'direct_post_role_id',

        'min_pages_to_count',

        'invitation_reward_points',
        'invitation_money_uses',
        'invitation_reward_money',

        'book_content_word_limit', 
        'new_writers_auto_active'

       
    ];

    protected $casts = [
        'new_writers_auto_active' => 'boolean', 
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'setting_role');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'setting_user');
    }

    public function banners()
    {
        return $this->hasMany(SettingBanner::class);
    }

   
}
