<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingResource\Pages;
use App\Forms\Components\Selector;
use App\Helpers\CommonHelpers;
use App\Models\Incoming;
use App\Models\Recipient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncomingResource extends Resource
{
    protected static ?string $model = Incoming::class;

    protected static ?string $navigationIcon = 'fas-file-arrow-down';


    protected static ?string $label = 'وارد';
    protected static ?string $pluralLabel = 'البريد الوارد';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Selector::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('internal_number')
                        ->label('الرقم الإشاري')
                        ->required()
                        ->default(CommonHelpers::nextIncomingNumber())
                        ->maxLength(255),
                    Selector::make('from_recipient_id')
                        ->label('المرسل')
                        ->reactive()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $recipient = Recipient::find($state);
                                $set('from_name', $recipient->name ?? '');
                            } else {
                                $set('from_name', '');
                            }
                        })
                        ->relationship('fromRecipient', 'name'),
                    Forms\Components\TextInput::make('from_name')
                        ->label('اسم المرسل')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('received_date')
                        ->label('تاريخ الاستلام')
                        ->required()
                        ->default(now()),
                    Forms\Components\Textarea::make('notes')
                        ->label('ملاحظات')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('المرفقات')
                        ->multiple()
                        ->directory('incomings')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/*',
                            'text/plain',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->columnSpanFull()
                        ->required(),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('internal_number')
                    ->label('إشاري')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('الشركة')
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fromRecipientـname')
                    ->label('المرسل')
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('received_date')
                    ->label('تاريخ الاستلام')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListIncomings::route('/'),
            'create' => Pages\CreateIncoming::route('/create'),
            'edit' => Pages\EditIncoming::route('/{record}/edit'),
            'view' => Pages\ViewIncoming::route('/{record}'),
        ];
    }
}
