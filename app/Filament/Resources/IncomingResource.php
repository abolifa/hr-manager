<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingResource\Pages;
use App\Models\Incoming;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncomingResource extends Resource
{
    protected static ?string $model = Incoming::class;

    protected static ?string $navigationIcon = 'fas-file-arrow-down';

    protected static ?string $navigationGroup = 'المراسلات';


    protected static ?string $label = 'وارد';
    protected static ?string $pluralLabel = 'البريد الوارد';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                Forms\Components\TextInput::make('internal_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('from_recipient_id')
                    ->relationship('fromRecipient', 'name'),
                Forms\Components\DatePicker::make('received_date'),
                Forms\Components\TextInput::make('notes')
                    ->maxLength(255),
                Forms\Components\TextInput::make('attachments')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internal_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fromRecipient.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('received_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        ];
    }
}
