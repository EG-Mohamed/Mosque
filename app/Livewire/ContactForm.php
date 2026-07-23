<?php

namespace App\Livewire;

use App\Mail\ContactSubmissionReceivedMail;
use App\Models\ContactSubmission;
use App\Models\User;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class ContactForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Full Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('Email Address'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),
                PhoneInput::make('phone')
                    ->label(__('Phone Number')),
                TextInput::make('subject')
                    ->label(__('Subject'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('message')
                    ->label(__('Message'))
                    ->required()
                    ->minLength(10)
                    ->rows(5),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = $this->form->getState();

        $submission = ContactSubmission::create($state);

        $recipients = User::query()
            ->where('receive_notification', true)
            ->pluck('email');

        if ($recipients->isNotEmpty()) {
            Mail::to($recipients->all())->send(new ContactSubmissionReceivedMail($submission));
        }

        $this->form->fill();
        $this->dispatch('contact-submitted');
    }

    public function render(): View
    {
        return view('livewire.contact-form');
    }
}
