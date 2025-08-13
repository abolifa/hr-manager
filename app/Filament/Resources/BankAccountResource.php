<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages;
use App\Forms\Components\BooleanField;
use App\Forms\Components\Selector;
use App\Models\BankAccount;
use App\Models\Company;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationIcon = 'fas-money-bill-wave-alt';


    protected static ?string $label = 'حساب';
    protected static ?string $pluralLabel = 'الحسابات المصرفية';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Selector::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->reactive()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $company = Company::find($state);
                                if ($company->ceo) {
                                    $set('account_holder_name', $company->ceo);
                                } else {
                                    $set('account_holder_name', null);
                                }
                            }
                        })
                        ->required(),
                    Forms\Components\TextInput::make('account_number')
                        ->label('رقم الحساب')
                        ->required()
                        ->numeric()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_name')
                        ->label('اسم المصرف')
                        ->required()
                        ->datalist(fn() => BankAccount::distinct('bank_name')->pluck('bank_name'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('branch_name')
                        ->label('اسم الفرع')
                        ->datalist(fn() => BankAccount::distinct('branch_name')->pluck('branch_name'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('account_holder_name')
                        ->label('اسم مفوض الحساب')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('swift_code')
                        ->label('رمز SWIFT')
                        ->maxLength(255),
                    Forms\Components\Select::make('currency')
                        ->label('العملة')
                        ->options([
                            'LYD' => 'دينار ليبي',
                            'USD' => 'دولار أمريكي',
                            'EUR' => 'يورو',
                            'GBP' => 'جنيه إسترليني',
                            'AED' => 'درهام إماراتي',
                        ])
                        ->default('LYD')
                        ->native(false)
                        ->required(),
                    Forms\Components\Select::make('account_type')
                        ->label('نوع الحساب')
                        ->options([
                            'normal' => 'حساب جاري',
                            'card' => 'حساب بطاقة',
                            'other' => 'أخرى',
                        ])
                        ->default('normal')
                        ->native(false)
                        ->required(),
                    BooleanField::make('active')
                        ->required(),
                ])->columns(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('الشركة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->label('رقم الحساب')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank')
                    ->label('المصرف')
                    ->getStateUsing(function ($record) {
                        if ($record->bank_name && $record->branch_name) {
                            return $record->bank_name . ' - ' . $record->branch_name;
                        } else {
                            return $record->bank_name ?: $record->branch_name ?: '-';
                        }
                    })
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->where(function (Builder $q) use ($search) {
                                $q->where('bank_name', 'like', "%$search%")
                                    ->orWhere('branch_name', 'like', "%$search%");
                            });
                        },
                        isIndividual: false,
                    )
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('account_holder_name')
                    ->label('المفوض')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('swift_code')
                    ->label('SWIFT')
                    ->alignCenter()
                    ->placeholder('-')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('العملة')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'LYD' => 'دينار ليبي',
                        'USD' => 'دولار أمريكي',
                        'EUR' => 'يورو',
                        'GBP' => 'جنيه إسترليني',
                        'AED' => 'درهام إماراتي',
                        default => $state,
                    })
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'LYD' => 'primary',
                        'USD' => 'success',
                        'EUR' => 'info',
                        'GBP' => 'warning',
                        'AED' => 'danger',
                        default => 'secondary',
                    })
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('نشط')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('الشركة')
                    ->searchable()
                    ->preload()
                    ->placeholder('الكل'),
                Tables\Filters\SelectFilter::make('currency')
                    ->options([
                        'LYD' => 'دينار ليبي',
                        'USD' => 'دولار أمريكي',
                        'EUR' => 'يورو',
                        'GBP' => 'جنيه إسترليني',
                        'AED' => 'درهام إماراتي',
                    ])
                    ->label('العملة')
                    ->native(false)
                    ->placeholder('الكل'),
                Tables\Filters\SelectFilter::make('account_type')
                    ->options([
                        'normal' => 'حساب جاري',
                        'card' => 'حساب بطاقة',
                        'other' => 'أخرى',
                    ])
                    ->label('نوع الحساب')
                    ->native(false)
                    ->placeholder('الكل'),
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
            'index' => Pages\ListBankAccounts::route('/'),
            'create' => Pages\CreateBankAccount::route('/create'),
            'edit' => Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }
}
