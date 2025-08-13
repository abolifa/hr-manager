<?php

namespace App\Filament\Resources\OutgoingResource\Pages;

use App\Filament\Resources\OutgoingResource;
use App\Helpers\CommonHelpers;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EditOutgoing extends EditRecord
{
    protected static string $resource = OutgoingResource::class;


    protected function afterSave(): void
    {
        try {
            $fieldsAffectingPdf = ['issue_number', 'issue_date', 'to', 'title', 'body', 'company_id'];
            $dirty = $this->record->wasChanged($fieldsAffectingPdf);
            if (!$dirty) {
                return;
            }
            if ($this->record->wasChanged('issue_number')) {
                $old = $this->record->getOriginal('issue_number');
                Storage::disk('public')->delete("outgoings/outgoing-{$old}.pdf");
            }
            ['content' => $bytes, 'path' => $path] = CommonHelpers::buildOutgoingPdf($this->record);
            Storage::disk('public')->put($path, $bytes);
        } catch (Throwable $e) {
            Notification::make()
                ->title('تعذر تحديث ملف PDF')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('طباعة')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('outgoings.print', $record))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
