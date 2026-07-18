<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Student;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Account Information')
                    ->description(
                        'Login information used by the student.'
                    )
                    ->schema([

                        TextInput::make('name')
                            ->label('Full Name')
                            ->placeholder('Enter student name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->placeholder('student@university.edu')
                            ->email()
                            ->required()
                            ->rules(
                                fn (?Student $record): array => [
                                    Rule::unique('users', 'email')
                                        ->ignore($record?->user_id),
                                ]
                            )
                            ->maxLength(255),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->required(
                                fn (string $operation): bool =>
                                    $operation === 'create'
                            )
                            ->minLength(8)
                            ->maxLength(255)
                            ->helperText(
                                'Leave empty while editing to keep the current password.'
                            ),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Academic Information')
                    ->description(
                        'University and contact information for the student.'
                    )
                    ->schema([

                        TextInput::make('university_number')
                            ->label('University Number')
                            ->placeholder('20260001')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Select::make('academic_year')
                            ->label('Academic Year')
                            ->options([
                                1 => 'First Year',
                                2 => 'Second Year',
                                3 => 'Third Year',
                                4 => 'Fourth Year',
                                5 => 'Fifth Year',
                            ])
                            ->native(false)
                            ->required(),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->placeholder('09XXXXXXXX')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('address')
                            ->label('Address')
                            ->placeholder('Enter student address')
                            ->maxLength(255),

                        Toggle::make('is_dormitory')
                            ->label('University Dormitory Student')
                            ->helperText(
                                'Enable this option if the student lives in university housing.'
                            )
                            ->default(false),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}