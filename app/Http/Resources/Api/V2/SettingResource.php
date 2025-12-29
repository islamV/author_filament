<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'site_favicon'      => $this->site_favicon ? asset('storage/' . $this->site_favicon) : null,
            'banner_image'      => $this->banner_image ? asset('storage/' . $this->banner_image) : null,

            'site_email'        => $this->site_email,
            'site_phone'        => $this->site_phone,
            'site_address'      => $this->site_address,
            'contact_info'      => $this->contact_info,

            'social' => [
                'facebook'  => $this->facebook,
                'twitter'   => $this->twitter,
                'instagram' => $this->instagram,
                'linkedin'  => $this->linkedin,
                'youtube'   => $this->youtube,
                'tiktok'    => $this->tiktok,
                'snapchat'  => $this->snapchat,
                'pinterest' => $this->pinterest,
            ],

            'messaging_apps' => [
                'whatsapp' => $this->whatsapp,
                'telegram' => $this->telegram,
            ],

            'legal' => [
                'terms_conditions' => $this->terms_conditions,
                'privacy_policy'   => $this->privacy_policy,
                'publish_policy'   => $this->publish_policy, 
            ],

            'about_us' => $this->about_us,
        ];
    }
}
