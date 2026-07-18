<?php

namespace App\Filament\Resources\Excuses\Tables;

use App\Models\Excuse;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
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
                    ->limit(30),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(40)
                    ->wrap()
                    ->tooltip(
                        fn (Excuse $record): string =>
                            $record->reason
                    ),

                TextColumn::make('file_path')
                    ->label('Attachment')
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            filled($state)
                                ? 'View File'
                                : 'No Attachment'
                    )
                    ->url(
                        fn (?string $state): ?string =>
                            filled($state)
                                ? Storage::disk('public')
                                    ->url($state)
                                : null
                    )
                    ->openUrlInNewTab()
                    ->color(
                        fn (?string $state): string =>
                            filled($state)
                                ? 'primary'
                                : 'gray'
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
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
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Excuse')
                    ->modalDescription(
                        'The attendance record will be changed to Excused.'
                    )
                    ->visible(
                        fn (Excuse $record): bool =>
                            $record->status !== 'Approved'
                    )
                    ->action(
                        fn (Excuse $record) =>
                            $record->update([
                                'status' => 'Approved',
                            ])
                    ),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Excuse')
                    ->modalDescription(
                        'The attendance record will remain Absent.'
                    )
                    ->visible(
                        fn (Excuse $record): bool =>
                            $record->status !== 'Rejected'
                    )
                    ->action(
                        fn (Excuse $record) =>
                            $record->update([
                                'status' => 'Rejected',
                            ])
                    ),

                EditAction::make()
                    ->label('Edit'),

                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->before(
                        function (Excuse $record): void {
                            if (filled($record->file_path)) {
                                Storage::disk('public')
                                    ->delete($record->file_path);
                            }
                        }
                    ),
            ])

            ->emptyStateHeading('No excuses submitted yet')

            ->emptyStateDescription(
                'Student excuses will appear here for review.'
            )

            ->emptyStateIcon(
                'heroicon-o-document-text'
            );
    }
}