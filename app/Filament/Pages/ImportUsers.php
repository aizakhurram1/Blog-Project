<?php

namespace App\Filament\Pages;

use App\Jobs\ImportUsersJob;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ImportUsers extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.import-users';

    protected static ?string $navigationIcon = 'heroicon-o-upload';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $title = 'Import Users';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            FileUpload::make('csv_file')
                ->label('CSV File')
                ->acceptedFileTypes(['text/csv', 'text/plain'])
                ->directory('imports')
                ->required(),
        ];
    }

    protected function getFormModel(): string
    {
        return static::class;
    }

    public function submit()
    {
        $path = $this->form->getState()['csv_file'];

        if (! $path) {
            Notification::make()
                ->title('No file uploaded')
                ->danger()
                ->send();

            return;
        }

        ImportUsersJob::dispatch($path);

        Notification::make()
            ->title('User import started')
            ->success()
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('admin');
    }
}
