<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutgoingResource\Pages;
use App\Forms\Components\Selector;
use App\Helpers\CommonHelpers;
use App\Models\Company;
use App\Models\Outgoing;
use App\Models\Recipient;
use App\Services\DocumentMailer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Throwable;

class OutgoingResource extends Resource
{
    protected static ?string $model = Outgoing::class;
    protected static ?string $navigationIcon = 'fas-file-arrow-up';
    protected static ?string $label = 'صادر';
    protected static ?string $pluralLabel = 'البريد الصادر';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('issue_number')
                    ->label('إشاري')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('الشركة')
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('تاريخ')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('to')
                    ->label('إلى')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->alignCenter()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('send-email')
                    ->label('إرسال')
                    ->icon('heroicon-o-envelope')
                    ->modalHeading('إرسال بريد إلكتروني')
                    ->modalSubmitActionLabel('إرسال')
                    ->form([
                        Forms\Components\TextInput::make('emails')
                            ->label('البريد الإلكتروني')
                            ->helperText('يمكن إدخال عدة عناوين مفصولة بفاصلة أو مسافة')
                            ->default(fn(Outgoing $record) => $record->recipient?->email ?? '')
                            ->required(),
                    ])
                    ->action(function (Outgoing $record, array $data) {
                        try {
                            $emails = collect(preg_split('/[,\s;]+/', (string)$data['emails']))
                                ->filter()
                                ->unique()
                                ->all();
                            $html = "";
                            $attachments = [];
                            ['content' => $pdfBytes, 'filename' => $fn] = CommonHelpers::buildOutgoingPdf($record);
                            $attachments[] = ['content' => $pdfBytes, 'as' => $fn, 'mime' => 'application/pdf'];
                            $paths = $record->attachments ?? [];
                            if (!empty($paths)) {
                                $merged = CommonHelpers::mergePdfsInMemory($paths);
                                $attachments[] = ['content' => $merged, 'as' => 'attachments.pdf', 'mime' => 'application/pdf'];
                            }
                            DocumentMailer::sendEmail(
                                to: $emails,
                                subject: 'إشعار صادر: ' . ($record->issue_number ?? ''),
                                html: $html,
                                attachments: $attachments,
                                options: ['queue' => false]
                            );

                            Notification::make()
                                ->title('تم الإرسال')
                                ->body('تم إرسال البريد إلى: ' . implode(', ', $emails))
                                ->success()
                                ->send();

                        } catch (Throwable $e) {
                            Notification::make()
                                ->title('تعذر الإرسال')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->color('primary'),
                Tables\Actions\Action::make('print')
                    ->label('طباعة')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('outgoings.print', $record))
                    ->color('success')
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Selector::make('company_id')
                            ->label('الشركة')
                            ->relationship('company', 'name')
                            ->reactive()
                            ->required(),
                        Selector::make('recipient_id')
                            ->label('المستلم')
                            ->reactive()
                            ->relationship('recipient', 'name')
                            ->afterStateUpdated(
                                function ($state, Forms\Set $set) {
                                    if ($state) {
                                        $recipient = Recipient::find($state);
                                        $set('to', $recipient->name);
                                    } else {
                                        $set('to', '');
                                    }
                                }
                            ),
                        Forms\Components\TextInput::make('issue_number')
                            ->label('الرقم الإشاري')
                            ->required()
                            ->reactive()
                            ->default(fn() => CommonHelpers::nextIssueNumber())
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('تاريخ الإصدار')
                            ->default(now())
                            ->reactive()
                            ->required(),
                        Forms\Components\TextInput::make('to')
                            ->label('إلى')
                            ->columnSpanFull()
                            ->reactive()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->label('الموضوع')
                            ->columnSpanFull()
                            ->reactive()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('body')
                            ->label('المحتوى')
                            ->reactive()
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(),
                ]),

                Forms\Components\Group::make([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\View::make('outgoing.live-preview')
                                ->reactive()
                                ->viewData(fn(Forms\Get $get) => [
                                    'issue_number' => $get('issue_number'),
                                    'issue_date' => $get('issue_date') ?: now(),
                                    'receiver' => $get('to'),
                                    'title' => $get('title'),
                                    'body' => $get('body'),
                                    'ceo_name' => $get('company_id') ? optional(Company::find($get('company_id')))->ceo : null,
                                    'letterhead' => optional(Company::find($get('company_id')))?->letterhead,
                                ]),
                        ]),
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
            'index' => Pages\ListOutgoings::route('/'),
            'create' => Pages\CreateOutgoing::route('/create'),
            'edit' => Pages\EditOutgoing::route('/{record}/edit'),
        ];
    }
}
