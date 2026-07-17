<?php

namespace App\Filament\Admin\Resources\ContactSubmissions;

use App\Filament\Admin\Resources\ContactSubmissions\Pages\EditContactSubmission;
use App\Filament\Admin\Resources\ContactSubmissions\Pages\ListContactSubmissions;
use App\Filament\Admin\Resources\ContactSubmissions\Schemas\ContactSubmissionForm;
use App\Filament\Admin\Resources\ContactSubmissions\Tables\ContactSubmissionsTable;
use App\Models\ContactSubmission;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    public static function getModelLabel(): string
    {
        return __('Contact Submission');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Contact Submissions');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedEnvelope;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Content');
    }

    public static function getNavigationBadge(): ?string
    {
        $unreadCount = ContactSubmission::query()->unread()->count();

        return $unreadCount > 0 ? (string) $unreadCount : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return ContactSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactSubmissionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactSubmissions::route('/'),
            'edit' => EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
