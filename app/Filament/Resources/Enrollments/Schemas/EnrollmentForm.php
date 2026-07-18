<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use App\Models\Student;
use App\Models\Subject;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Enrollment Information')
                    ->description(
                        'Select a student and the subject they should be enrolled in.'
                    )
                    ->schema([
                        Select::make('student_id')
                            ->label('Student')
                            ->options(
                                fn (): array => Student::query()
                                    ->with('user')
                                    ->get()
                                    ->sortBy('user.name')
                                    ->mapWithKeys(
                                        fn (Student $student): array => [
                                            $student->id =>
                                                $student->user->name
                                                .' — '
                                                .$student->university_number,
                                        ]
                                    )
                                    ->all()
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),

                        Select::make('subject_id')
                            ->label('Subject')
                            ->options(
                                fn (): array => Subject::query()
                                    ->orderBy('subject_name')
                                    ->get()
                                    ->mapWithKeys(
                                        fn (Subject $subject): array => [
                                            $subject->id =>
                                                $subject->subject_name
                                                .' — '
                                                .$subject->subject_code,
                                        ]
                                    )
                                    ->all()
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}