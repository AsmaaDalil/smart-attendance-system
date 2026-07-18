<?php

namespace App\Filament\Resources\Subjects\Schemas;

use App\Models\Student;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Subject Information')
                    ->description(
                        'Enter the subject details and assign the responsible professor.'
                    )
                    ->schema([

                        TextInput::make('subject_name')
                            ->label('Subject Name')
                            ->placeholder(
                                'Example: Data Security'
                            )
                            ->required()
                            ->maxLength(255),

                        TextInput::make('subject_code')
                            ->label('Subject Code')
                            ->placeholder('Example: CS401')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->helperText(
                                'The subject code must be unique.'
                            ),

                        Select::make('user_id')
                            ->label('Responsible Professor')
                            ->relationship(
                                name: 'professor',
                                titleAttribute: 'name',
                                modifyQueryUsing:
                                    fn (Builder $query): Builder =>
                                        $query->where(
                                            'role',
                                            'professor'
                                        )
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->helperText(
                                'Only professor accounts are displayed.'
                            )
                            ->columnSpanFull(),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Student Enrollment')
                    ->description(
                        'Select the students enrolled in this subject.'
                    )
                    ->schema([

                        Select::make('students')
                            ->label('Enrolled Students')
                            ->relationship(
                                name: 'students',
                                titleAttribute:
                                    'university_number'
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (Student $record): string =>
                                    $record->user->name
                                    . ' — '
                                    . $record->university_number
                            )
                            ->multiple()
                            ->searchable([
                                'university_number',
                            ])
                            ->preload()
                            ->native(false)
                            ->helperText(
                                'You can select more than one student.'
                            )
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

            ]);
    }
}