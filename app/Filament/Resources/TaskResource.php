<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Employee;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $label = 'مهمة';
    protected static ?string $pluralLabel = 'المهام';

    protected static ?string $navigationIcon = 'fas-clipboard-list';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('title')
                        ->label('عنوان المهمة')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\ToggleButtons::make('priority')
                        ->label('الأولوية')
                        ->options([
                            'low' => 'عادية',
                            'medium' => 'متوسطة',
                            'high' => 'مرتفعة',
                            'urgent' => 'عاجلة',
                        ])
                        ->default('medium')
                        ->grouped()
                        ->inline()
                        ->colors([
                            'low' => 'info',
                            'medium' => 'warning',
                            'high' => 'rose',
                            'urgent' => 'danger',
                        ])
                        ->required(),
                    Forms\Components\RichEditor::make('description')
                        ->label('وصف المهمة')
                        ->columnSpanFull(),
                    Forms\Components\Select::make('status')
                        ->label('حالة المهمة')
                        ->options([
                            'in_progress' => 'قيد التنفيذ',
                            'completed' => 'مكتملة',
                            'cancelled' => 'ملغية',
                        ])
                        ->default('in_progress')
                        ->required(),
                    Forms\Components\DatePicker::make('due_date')
                        ->label('المدة النهائية')
                        ->required()
                        ->displayFormat('d/m/Y')
                        ->default(now()->addDays(7)),
                ])->columns(),

                Forms\Components\Section::make([
                    Forms\Components\ToggleButtons::make('for_all')
                        ->label('تحديد الكل')
                        ->boolean()
                        ->inline()
                        ->grouped()
                        ->default(false)
                        ->reactive()
                        ->afterStateUpdated(
                            function ($state, callable $set) {
                                if ($state) {
                                    $set('target_mode', 'manual');
                                    $set('target_role', null);
                                    $set('company_id', null);
                                    $set('employees', Employee::all()->pluck('id')->toArray());
                                } else {
                                    $set('employees', []);
                                    $set('target_mode', 'manual');
                                    $set('target_role', null);
                                    $set('company_id', null);
                                }
                            }
                        )
                        ->required(),
                    Forms\Components\ToggleButtons::make('target_mode')
                        ->label('نوع الإستهداف')
                        ->options([
                            'manual' => 'يدوي',
                            'role' => 'دور',
                            'company' => 'شركة',
                        ])
                        ->inline()
                        ->grouped()
                        ->reactive()
                        ->default('manual')
                        ->required(),
                    Forms\Components\Select::make('target_role')
                        ->label('الدور المستهدف')
                        ->nullable()
                        ->disabled(fn($get) => $get('target_mode') !== 'role')
                        ->required(fn($get) => $get('target_mode') === 'role')
                        ->options([
                            'employee' => 'موظف',
                            'accountant' => 'محاسب',
                            'driver' => 'سائق',
                            'manager' => 'مدير',
                            'sales' => 'مبيعات',
                            'hr' => 'موارد بشرية',
                            'supervisor' => 'مشرف',
                        ])->native(false)
                        ->live(onBlur: true)
                        ->reactive()
                        ->afterStateUpdated(
                            function ($state, callable $set, callable $get) {
                                if ($get('target_mode') === 'role' && $state) {
                                    $set('employee', []);
                                    $set('for_all', false);
                                    $set('company_id', null);
                                    $set('employees', Employee::where('role', $state)->pluck('id')->toArray());
                                }
                            }
                        ),
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->disabled(fn($get) => $get('target_mode') !== 'company')
                        ->required(fn($get) => $get('target_mode') === 'company')
                        ->searchable()
                        ->reactive()
                        ->live(onBlur: true)
                        ->afterStateUpdated(
                            function ($state, callable $set, callable $get) {
                                if ($get('target_mode') === 'company' && $state) {
                                    $set('employee', []);
                                    $set('target_role', null);
                                    $set('for_all', false);
                                    $set('employees', Employee::where('company_id', $state)->pluck('id')->toArray());
                                }
                            }
                        )
                        ->preload(),
                    Forms\Components\Select::make('employees')
                        ->label('الموظفين المستهدفين')
                        ->multiple()
                        ->columnSpanFull()
                        ->relationship('employees', 'name')
                        ->preload()
                        ->searchable(),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('الأولوية')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'low' => 'عادية',
                        'medium' => 'متوسطة',
                        'high' => 'مرتفعة',
                        'urgent' => 'عاجلة',
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'low' => 'info',
                        'medium' => 'warning',
                        'high' => 'rose',
                        'urgent' => 'danger',
                    })
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'in_progress' => 'قيد التنفيذ',
                        'completed' => 'مكتملة',
                        'cancelled' => 'ملغية',
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('المدة النهائية')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_mode')
                    ->label('نوع الإستهداف')
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'manual' => 'يدوي',
                        'role' => 'دور',
                        'company' => 'شركة',
                    })
                    ->tooltip(
                        function ($record) {
                            $empolyees = $record->employees->pluck('name')->implode(', ');
                            return 'الموظفين:' . $empolyees;
                        }
                    )->sortable(),
                Tables\Columns\IconColumn::make('for_all')
                    ->boolean()
                    ->alignCenter()
                    ->label('تحديد الكل'),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
