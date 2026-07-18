<?php

namespace App\Filament\Resources\AttendanceRecords\Schemas;

use App\Models\AttendanceSession;
use App\Models\Student;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance Information')
                    ->description(
                        'Select the student, lecture session and attendance status.'
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

                        Select::make('session_id')
                            ->label('Attendance Session')
                            ->options(
                                fn (): array => AttendanceSession::query()
                                    ->with('subject')
                                    ->latest('start_time')
                                    ->get()
                                    ->mapWithKeys(
                                        fn (AttendanceSession $session): array => [
                                            $session->id =>
                                                $session->subject->subject_name
                                                .' — Lecture '
                                                .$session->lecture_number
                                                .' — '
                                                .$session->lecture_title,
                                        ]
                                    )
                                    ->all()
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),

                        Select::make('status')
                            ->label('Attendance Status')
                            ->options([
                                'Present' => 'Present',
                                'Late' => 'Late',
                                'Absent' => 'Absent',
                                'Excused' => 'Excused',
                            ])
                            ->default('Absent')
                            ->native(false)
                            ->required(),

                        DateTimePicker::make('scanned_at')
                            ->label('Scanned At')
                            ->seconds()
                            ->native(false),

                        TextInput::make('distance_meters')
                            ->label('Distance From Room')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('meters')
                            ->placeholder('Example: 12.50'),

                        Toggle::make('is_dorm_approved')
                            ->label('Dormitory Approved')
                            ->helperText(
                                'Enable when attendance is approved automatically for a dormitory student.'
                            )
                            ->default(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}