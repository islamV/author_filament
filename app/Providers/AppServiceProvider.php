<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\User;
use App\Observers\BookObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use App\Repositories\v1\Interface\Dashboard\Ad\IAd;
use App\Repositories\v1\Interface\Author\Auth\IUser;
use App\Repositories\v1\Interface\Author\Book\IBook;
use App\Repositories\v1\Interface\Author\Book\IBookPage;
use App\Repositories\v1\Interface\Author\Payment\IPayment;
use App\Repositories\v1\Interface\Author\Category\ICategory;
use App\Repositories\v1\Interface\Author\book_parts\Ibook_parts;
use App\Repositories\v1\Interface\Author\Payment\IManualPayment;
use App\Repositories\v1\Implementation\Dashboard\Ad\AdRepository;
use App\Repositories\v1\Interface\Author\Payment\IPaymentGateway;
use App\Repositories\v1\Implementation\Author\Auth\UserRepository;
use App\Repositories\v1\Implementation\Author\Book\BookRepository;
use App\Repositories\v1\Interface\Author\Notification\INotification;
use App\Repositories\v1\Implementation\Author\Book\BookPageRepository;
use App\Repositories\v1\Implementation\Author\Payment\PaymentRepository;
use App\Repositories\v1\Implementation\Author\Category\CategoryRepository;
use App\Repositories\v1\Implementation\Author\book_parts\book_partsRepository;
use App\Repositories\v1\Implementation\Author\Payment\ManualPaymentRepository;
use App\Repositories\v1\Implementation\Author\Payment\PaymentGatewayRepository;
use App\Repositories\v1\Implementation\Author\Notification\NotificationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUser::class,UserRepository::class);
        $this->app->bind(ICategory::class,CategoryRepository::class);
        $this->app->bind(IBook::class,BookRepository::class);
        $this->app->bind(Ibook_parts::class,book_partsRepository::class);
        $this->app->bind(IAd::class,AdRepository::class);
        $this->app->bind(IBookPage::class,BookPageRepository::class);
        $this->app->bind(INotification::class,NotificationRepository::class);
        $this->app->bind(IPaymentGateway::class,PaymentGatewayRepository::class);
        $this->app->bind(IPayment::class,PaymentRepository::class);
        $this->app->bind(IManualPayment::class,ManualPaymentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Book::observe(BookObserver::class);
        User::observe(UserObserver::class);
        
          LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en']);  
        });
        
        
    }
}
