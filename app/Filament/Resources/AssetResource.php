<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Forms\Components\BooleanField;
use App\Forms\Components\Selector;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'fas-truck';

    protected static ?string $label = 'أصول';
    protected static ?string $pluralLabel = 'الأصول';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Selector::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->required(),
                    Forms\Components\Select::make('type')
                        ->label('النوع')
                        ->native(false)
                        ->options([
                            'vehicle' => 'مركبة',
                            'generator' => 'مولد',
                            'other' => 'أخرى',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('serial_number')
                        ->label('الرقم التسلسلي')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('model')
                        ->label('الموديل')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('brand')
                        ->label('الماركة')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('color')
                        ->label('اللون')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('plate_number')
                        ->label('رقم اللوحة')
                        ->maxLength(255),
                    BooleanField::make('maintenance_required')
                        ->label('الصيانة مطلوبة')
                        ->default(false)
                        ->required(),
                    Selector::make('employee_id')
                        ->label('الموظف المسؤول')
                        ->relationship('employee', 'name'),
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
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'vehicle' => 'مركبة',
                        'generator' => 'مولد',
                        'other' => 'أخرى',
                    })
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'vehicle' => 'primary',
                        'generator' => 'secondary',
                        'other' => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('اللون')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\IconColumn::make('maintenance_required')
                    ->label('الصيانة مطلوبة')
                    ->alignCenter()
                    ->boolean(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('المسؤول')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
