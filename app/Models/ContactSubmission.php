<?php

namespace App\Models;

use App\Enums\ContactSubmissionStatus;
use App\Filament\Admin\Resources\ContactSubmissions\ContactSubmissionResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContactSubmission extends Model
{
    protected static function booted(): void
    {
        static::created(function (ContactSubmission $contactSubmission): void {
            if ($contactSubmission->getConnection()->transactionLevel() > 0) {
                DB::afterCommit(fn () => $contactSubmission->sendNewSubmissionNotifications());

                return;
            }

            $contactSubmission->sendNewSubmissionNotifications();
        });
    }

    protected function casts(): array
    {
        return [
            'status' => ContactSubmissionStatus::class,
            'read_at' => 'datetime',
        ];
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', ContactSubmissionStatus::New);
    }

    private function sendNewSubmissionNotifications(): void
    {
        User::query()
            ->lazyById()
            ->each(fn (User $user) => Notification::make()
                ->title(__('New Contact Submission'))
                ->body(__('A new message was submitted by :name.', ['name' => $this->name]))
                ->icon(Heroicon::OutlinedEnvelope)
                ->iconColor('info')
                ->actions([
                    Action::make('view')
                        ->label(__('View'))
                        ->url(ContactSubmissionResource::getUrl('edit', ['record' => $this->getKey()]))
                        ->markAsRead(),
                ])
                ->sendToDatabase($user, isEventDispatched: true));
    }
}
