<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Forms\Components\BooleanField;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'fas-user';


    protected static ?string $label = 'حساب';
    protected static ?string $pluralLabel = 'المستخدمين';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
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
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->label('كلمة المرور')
                        ->password()
                        ->required()
                        ->disabled(fn($livewire) => $livewire instanceof Pages\EditUser)
                        ->required(fn($livewire) => !$livewire instanceof Pages\EditUser)
                        ->maxLength(255),
                    BooleanField::make('active'),
                ])->columns()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->sortable()
                    ->placeholder('-')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('نشط')
                    ->alignCenter(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
