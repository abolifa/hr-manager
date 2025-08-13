<?php

namespace App\Filament\Resources\IncomingResource\Pages;

use App\Filament\Resources\IncomingResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewIncoming extends ViewRecord
{
    protected static string $resource = IncomingResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make([
                    TextEntry::make('company.name')
                        ->size(TextEntry\TextEntrySize::Medium)
                        ->weight(FontWeight::SemiBold)
                        ->label('الشركة'),
                    TextEntry::make('internal_number')
                        ->label('الرقم الإشاري')
                        ->size(TextEntry\TextEntrySize::Medium)
                        ->weight(FontWeight::SemiBold)
                        ->placeholder('-'),
                    TextEntry::make('received_date')
                        ->label('تاريخ الاستلام')
                        ->size(TextEntry\TextEntrySize::Medium)
                        ->weight(FontWeight::SemiBold)
                        ->date('d/m/Y'),
                    TextEntry::make('from_name')
                        ->size(TextEntry\TextEntrySize::Medium)
                        ->weight(FontWeight::SemiBold)
                        ->limit(25)
                        ->label('المرسل'),
                ])->columns([
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 4,
                ]),
                Section::make([
                    ViewEntry::make('document_files')
                        ->view('incomings.preview')
                        ->label('ملفات المستند')]),
            ]);
    }
}
