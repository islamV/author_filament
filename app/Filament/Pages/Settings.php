<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MultiSelect;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class Settings extends Page implements HasSchemas
{
    use InteractsWithActions, InteractsWithForms;

    protected string $view = 'filament.pages.setting';

    public ?array $data = [];
    public Setting $record;

    public function getHeading(): string
    {
        return 'إعدادات الموقع';
    }

    public static function getNavigationGroup(): string
    {
        return 'إعدادات الموقع';
    }

    public static function getNavigationLabel(): string
    {
        return 'إعدادات الموقع';
    }

    public function mount(): void
    {
        $this->record = Setting::firstOrCreate([]);
        $this->fillForms();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make('عام')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('معلومات الموقع')
                                    ->schema([
                                   
                                        FileUpload::make('site_favicon')
                                            ->label('أيقونة الموقع')
                                            ->image()
                                            ->directory('settings')
                                            ->disk('public') 
                                            ->imagePreviewHeight('50')
                                            ->maxSize(1024),

                                    ])->columns(2),
                                Section::make('معلومات التواصل')
                                    ->schema([
                                        TextInput::make('site_email')->label('البريد الإلكتروني')->email()->maxLength(255),
                                        TextInput::make('site_phone')->label('رقم الهاتف')->tel()->maxLength(255),
                                        Textarea::make('site_address')->label('عنوان الموقع')->rows(2)->maxLength(500)->columnSpanFull(),
                                        Textarea::make('contact_info')->label('معلومات التواصل')->rows(4)->columnSpanFull(),
                                    ])->columns(2),
                            ]),

                        Tab::make('وسائل التواصل الاجتماعي')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('شبكات التواصل')
                                    ->schema([
                                        TextInput::make('facebook')->label('فيسبوك')->url()->maxLength(255),
                                        TextInput::make('twitter')->label('تويتر')->url()->maxLength(255),
                                        TextInput::make('instagram')->label('انستجرام')->url()->maxLength(255),
                                        TextInput::make('linkedin')->label('لينكدإن')->url()->maxLength(255),
                                        TextInput::make('youtube')->label('يوتيوب')->url()->maxLength(255),
                                        TextInput::make('tiktok')->label('تيك توك')->url()->maxLength(255),
                                        TextInput::make('snapchat')->label('سناب شات')->url()->maxLength(255),
                                        TextInput::make('pinterest')->label('بنترست')->url()->maxLength(255),
                                    ])->columns(2),
                                Section::make('تطبيقات الرسائل')
                                    ->schema([
                                        TextInput::make('whatsapp')->label('واتساب')->tel()->maxLength(255),
                                        TextInput::make('telegram')->label('تليجرام')->maxLength(255),
                                    ])->columns(2),
                            ]),

                        Tab::make('صفحات قانونية')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('السياسات')
                                    ->schema([
                                        RichEditor::make('terms_conditions')->label('الشروط والأحكام')->columnSpanFull(),
                                        RichEditor::make('privacy_policy')->label('سياسة الخصوصية')->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('من نحن')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('عن الموقع')
                                    ->schema([
                                        RichEditor::make('about_us')->label('من نحن')->columnSpanFull(),
                                    ]),
                            ]),

                            Tab::make('سياسة النشر والاستخدام')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                  
                                    RichEditor::make('publish_policy')
                                        ->label('سياسة النشر والاستخدام')
                                        ->columnSpanFull(),
                                        
                                ]),


                      Tab::make('نشر المقالات')
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            Section::make('اختيار الدور أو المستخدم')
                                ->schema([

                                    TextInput::make('book_content_word_limit')
                                        ->label('الحد الأقصى للكلمات لكل صفحة')
                                        ->numeric()
                                        ->minValue(1)
                                        ->default(1000)
                                        ->helperText('عدد الكلمات المسموح بها لكل صفحة عند تقسيم محتوى الكتاب.'),
                                        
                                    MultiSelect::make('roles')
                                        ->label('الدور المسموح بالنشر')
                                        ->relationship('roles', 'name', fn($query) => $query->where('guard_name', 'web'))
                                        ->placeholder('اختر دورًا')
                                        ->preload(),

                                    MultiSelect::make('users')
                                        ->label('المستخدم المسموح بالنشر')
                                        ->relationship('users', 'first_name')
                                        ->placeholder('اختر مستخدمًا')
                                        ->preload(),

                                    Toggle::make('new_writers_auto_active')
                                        ->label('تفعيل الكتّاب الجدد مباشرة عند التسجيل')
                                        ->helperText('عند تفعيل هذا الخيار، أي كاتب جديد عند التسجيل يصبح نشط مباشرة.'),    
                                ])
                                ->columns(2),
                                ]),

                        Tab::make('البنرات')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('إدارة صور البانر')
                                    ->schema([
                                       Repeater::make('banners')
                                        ->relationship()
                                       // ->reorderable('order')
                                       // ->orderColumn('order') 
                                        ->schema([
                                            FileUpload::make('image')
                                                ->label('صورة البانر')
                                                ->image()
                                                ->required()
                                                ->directory('banners')
                                                ->disk('public'),   
                                        ])
                                        ->collapsible()
                                        ->addActionLabel('إضافة بانر')
                                        ->itemLabel(fn (array $state): ?string => 'بانر'),
                                    ]),
                            ]),


                        Tab::make('إعدادات المشاهدات')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Section::make('حساب المشاهدات والأرباح')
                                    ->schema([
                                        TextInput::make('min_pages_to_count')
                                            ->label('الحد الأدنى للصفحات المحسوبة')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->default(3)
                                            ->helperText('عدد الصفحات التي يجب قراءتها لحساب المشاهدة')
                                            ->columnSpanFull(),
                                    ]),
                            ]),


                            Tab::make('دعوات المستخدمين')
                                ->icon('heroicon-o-gift')
                                ->schema([
                                    Section::make('إعدادات النقاط والمكافآت')
                                        ->schema([
                                           TextInput::make('invitation_money_uses')
                                                ->label('عدد المستخدمين المطلوب استخدامهم للكود للحصول على المكافأة المالية')
                                                ->helperText('مثال: إذا أدخلت 3، يجب أن يستخدم 3 أشخاص الكود ليحصل المؤلف على المكافأة.')
                                                ->numeric()
                                                ->minValue(1)
                                                ->nullable(),

                                            TextInput::make('invitation_reward_money')
                                                ->label('المكافأة المالية للمؤلف عند تحقيق الحد المطلوب من استخدام الكود')
                                                ->helperText('المبلغ الذي يحصل عليه المؤلف عندما يستخدم العدد المحدد من المستخدمين الكود.')
                                                ->numeric()
                                                ->minValue(0)
                                                ->step(0.01)
                                                ->nullable(),

                                            TextInput::make('invitation_reward_points')
                                                ->label('عدد النقاط التي يحصل عليها المستخدم لكل استخدام لكود الدعوة')
                                                ->numeric()
                                                ->minValue(1)
                                                ->nullable(),    

                                        ])
                                        ->columns(2),
                                ]),

                                

                    ])->columnSpanFull(),
            ])
            ->model($this->record)
            ->statePath('data');
    }

    protected function fillForms(): void
    {
        $data = $this->record->attributesToArray();
        $this->form->fill($data);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('admin.save_settings'))
                ->submit('submit'),
        ];
    }

    public function submit(): void
    {
        try {
            $data = $this->form->getState();
            $this->record->update($data);
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('admin.updated_successfully'))
            ->send();
    }
}
