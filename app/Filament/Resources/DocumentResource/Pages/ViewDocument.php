<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make([
                    TextEntry::make('company.name')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::SemiBold)
                        ->label('الشركة'),
                    TextEntry::make('type')
                        ->label('نوع المستند')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::SemiBold)
                        ->formatStateUsing(fn($state) => match ($state) {
                            'commercial_registration' => 'سجل تجاري',
                            'business_license' => 'رخصة تجارية',
                            'industrial_license' => 'رخصة صناعية',
                            'importers_register' => 'سجل المستوردين',
                            'statistical_code' => 'رمز إحصائي',
                            'authorized_economic_operator' => 'المشغل الاقتصادي',
                            'tax_clearance' => 'شهادة سداد ضريبي',
                            'balance_sheet' => 'ميزانية عمومية',
                            'social_security_clearance' => 'شهادة سداد اشتراكات ضمانية',
                            'partnership_certificate' => 'شهادة تظامن',
                            'articles_of_incorporation' => 'عقد التأسيس',
                            'amendment_contract' => 'عقد تعديل',
                            'company_bylaws' => 'النظام الأساسي',
                            default => $state,
                        }),
                    TextEntry::make('document_number')
                        ->label('رقم المستند')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::SemiBold)
                        ->placeholder('-'),
                    TextEntry::make('issue_date')
                        ->label('تاريخ الإصدار')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::SemiBold)
                        ->date('d/m/Y'),
                    TextEntry::make('expiry_date')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::SemiBold)
                        ->label('تاريخ الانتهاء')
                        ->date('d/m/Y'),
                ])->columns([
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 4,
                    'xl' => 5,
                ]),
                Section::make([
                    ViewEntry::make('document_files')
                        ->view('documents.preview')
                        ->label('ملفات المستند')]),
            ]);
    }
}
