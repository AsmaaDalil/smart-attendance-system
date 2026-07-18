<?php

namespace App\Filament\Resources\Enrollments\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentsTable
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

                TextColumn::make('subject.subject_name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject.subject_code')
                    ->label('Subject Code')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

          TextColumn::make('created_at')
    ->label('Enrolled On')
    ->dateTime('Y-m-d H:i')
    ->placeholder('Not recorded')
    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('student_id')
                    ->label('Student')
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'university_number',
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('subject_id')
                    ->label('Subject')
                    ->relationship(
                        name: 'subject',
                        titleAttribute: 'subject_name',
                    )
                    ->searchable()
                    ->preload(),
            ])

            ->defaultSort('created_at', 'desc')

            ->recordActions([
                EditAction::make()
                    ->label('Edit'),

                DeleteAction::make()
                    ->label('Remove')
                    ->requiresConfirmation(),
            ])

            ->emptyStateHeading('No enrollments yet')

            ->emptyStateDescription(
                'Enroll students in subjects to begin tracking their attendance.'
            )

            ->emptyStateIcon('heroicon-o-user-plus');
    }
}