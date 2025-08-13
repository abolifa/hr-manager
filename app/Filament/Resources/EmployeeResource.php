<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Forms\Components\Selector;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $label = 'موظف';
    protected static ?string $pluralLabel = 'الموظفين';

    protected static ?string $navigationIcon = 'fas-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\FileUpload::make('photo')
                        ->label('الصورة الشخصية')
                        ->image()
                        ->avatar()
                        ->columnSpanFull()
                        ->alignCenter()
                        ->disk('public')
                        ->directory('employees')
                        ->visibility('public'),
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->required()
                        ->maxLength(255),
                    Selector::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\Select::make('role')
                        ->label('الدور الوظيفي')
                        ->options([
                            'employee' => 'موطف',
                            'accountant' => 'محاسب',
                            'driver' => 'سائق',
                            'manager' => 'مدير',
                            'sales' => 'مندوب مبيعات',
                            'hr' => 'موارد بشرية',
                            'supervisor' => 'مشرف',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('password')
                        ->label('كلمة المرور')
                        ->type('password')
                        ->password()
                        ->required()
                        ->maxLength(255),
                ])->columns(),

                Forms\Components\Section::make('الملف الشخصي')
                    ->relationship('profile')
                    ->schema([
                        Forms\Components\Select::make('gender')
                            ->label('الجنس')
                            ->options([
                                'male' => 'ذكر',
                                'female' => 'أنثى',
                            ])
                            ->native(false),
                        Forms\Components\Select::make('marital_status')
                            ->label('الحالة الاجتماعية')
                            ->options([
                                'single' => 'أعزب',
                                'married' => 'متزوج',
                            ])
                            ->native(false),
                        Forms\Components\TextInput::make('nationality')
                            ->label('الجنسية')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('تاريخ الميلاد')
                            ->date(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('تاريخ بدء العمل')
                            ->date(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('تاريخ انتهاء العمل')
                            ->date(),
                        Forms\Components\FileUpload::make('license')
                            ->label('الرخصة')
                            ->disk('public')
                            ->directory('employees/licenses')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/*',
                            ])
                            ->multiple(false)
                            ->nullable(),
                        Forms\Components\FileUpload::make('birth_certificate')
                            ->label('شهادة الميلاد')
                            ->disk('public')
                            ->directory('employees/birth_certificates')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/*',
                            ])
                            ->multiple(false)
                            ->nullable(),
                        Forms\Components\FileUpload::make('passport')
                            ->label('جواز السفر')
                            ->disk('public')
                            ->directory('employees/passports')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/*',
                            ])
                            ->multiple(false)
                            ->nullable(),
                        Forms\Components\FileUpload::make('id_card')
                            ->label('بطاقة الهوية')
                            ->disk('public')
                            ->directory('employees/id_cards')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/*',
                            ])
                            ->multiple(false)
                            ->nullable(),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('الشركة')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('الدور الوظيفي')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'employee' => 'موطف',
                        'accountant' => 'محاسب',
                        'driver' => 'سائق',
                        'manager' => 'مدير',
                        'sales' => 'مندوب مبيعات',
                        'hr' => 'موارد بشرية',
                        'supervisor' => 'مشرف',
                    })->badge()
                    ->alignCenter()
                    ->color(fn($state) => match ($state) {
                        'employee' => 'primary',
                        'accountant' => 'success',
                        'driver' => 'warning',
                        'manager' => 'danger',
                        'sales' => 'info',
                        'hr' => 'rose',
                        'supervisor' => 'cyan',
                    }),
                Tables\Columns\TextColumn::make('profile_completeness')
                    ->label('الملف الشخصي')
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        $p = $record->profile;
                        if (!$p) {
                            return '0/9';
                        }

                        $fields = [
                            'gender',
                            'marital_status',
                            'nationality',
                            'date_of_birth',
                            'start_date',
                            'license',
                            'birth_certificate',
                            'passport',
                            'id_card',
                        ];

                        $filled = collect($fields)->filter(function ($f) use ($p) {
                            $v = $p->{$f} ?? null;
                            return !is_null($v) && $v !== '' && $v !== [];
                        })->count();

                        return "{$filled}/" . count($fields);
                    })
                    ->badge()
                    ->color(function ($state, $record) {
                        $total = 9;
                        [$filled] = explode('/', $state);
                        $filled = (int)$filled;
                        $ratio = $filled / $total;

                        return match (true) {
                            $ratio >= 1 => 'success',
                            $ratio >= 0.6 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->tooltip(function ($state, $record) {
                        $p = $record->profile;
                        if (!$p) return 'لا يوجد ملف شخصي';

                        $fields = [
                            'gender' => 'الجنس',
                            'marital_status' => 'الحالة الاجتماعية',
                            'nationality' => 'الجنسية',
                            'date_of_birth' => 'تاريخ الميلاد',
                            'start_date' => 'تاريخ بدء العمل',
                            'license' => 'الرخصة',
                            'birth_certificate' => 'شهادة الميلاد',
                            'passport' => 'جواز السفر',
                            'id_card' => 'بطاقة الهوية',
                        ];

                        $missing = collect($fields)->filter(function ($label, $key) use ($p) {
                            $v = $p->{$key} ?? null;
                            return is_null($v) || $v === '' || $v === [];
                        })->values()->all();

                        return empty($missing)
                            ? 'مكتمل'
                            : 'ناقص: ' . implode('، ', $missing);
                    }),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
