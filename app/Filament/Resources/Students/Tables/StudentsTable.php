<?php

namespace App\Filament\Resources\Students\Tables;

use App\Models\Student;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('university_number')
                    ->label('University Number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('user.email')
                    ->label('Email Address')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('academic_year')
                    ->label('Academic Year')
                    ->badge()
                    ->formatStateUsing(
                        fn (int $state): string => match ($state) {
                            1 => 'First Year',
                            2 => 'Second Year',
                            3 => 'Third Year',
                            4 => 'Fourth Year',
                            5 => 'Fifth Year',
                            default => 'Unknown',
                        }
                    )
                    ->color('primary'),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->placeholder('Not provided'),

                IconColumn::make('is_dormitory')
                    ->label('Dormitory')
                    ->boolean(),

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
                    ->action(function (Student $record): void {
                        /*
                         * حذف حساب المستخدم يؤدي تلقائياً إلى حذف
                         * سجل الطالب بسبب cascadeOnDelete.
                         */
                        $record->user->delete();
                    }),

            ])

            ->emptyStateHeading('No students added yet')

            ->emptyStateDescription(
                'Add the first student to begin managing attendance.'
            )

            ->emptyStateIcon('heroicon-o-user-group');
    }
}