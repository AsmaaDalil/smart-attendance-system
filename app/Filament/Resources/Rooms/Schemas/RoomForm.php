<?php

namespace App\Filament\Resources\Rooms\Schemas;

use App\Filament\Forms\Components\CurrentLocationButton;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Room Information')
                    ->description(
                        'Enter the room name, GPS coordinates, and allowed attendance radius.'
                    )
                    ->schema([

                        TextInput::make('room_name')
                            ->label('Room Name')
                            ->placeholder(
                                'Example: Lecture Hall 3'
                            )
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        /*
                         * هذا الحقل يعرض زر تحديد الموقع فقط،
                         * ولا يتم حفظه في قاعدة البيانات.
                         */
                        CurrentLocationButton::make(
                            'current_location'
                        )
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->placeholder('35.93120000')
                            ->required()
                            ->numeric()
                            ->rules([
                                'numeric',
                                'between:-90,90',
                            ])
                            ->step(0.00000001)
                            ->hintAction(
                                Action::make('openGoogleMaps')
                                    ->label('Open Google Maps')
                                    ->url(
                                        'https://www.google.com/maps'
                                    )
                                    ->openUrlInNewTab()
                            )
                            ->helperText(
                                'The first coordinate represents latitude.'
                            ),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->placeholder('36.63410000')
                            ->required()
                            ->numeric()
                            ->rules([
                                'numeric',
                                'between:-180,180',
                            ])
                            ->step(0.00000001)
                            ->helperText(
                                'The second coordinate represents longitude.'
                            ),

                        TextInput::make('allowed_radius')
                            ->label(
                                'Allowed Attendance Radius'
                            )
                            ->placeholder('30')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->rules([
                                'integer',
                                'min:1',
                                'max:1000',
                            ])
                            ->default(30)
                            ->suffix('meters')
                            ->helperText(
                                'Students outside this distance cannot register attendance.'
                            ),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}