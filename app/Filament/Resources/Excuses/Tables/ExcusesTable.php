<?php

namespace App\Filament\Resources\Excuses\Tables;

use App\Models\Excuse;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExcusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make(
                    'attendanceRecord.student.user.name'
                )
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make(
                    'attendanceRecord.student.university_number'
                )
                    ->label('University Number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make(
                    'attendanceRecord.session.subject.subject_name'
                )
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),

                TextColumn::make(
                    'attendanceRecord.session.lecture_title'
                )
                    ->label('Lecture')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(
                        fn (Excuse $record): string =>
                            $record
                                ->attendanceRecord
                                ?->session
                                ?->lecture_title
                            ?? 'No lecture title'
                    ),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->wrap()
                    ->tooltip(
                        fn (Excuse $record): string =>
                            $record->reason
                    ),

                /*
                 * رابط مرفق العذر.
                 *
                 * نستخدم رابط الموقع المفتوح حاليًا بدل APP_URL،
                 * حتى يعمل على:
                 * - 127.0.0.1
                 * - Cloudflare HTTPS
                 */
                TextColumn::make('file_path')
                    ->label('Attachment')
                    ->formatStateUsing(
                        function (?string $state): string {
                            if (blank($state)) {
                                return 'No Attachment';
                            }

                            if (
                                ! Storage::disk('public')
                                    ->exists($state)
                            ) {
                                return 'File Missing';
                            }

                            return 'View File';
                        }
                    )
                    ->url(
                        function (?string $state): ?string {
                            if (blank($state)) {
                                return null;
                            }

                            if (
                                ! Storage::disk('public')
                                    ->exists($state)
                            ) {
                                return null;
                            }

                            return request()->root()
                                .'/storage/'
                                .ltrim($state, '/');
                        }
                    )
                    ->openUrlInNewTab()
                    ->color(
                        function (?string $state): string {
                            if (blank($state)) {
                                return 'gray';
                            }

                            return Storage::disk('public')
                                ->exists($state)
                                    ? 'primary'
                                    : 'danger';
                        }
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn (string $state): string =>
                            match ($state) {
                                'Pending' => 'warning',
                                'Approved' => 'success',
                                'Rejected' => 'danger',
                                default => 'gray',
                            }
                    )
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])

            ->filters([

                SelectFilter::make('status')
                    ->label('Excuse Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ]),
            ])

            ->defaultSort('created_at', 'desc')

            ->recordActions([

                /*
                 * قبول العذر:
                 * Excuse = Approved
                 * Attendance = Excused
                 */
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Excuse')
                    ->modalDescription(
                        'The attendance record will be changed to Excused.'
                    )
                    ->modalSubmitActionLabel('Yes, Approve')
                    ->visible(
                        fn (Excuse $record): bool =>
                            $record->status !== 'Approved'
                    )
                    ->action(
                        function (Excuse $record): void {
                            DB::transaction(
                                function () use ($record): void {
                                    $record->update([
                                        'status' => 'Approved',
                                    ]);

                                    $record
                                        ->attendanceRecord()
                                        ->update([
                                            'status' => 'Excused',
                                        ]);
                                }
                            );

                            Notification::make()
                                ->success()
                                ->title(
                                    'Excuse Approved Successfully'
                                )
                                ->body(
                                    'The attendance status was changed to Excused.'
                                )
                                ->send();
                        }
                    ),

                /*
                 * رفض العذر:
                 * Excuse = Rejected
                 * Attendance = Absent
                 */
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Excuse')
                    ->modalDescription(
                        'The attendance record will be changed to Absent.'
                    )
                    ->modalSubmitActionLabel('Yes, Reject')
                    ->visible(
                        fn (Excuse $record): bool =>
                            $record->status !== 'Rejected'
                    )
                    ->action(
                        function (Excuse $record): void {
                            DB::transaction(
                                function () use ($record): void {
                                    $record->update([
                                        'status' => 'Rejected',
                                    ]);

                                    $record
                                        ->attendanceRecord()
                                        ->update([
                                            'status' => 'Absent',
                                        ]);
                                }
                            );

                            Notification::make()
                                ->success()
                                ->title(
                                    'Excuse Rejected'
                                )
                                ->body(
                                    'The attendance status was changed to Absent.'
                                )
                                ->send();
                        }
                    ),

                EditAction::make()
                    ->label('Edit'),

                /*
                 * حذف العذر:
                 * - حذف المرفق من التخزين.
                 * - إعادة الحضور إلى Absent إذا كان العذر مقبولًا.
                 */
                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Excuse')
                    ->modalDescription(
                        'Are you sure you want to delete this excuse?'
                    )
                    ->modalSubmitActionLabel('Yes, Delete')
                    ->before(
                        function (Excuse $record): void {
                            if (
                                $record->status === 'Approved'
                                || $record
                                    ->attendanceRecord
                                    ?->status === 'Excused'
                            ) {
                                $record
                                    ->attendanceRecord()
                                    ->update([
                                        'status' => 'Absent',
                                    ]);
                            }

                            if (
                                filled($record->file_path)
                                && Storage::disk('public')
                                    ->exists($record->file_path)
                            ) {
                                Storage::disk('public')
                                    ->delete($record->file_path);
                            }
                        }
                    )
                    ->successNotificationTitle(
                        'Excuse deleted successfully.'
                    ),
            ])

            ->emptyStateHeading(
                'No excuses submitted yet'
            )

            ->emptyStateDescription(
                'Student excuses will appear here for review.'
            )

            ->emptyStateIcon(
                'heroicon-o-document-text'
            );
    }
}