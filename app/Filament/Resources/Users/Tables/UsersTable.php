<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Professor Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(
                        fn (string $state): string => ucfirst($state)
                    ),

                TextColumn::make('subjects_count')
                    ->label('Subjects')
                    ->counts('subjects')
                    ->badge()
                    ->color('warning'),

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
                    ->modalHeading('Delete Professor')
                    ->modalDescription(
                        'Are you sure you want to delete this professor?'
                    )
                    ->modalSubmitActionLabel('Yes, Delete')
                    ->before(
                        function (
                            DeleteAction $action,
                            User $record
                        ): void {
                            /*
                             * منع حذف الدكتور إذا كان مرتبطًا
                             * بمادة واحدة أو أكثر.
                             */
                            if ($record->subjects()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title(
                                        'Professor Cannot Be Deleted'
                                    )
                                    ->body(
                                        'This professor is assigned to '
                                        . 'one or more subjects. Reassign '
                                        . 'or remove their subjects first.'
                                    )
                                    ->seconds(5)
                                    ->send();

                                /*
                                 * إيقاف عملية الحذف حتى لا يظهر
                                 * خطأ Foreign Key أو صفحة 500.
                                 */
                                $action->halt();
                            }
                        }
                    )
                    ->successNotificationTitle(
                        'Professor deleted successfully.'
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([

                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->requiresConfirmation()
                        ->modalHeading(
                            'Delete Selected Professors'
                        )
                        ->modalDescription(
                            'Only professors who are not assigned '
                            . 'to subjects can be deleted.'
                        )
                        ->modalSubmitActionLabel('Yes, Delete')
                        ->before(
                            function (
                                DeleteBulkAction $action,
                                Collection $selectedRecords
                            ): void {
                                /*
                                 * استخراج الدكاترة المرتبطين بمواد
                                 * من بين العناصر المحددة.
                                 */
                                $linkedProfessors =
                                    $selectedRecords->filter(
                                        fn (User $record): bool =>
                                            $record
                                                ->subjects()
                                                ->exists()
                                    );

                                if ($linkedProfessors->isEmpty()) {
                                    return;
                                }

                                $professorNames =
                                    $linkedProfessors
                                        ->pluck('name')
                                        ->implode(', ');

                                Notification::make()
                                    ->danger()
                                    ->title(
                                        'Selected Professors '
                                        . 'Cannot Be Deleted'
                                    )
                                    ->body(
                                        'These professors are assigned '
                                        . 'to subjects: '
                                        . $professorNames
                                        . '. Reassign or remove their '
                                        . 'subjects first.'
                                    )
                                    ->seconds(5)
                                    ->send();

                                /*
                                 * إيقاف الحذف الجماعي كله حتى لا
                                 * تُحذف بعض العناصر وتفشل البقية.
                                 */
                                $action->halt();
                            }
                        )
                        ->successNotificationTitle(
                            'Selected professors deleted successfully.'
                        ),
                ]),
            ])

            ->emptyStateHeading('No professors added yet')

            ->emptyStateDescription(
                'Add the first professor to begin assigning subjects.'
            )

            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}