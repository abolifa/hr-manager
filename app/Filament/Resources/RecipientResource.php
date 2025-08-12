<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipientResource\Pages;
use App\Forms\Components\BooleanField;
use App\Models\Recipient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RecipientResource extends Resource
{
    protected static ?string $model = Recipient::class;

    protected static ?string $navigationIcon = 'fas-users-rectangle';

    protected static ?int $navigationSort = 4;
    protected static ?string $label = 'جهة';
    protected static ?string $pluralLabel = 'جهات الإتصال';

    protected static ?string $navigationGroup = 'إدارة الشركات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم الجهة')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->label('العنوان')
                        ->maxLength(255),
                    BooleanField::make('is_government')
                        ->label('جهة حكومية')
                        ->default(false)
                        ->required(),
                    BooleanField::make('active')
                        ->required(),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الإسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_government')
                    ->alignCenter()
                    ->label('حكومية'),
                Tables\Columns\ToggleColumn::make('active')
                    ->alignCenter()
                    ->label('نشط'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListRecipients::route('/'),
            'create' => Pages\CreateRecipient::route('/create'),
            'edit' => Pages\EditRecipient::route('/{record}/edit'),
        ];
    }
}
