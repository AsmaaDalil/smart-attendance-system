<?php

namespace App\Filament\Resources\AttendanceRecords\Schemas;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Student;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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
                                        fn (
                                            AttendanceSession $session
                                        ): array => [
                                            $session->id =>
                                                $session
                                                    ->subject
                                                    ->subject_name
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
                            ->required()

                            /*
                             * منع إنشاء سجلين للطالب نفسه
                             * في جلسة الحضور نفسها.
                             */
                            ->rules([
                                fn (
                                    Get $get,
                                    ?AttendanceRecord $record
                                ): Closure =>
                                    function (
                                        string $attribute,
                                        mixed $value,
                                        Closure $fail
                                    ) use (
                                        $get,
                                        $record
                                    ): void {
                                        $studentId =
                                            $get('student_id');

                                        if (
                                            blank($studentId)
                                            || blank($value)
                                        ) {
                                            return;
                                        }

                                        $query =
                                            AttendanceRecord::query()
                                                ->where(
                                                    'student_id',
                                                    $studentId
                                                )
                                                ->where(
                                                    'session_id',
                                                    $value
                                                );

                                        /*
                                         * أثناء التعديل نتجاهل
                                         * السجل الحالي نفسه.
                                         */
                                        if ($record !== null) {
                                            $query->where(
                                                'id',
                                                '!=',
                                                $record->getKey()
                                            );
                                        }

                                        if ($query->exists()) {
                                            $fail(
                                                'An attendance record '
                                                .'already exists for this '
                                                .'student in the selected '
                                                .'session. Open the existing '
                                                .'record and edit it instead.'
                                            );
                                        }
                                    },
                            ]),

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
                                'Enable when attendance is approved '
                                .'automatically for a dormitory student.'
                            )
                            ->default(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}