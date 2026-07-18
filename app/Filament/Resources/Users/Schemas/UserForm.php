<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Professor Information')
                    ->description(
                        'Enter the personal and login information for the professor.'
                    )
                    ->schema([

                        TextInput::make('name')
                            ->label('Full Name')
                            ->placeholder('Enter professor name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->placeholder('professor@university.edu')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('password')
                            ->label('Password')
                            ->placeholder('Enter a secure password')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->required(fn (string $operation): bool =>
                                $operation === 'create'
                            )
                            ->dehydrated(fn (?string $state): bool =>
                                filled($state)
                            )
                            ->minLength(8)
                            ->maxLength(255)
                            ->helperText(
                                'Leave this field empty when editing to keep the current password.'
                            ),

                        Hidden::make('role')
                            ->default('professor'),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}