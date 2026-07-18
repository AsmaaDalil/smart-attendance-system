<?php

namespace App\Filament\Resources\Rooms\Tables;

use App\Models\Room;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('room_name')
                    ->label('Room Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('latitude')
                    ->label('Latitude')
                    ->copyable(),

                TextColumn::make('longitude')
                    ->label('Longitude')
                    ->copyable(),

                TextColumn::make('allowed_radius')
                    ->label('Allowed Radius')
                    ->suffix(' meters')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('sessions_count')
                    ->label('Sessions')
                    ->counts('sessions')
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
                    ->visible(
                        fn (Room $record): bool =>
                            ! $record->sessions()->exists()
                    ),

            ])

            ->emptyStateHeading('No rooms added yet')

            ->emptyStateDescription(
                'Add the first room and specify its GPS location.'
            )

            ->emptyStateIcon('heroicon-o-map-pin');
    }
}