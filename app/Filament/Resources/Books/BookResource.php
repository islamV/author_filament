<?php

namespace App\Filament\Resources\Books;

use BackedEnum;
use App\Models\Book;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Books\Pages\EditBook;
use App\Filament\Resources\Books\Pages\ViewBook;
use App\Filament\Resources\Books\Pages\ListBooks;
use App\Filament\Resources\Books\Pages\CreateBook;
use App\Filament\Resources\Books\Schemas\BookForm;
use App\Filament\Resources\Books\Tables\BooksTable;
use App\Filament\Resources\Books\Schemas\BookInfolist;
use App\Filament\Resources\Books\RelationManagers\CommentsRelationManager;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'title';


    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.books');
    }

    public static function getModelLabel(): string
    {
        return __('filament.books.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.books.plural_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'المقالات';
    }


    public static function getNavigationBadge(): ?string
    {
        return Book::count();
    }

    public static function form(Schema $schema): Schema
    {
        return BookForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BookInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BooksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
          CommentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBooks::route('/'),
            'create' => CreateBook::route('/create'),
            'view' => ViewBook::route('/{record}'),
            'edit' => EditBook::route('/{record}/edit'),
        ];
    }


    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make()
                ->label(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index'))
                ->icon(static::$navigationIcon)
                ->badge(Book::count())
                ->sort(-1), 

            NavigationItem::make()
                ->label('معلق')
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index', ['status' => 'pending']))
                ->icon('heroicon-o-clock')
                ->badge(Book::where('status', 'pending')->count())
                ->sort(0),

            NavigationItem::make()
                ->label('مقبول')
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index', ['status' => 'accepted']))
                ->icon('heroicon-o-check-circle')
                ->badge(Book::where('status', 'accepted')->count())
                ->sort(1),

            NavigationItem::make()
                ->label('مرفوض')
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index', ['status' => 'refused']))
                ->icon('heroicon-o-x-circle')
                ->badge(Book::where('status', 'refused')->count())
                ->sort(2),
        ];
    }




    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        return $query;
    }




}
