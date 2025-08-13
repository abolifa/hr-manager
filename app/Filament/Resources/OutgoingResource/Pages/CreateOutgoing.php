<?php

namespace App\Filament\Resources\OutgoingResource\Pages;

use App\Filament\Resources\OutgoingResource;
use App\Helpers\CommonHelpers;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CreateOutgoing extends CreateRecord
{
    protected static string $resource = OutgoingResource::class;


    protected function afterCreate(): void
    {
        try {
            ['content' => $content, 'path' => $path] = CommonHelpers::buildOutgoingPdf($this->record);
            Storage::disk('public')->put($path, $content);
        } catch (Throwable $e) {
            Notification::make()
                ->title('تعذر إنشاء ملف PDF')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
