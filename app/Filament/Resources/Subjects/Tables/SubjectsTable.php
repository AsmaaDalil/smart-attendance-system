<?php

namespace App\Filament\Resources\Subjects\Tables;

use App\Models\Subject;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('subject_name')
                    ->label('Subject Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('subject_code')
                    ->label('Subject Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('professor.name')
                    ->label('Professor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('students_count')
                    ->label('Students')
                    ->counts('students')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('sessions_count')
                    ->label('Sessions')
                    ->counts('sessions')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Added On')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),

            ])

            ->defaultSort('created_at', 'desc')

            ->recordActions([

                EditAction::make()
                    ->label('Edit'),

                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->visible(
                        fn (Subject $record): bool =>
                            ! $record->sessions()->exists()
                    ),

            ])

            ->emptyStateHeading('No subjects added yet')

            ->emptyStateDescription(
                'Add the first subject and assign it to a professor.'
            )

            ->emptyStateIcon('heroicon-o-book-open');
    }
}