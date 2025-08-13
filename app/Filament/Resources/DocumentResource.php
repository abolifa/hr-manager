<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Forms\Components\Selector;
use App\Models\Document;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'fas-file-contract';

    protected static ?string $label = 'مستند';
    protected static ?string $pluralLabel = 'المستندات';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Selector::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('document_number')
                        ->label('رقم المستند')
                        ->maxLength(255),
                    Forms\Components\Select::make('type')
                        ->label('نوع المستند')
                        ->columnSpanFull()
                        ->options([
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
                            'else' => 'أخرى',
                        ])
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('issue_date')
                        ->label('تاريخ الإصدار')
                        ->required(),
                    Forms\Components\DatePicker::make('expiry_date')
                        ->label('تاريخ الانتهاء')
                        ->required(),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('المستند')
                        ->multiple()
                        ->imageEditor()
                        ->columnSpanFull()
                        ->directory('documents')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/*',
                        ]),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('الشركة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('رقم المستند')
                    ->alignCenter()
                    ->sortable()
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع المستند')
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
                    })->alignCenter()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'commercial_registration' => 'rose',
                        'business_license' => 'cyan',
                        'industrial_license' => 'blue',
                        'importers_register' => 'indigo',
                        'statistical_code' => 'purple',
                        'authorized_economic_operator' => 'pink',
                        'tax_clearance' => 'red',
                        'balance_sheet' => 'orange',
                        'social_security_clearance' => 'yellow',
                        'partnership_certificate' => 'green',
                        'articles_of_incorporation' => 'amber',
                        'amendment_contract' => 'slate',
                        'company_bylaws' => 'zinc',
                        default => 'gray',
                    })->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('تاريخ الإصدار')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->color(function ($record) {
                        $date = Carbon::parse($record->expiry_date);
                        if ($date->gt(Carbon::now()->addMonths(3))) {
                            return 'green';
                        } elseif ($date->gt(Carbon::now()->addMonth())) {
                            return 'yellow';
                        }
                        return 'red';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_in')
                    ->label('تاريخ الانتهاء')
                    ->alignCenter()
                    ->getStateUsing(fn($record) => Carbon::parse($record->expiry_date)->diffForHumans()),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
            'view' => Pages\ViewDocument::route('/{record}'),
        ];
    }
}
