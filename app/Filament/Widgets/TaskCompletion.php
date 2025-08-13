<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TaskCompletion extends BaseWidget
{
    protected static ?string $heading = 'إنجاز المهام';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query(Task::query()->withCount([
                'assignedEmployees',
                'doneEmployees',
            ]))
            ->columns([
                TextColumn::make('title')
                    ->label('المهمة')
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('الأولوية')
                    ->alignCenter()
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'low' => 'منخفضة',
                        'medium' => 'متوسطة',
                        'high' => 'مرتفعة',
                        'urgent' => 'عاجلة',
                    })
                    ->color(fn(string $state) => match ($state) {
                        'low' => 'secondary',
                        'medium' => 'primary',
                        'high' => 'warning',
                        'urgent' => 'danger',
                    }),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->alignCenter()
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'in_progress' => 'قيد التنفيذ',
                        'completed' => 'مكتملة',
                        'cancelled' => 'ملغاة',
                    })
                    ->color(fn(string $state) => match ($state) {
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                TextColumn::make('due_date')
                    ->label('المدة النهائية')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('المعينين')
                    ->alignCenter()
                    ->badge()
                    ->getStateUsing(fn(Task $record) => $record->assigned_employees_count + $record->in_progress_employees_count + $record->done_employees_count)
                    ->color('rose')
                    ->sortable(),
                TextColumn::make('done_employees_count')
                    ->label('منجز')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('completion_rate')
                    ->label('نسبة الإنجاز')
                    ->alignCenter()
                    ->getStateUsing(function (Task $record) {
                        $total = $record->assigned_employees_count + $record->in_progress_employees_count + $record->done_employees_count;

                        if ($total === 0) return '0%';

                        $percentage = round(($record->done_employees_count / $total) * 100, 1);

                        return $percentage . '%';
                    })
                    ->color(fn(string $state) => match (true) {
                        $state === '0%' => 'danger',
                        (float)rtrim($state, '%') < 50 => 'warning',
                        default => 'success',
                    }),
            ])->actions([
                ViewAction::make(),
            ])->emptyStateIcon('fas-tasks')
            ->emptyStateHeading('لا توجد مهام');
    }
}
