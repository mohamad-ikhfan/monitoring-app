<?php

namespace App\Jobs;

use App\Imports\PoItemImport;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PoItemImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $receipent;
    private $file;

    /**
     * Create a new job instance.
     */
    public function __construct(User $receipent, $file)
    {
        $this->receipent = $receipent;
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            (new PoItemImport)->import($this->file);
            Notification::make()
                ->success()
                ->title('Imported Po Item.')
                ->body('Po item imported successfully, please refresh page.')
                ->sendToDatabase($this->receipent);
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Imported Po Item.')
                ->body($e->getMessage())
                ->sendToDatabase($this->receipent);
        }
        unlink($this->file);
    }
}