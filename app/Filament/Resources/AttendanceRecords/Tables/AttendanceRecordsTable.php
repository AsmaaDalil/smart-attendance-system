<?php

namespace App\Filament\Resources\AttendanceRecords\Tables;

use App\Models\AttendanceRecord;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('student.university_number')
                    ->label('University Number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('session.subject.subject_name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('session.lecture_number')
                    ->label('Lecture')
                    ->prefix('#')
                    ->sortable(),

                TextColumn::make('session.lecture_title')
                    ->label('Lecture Title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(
                        fn (AttendanceRecord $record): string =>
                            $record->session->lecture_title
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
                            'Present' => 'success',
                            'Late' => 'warning',
                            'Absent' => 'danger',
                            'Excused' => 'info',
                            default => 'gray',
                        }
                    )
                    ->sortable(),

                TextColumn::make('scanned_at')
                    ->label('Scanned At')
                    ->dateTime('Y-m-d H:i:s')
                    ->placeholder('Not scanned')
                    ->sortable(),

                TextColumn::make('distance_meters')
                    ->label('Distance')
                    ->formatStateUsing(
                        fn ($state): string =>
                            $state !== null
                                ? number_format((float) $state, 2).' m'
                                : 'Not recorded'
                    )
                    ->sortable(),

                IconColumn::make('is_dorm_approved')
                    ->label('Dorm Approved')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->label('Attendance Status')
                    ->options([
                        'Present' => 'Present',
                        'Late' => 'Late',
                        'Absent' => 'Absent',
                        'Excused' => 'Excused',
                    ]),

                SelectFilter::make('student_id')
                    ->label('Student')
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'university_number',
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('session_id')
                    ->label('Lecture Session')
                    ->relationship(
                        name: 'session',
                        titleAttribute: 'lecture_title',
                    )
                    ->searchable()
                    ->preload(),

                Filter::make('scanned_at')
                    ->label('Scan Date')
                    ->schema([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->native(false),

                        DatePicker::make('until')
                            ->label('Until Date')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->query(
                        fn (
                            Builder $query,
                            array $data
                        ): Builder => $query
                            ->when(
                                $data['from'] ?? null,
                                fn (
                                    Builder $query,
                                    $date
                                ): Builder => $query
                                    ->whereDate(
                                        'scanned_at',
                                        '>=',
                                        $date
                                    )
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (
                                    Builder $query,
                                    $date
                                ): Builder => $query
                                    ->whereDate(
                                        'scanned_at',
                                        '<=',
                                        $date
                                    )
                            )
                    ),
            ])

            ->defaultSort('created_at', 'desc')

            ->recordActions([
                EditAction::make()
                    ->label('Edit'),

                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation(),
            ])

            ->emptyStateHeading('No attendance records yet')

            ->emptyStateDescription(
                'Attendance records will appear after students scan a session QR code.'
            )

            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}