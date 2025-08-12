<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'fas-building-ngo';


    protected static ?string $label = 'شركة';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralLabel = 'الشركات';

    protected static ?string $navigationGroup = 'إدارة الشركات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم الشركة')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('english_name')
                        ->label('الاسم بالانجليزية')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('law_shape')
                        ->label('الشكل القانوني')
                        ->required()
                        ->placeholder('مثال: لاستيراد المواشي واللحوم')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('ceo')
                        ->label('مفوض الشركة')
                        ->maxLength(255),
                    Forms\Components\TagsInput::make('members')
                        ->placeholder('أعضاء الشركة')
                        ->label('أعضاء الشركة'),
                    Forms\Components\TextInput::make('capital')
                        ->label('رأس المال')
                        ->numeric(),
                    Forms\Components\DatePicker::make('established_at')
                        ->label('تاريخ التأسيس'),
                    Forms\Components\TextInput::make('address')
                        ->label('العنوان')
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('logo')
                        ->label('شعار الشركة')
                        ->image()
                        ->imageEditor()
                        ->directory('company-logos')
                        ->visibility('public'),
                    Forms\Components\FileUpload::make('letterhead')
                        ->label('قالب الرسالة المعنونة')
                        ->image()
                        ->imageEditor()
                        ->directory('company-letterheads')
                        ->visibility('public'),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('الشعار')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الإسم')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ceo')
                    ->label('المفوض')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('established_at')
                    ->label('تاريخ التأسيس')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('letter')
                    ->label('رسالة معنونة')
                    ->getStateUsing(fn($record) => (bool)$record->letterhead)
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
