<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistrationCountry;

class RegistrationCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newCountries = [
            ['name'=>'أمريكا','code'=>'US'],
            ['name'=>'كندا','code'=>'CA'],
            ['name'=>'بريطانيا','code'=>'GB'],
            ['name'=>'ألمانيا','code'=>'DE'],
            ['name'=>'فرنسا','code'=>'FR'],
            ['name'=>'إيطاليا','code'=>'IT'],
            ['name'=>'أستراليا','code'=>'AU'],
            ['name'=>'الهند','code'=>'IN'],
            ['name'=>'باكستان','code'=>'PK'],
            ['name'=>'الصين','code'=>'CN'],
            ['name'=>'اليابان','code'=>'JP'],
            ['name'=>'كوريا الجنوبية','code'=>'KR'],
            ['name'=>'البرازيل','code'=>'BR'],
            ['name'=>'الأرجنتين','code'=>'AR'],
            ['name'=>'المكسيك','code'=>'MX'],
            ['name'=>'روسيا','code'=>'RU'],
            ['name'=>'تركيا','code'=>'TR'],
            ['name'=>'نيوزيلندا','code'=>'NZ'],
            ['name'=>'جنوب أفريقيا','code'=>'ZA'],
            ['name'=>'إندونيسيا','code'=>'ID'],
        ];

        foreach ($newCountries as $country) {
            // نضيف الدولة فقط إذا لم تكن موجودة مسبقًا
            RegistrationCountry::updateOrCreate(
                ['code' => $country['code']],
                ['name' => $country['name']]
            );
        }
    }
}
