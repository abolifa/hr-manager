<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DocumentAlerts extends BaseWidget
{
    protected static ?string $heading = 'المستندات التي ستنتهي صلاحيتها خلال 60 يومًا';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Document::query()
                    ->with('company')
                    ->where('expiry_date', '<=', now()->addDays(60))
                    ->where('expiry_date', '<=', now())
                    ->orderBy('expiry_date')
                    ->limit(10)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('id')
                    ->label('رقم')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('الشركة')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('نوع المستند')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'commercial_registration' => 'سجل تجاري',
                        'business_license' => 'رخصة تجارية',
                        'industrial_license' => 'رخصة صناعية',
                        'importers_register' => 'سجل المستوردين',
                        'statistical_code' => 'رمز إحصائي',
                        'authorized_economic_operator' => 'المشغل الاقتصادي',
                        'tax_clearance' => 'شهادة سداد ضريبي',
                        'balance_sheet' => 'ميزانية عمومية',
                        'social_security_clearance' => 'شهادة سداد اشتراكات ضمانية',
                        'partnership_certificate' => 'شهادة تظامن',
                        'articles_of_incorporation' => 'عقد التأسيس',
                        'amendment_contract' => 'عقد تعديل',
                        'company_bylaws' => 'النظام الأساسي',
                        'else' => 'أخرى',
                    })
                    ->badge()
                    ->alignCenter()
                    ->color(fn($state) => match ($state) {
                        'commercial_registration' => 'info',
                        'business_license' => 'success',
                        'industrial_license' => 'warning',
                        'importers_register' => 'gray',
                        'statistical_code' => 'neutral',
                        'authorized_economic_operator' => 'rose',
                        'tax_clearance' => 'orange',
                        'balance_sheet' => 'cyan',
                        'social_security_clearance' => 'teal',
                        'partnership_certificate' => 'purple',
                        'articles_of_incorporation' => 'indigo',
                        'amendment_contract' => 'pink',
                        'company_bylaws' => 'blue',
                        'else' => 'yellow',
                    })
                    ->sortable(),
                TextColumn::make('issue_date')
                    ->label('تاريخ الإصدار')
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->color(function ($state) {
                        if (!$state) {
                            return 'gray';
                        }
                        $expiryDate = Carbon::parse($state);
                        if ($expiryDate->isPast()) {
                            return 'danger';
                        }
                        if ($expiryDate->lte(now()->addDays(30))) {
                            return 'warning';
                        }
                        return 'success';
                    })
                    ->sortable(),
            ])->emptyStateHeading('لا توجد مستندات')
            ->emptyStateIcon('fas-file-alt');
    }
}
