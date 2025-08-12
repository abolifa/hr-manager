<?php

namespace App\Filament\Resources\OutgoingResource\Pages;

use App\Filament\Resources\OutgoingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutgoing extends EditRecord
{
    protected static string $resource = OutgoingResource::class;

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
