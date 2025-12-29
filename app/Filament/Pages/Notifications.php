<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Services\FirebaseNotificationService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification as FilamentNotification;

class Notifications extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.notifications';

    public ?array $data = [];

    // ⚠️ احذف هذا السطر تماماً
    // protected FirebaseNotificationService $firebaseService;

    // ⚠️ احذف mount() أو أبسطها
    public function mount(): void
    {
        $this->form->fill();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('notifications.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('notifications.navigation_label');
    }

    public function getTitle(): string
    {
        return __('notifications.navigation_label');
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make(__('notifications.notification_details'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('notifications.title_label'))
                        ->required()
                        ->placeholder(__('notifications.title_placeholder'))
                        ->maxLength(255),

                    Textarea::make('message')
                        ->label(__('notifications.message_label'))
                        ->required()
                        ->rows(6)
                        ->placeholder(__('notifications.message_placeholder')),
                ])
                ->columns(1),

            Section::make(__('notifications.recipients'))
                ->schema([
                    Checkbox::make('sendToAll')
                        ->label(__('notifications.send_to_all_label'))
                        ->reactive()
                        ->helperText(__('notifications.send_to_all_helper')),

                    Checkbox::make('sendToReaders')
                        ->label(__('notifications.send_to_readers_label'))
                        ->reactive()
                        ->helperText(__('notifications.send_to_readers_helper'))
                        ->hidden(fn ($get) => $get('sendToAll')),

                    Checkbox::make('sendToAuthor')
                        ->label(__('notifications.send_to_author_label'))
                        ->reactive()
                        ->helperText(__('notifications.send_to_author_helper'))
                        ->hidden(fn ($get) => $get('sendToAll')),

                    Checkbox::make('sendToDependentAuthor')
                        ->label(__('notifications.send_to_dependent_author_label'))
                        ->reactive()
                        ->helperText(__('notifications.send_to_dependent_author_helper'))
                        ->hidden(fn ($get) => $get('sendToAll')),

                    Select::make('user_ids')
                        ->label(__('notifications.user_label'))
                        ->options(function () {
                            return \App\Models\User::whereNotNull('device_token')
                                ->where('device_token', '!=', '')
                                ->get()
                                ->mapWithKeys(function ($user) {
                                    return [$user->id => $user->name . ' (' . $user->email . ')'];
                                });
                        })
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->hidden(fn ($get) => $get('sendToAll') || 
                                           $get('sendToReaders') || 
                                           $get('sendToAuthor') || 
                                           $get('sendToDependentAuthor'))
                        ->helperText(__('notifications.user_helper')),
                ])
                ->columns(2),
        ];
    }

    public function submit(): void
    {
        try {
            $data = $this->form->getState();

            // التحقق من المدخلات
            if (empty($data['title']) || empty($data['message'])) {
                FilamentNotification::make()
                    ->title(__('notifications.validation_error'))
                    ->body(__('notifications.title_message_required'))
                    ->danger()
                    ->send();
                return;
            }

            $roleIds = [];
            $sendToAll = false;
            $userIds = [];

            // تحديد المستلمين
            if ($data['sendToAll'] ?? false) {
                $sendToAll = true;
            } else {
                // إرسال بناءً على الأدوار المحددة
                if ($data['sendToReaders'] ?? false) {
                    $roleIds[] = 4; // Readers/Users
                }
                if ($data['sendToAuthor'] ?? false) {
                    $roleIds[] = 2; // Verified Author
                }
                if ($data['sendToDependentAuthor'] ?? false) {
                    $roleIds[] = 3; // Dependent Author
                }

                // إذا تم تحديد مستخدمين محددين
                if (empty($roleIds) && !empty($data['user_ids'])) {
                    $userIds = $data['user_ids'];
                }
            }

            // ⚠️ استدعاء الخدمة مباشرة بدون خاصية
            $firebaseService = app(FirebaseNotificationService::class);

            // إرسال الإشعار
            if ($sendToAll) {
                $result = $firebaseService->sendCustomNotification(
                    $data['title'], 
                    $data['message'],
                    [], // مصفوفة فارغة
                    true // sendToAll = true
                );
            } elseif (!empty($roleIds)) {
                $result = $firebaseService->sendNotificationToRoles(
                    $data['title'], 
                    $data['message'],
                    $roleIds
                );
            } elseif (!empty($userIds)) {
                $result = $firebaseService->sendCustomNotification(
                    $data['title'], 
                    $data['message'],
                    $userIds,
                    false // sendToAll = false
                );
            } else {
                FilamentNotification::make()
                    ->title(__('notifications.recipients_error'))
                    ->body(__('notifications.no_recipients_selected'))
                    ->warning()
                    ->send();
                return;
            }

            // عرض النتائج
            if ($result['success']) {
                $message = __('notifications.sent_success_count', [
                    'count' => $result['sent_count'] ?? 0
                ]);
                
                if (isset($result['notification_id'])) {
                    $message .= ' ' . __('notifications.notification_saved_id', [
                        'id' => $result['notification_id']
                    ]);
                }

                FilamentNotification::make()
                    ->title(__('notifications.sent_success'))
                    ->body($message)
                    ->success()
                    ->duration(5000)
                    ->send();
            } else {
                $failedMessage = __('notifications.some_failed_count', [
                    'success' => $result['sent_count'] ?? 0,
                    'failed' => count($result['failed_users'] ?? [])
                ]);

                if (!empty($result['failed_users'])) {
                    $failedDetails = '';
                    foreach ($result['failed_users'] as $failedUser) {
                        if (is_array($failedUser)) {
                            $failedDetails .= "• " . ($failedUser['name'] ?? 'Unknown') . 
                                            " - " . ($failedUser['error'] ?? 'Unknown error') . "\n";
                        } else {
                            $failedDetails .= "• " . $failedUser . "\n";
                        }
                    }
                    
                    $failedMessage .= "\n\n" . __('notifications.failed_details') . ":\n" . $failedDetails;
                }

                FilamentNotification::make()
                    ->title(__('notifications.some_failed_title'))
                    ->body($failedMessage)
                    ->danger()
                    ->duration(8000)
                    ->send();
            }

            $this->form->fill();

        } catch (\Exception $e) {
            FilamentNotification::make()
                ->title(__('notifications.sending_failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();
                
            Log::error('Notification sending error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}