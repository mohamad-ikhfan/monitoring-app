<?php

namespace App\Filament\Resources\PoItemResource\Pages;

use App\Filament\Resources\PoItemResource;
use App\Imports\PoItemImport;
use App\Jobs\PoItemImportJob;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListPoItems extends ListRecords
{
    protected static string $resource = PoItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('New import po item')
                ->color('primary')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('Import')
                ->form(function (Forms\Form $form) {
                    return $form
                        ->schema([
                            Forms\Components\FileUpload::make('file_excel')
                                ->hiddenLabel()
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                                ->directory('imports')
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file): string => (string) str('import_po_items')
                                        ->append("_(" . now()->format('d-m-Y') . ")_" . time() . "." . $file->getClientOriginalExtension()),
                                )
                                ->required()
                        ]);
                })
                ->action(function (array $data) {
                    $file = storage_path('app/public/' . $data['file_excel']);
                    $receipent = User::find(auth()->user()->id);

                    PoItemImportJob::dispatch($receipent, $file);

                    Notifications\Notification::make()
                        ->success()
                        ->title('Import po items on background.')
                        ->body('After import finished, send your notification.')
                        ->send();
                })

        ];
    }
}
