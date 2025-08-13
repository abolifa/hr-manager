<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Incoming;
use App\Models\Outgoing;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemState extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('الشركات', Company::count())
                ->label('الشركات')
                ->description('إجمالي عدد الشركات المسجلة')
                ->color('primary')
                ->icon('fas-building'),
            Stat::make('الموضفين', Employee::count())
                ->label('الموظفين')
                ->description('إجمالي عدد الموظفين المسجلين')
                ->color('warning')
                ->icon('fas-user-tie'),
            Stat::make('المهام', Task::count())
                ->label('المهام')
                ->description('إجمالي عدد المهام المسجلة')
                ->color('info')
                ->icon('fas-car'),
            Stat::make('المستندات', Document::count())
                ->label('المستندات')
                ->description('إجمالي عدد المستندات المسجلة')
                ->color('rose')
                ->icon('fas-file-alt'),
            Stat::make('البريد الصادر', Outgoing::count())
                ->label('البريد الصادر')
                ->description('إجمالي عدد البريد الصادر')
                ->color('cyan')
                ->icon('heroicon-s-document-arrow-up'),
            Stat::make('البريد الوارد', Incoming::count())
                ->label('البريد الوارد')
                ->description('إجمالي عدد البريد الوارد')
                ->color('rose')
                ->icon('heroicon-s-document-arrow-down'),
        ];
    }
}
