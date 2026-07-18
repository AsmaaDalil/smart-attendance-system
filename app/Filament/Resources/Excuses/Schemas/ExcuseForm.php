<?php

namespace App\Filament\Resources\Excuses\Schemas;

use App\Models\AttendanceRecord;
use App\Models\Excuse;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ExcuseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Excuse Information')
                    ->description(
                        'Review or create an excuse for an absent student.'
                    )
                    ->schema([
                        Select::make('attendance_record_id')
                            ->label('Absent Attendance Record')
                            ->options(
                                function (?Excuse $record): array {
                                    return AttendanceRecord::query()
                                        ->with([
                                            'student.user',
                                            'session.subject',
                                        ])
                                        ->whereIn(
                                            'status',
                                            ['Absent', 'Excused']
                                        )
                                        ->where(
                                            function (
                                                Builder $query
                                            ) use ($record): void {
                                                $query
                                                    ->whereDoesntHave(
                                                        'excuse'
                                                    )
                                                    ->when(
                                                        $record,
                                                        fn (
                                                            Builder $query
                                                        ) => $query
                                                            ->orWhereKey(
                                                                $record
                                                                    ->attendance_record_id
                                                            )
                                                    );
                                            }
                                        )
                                        ->latest()
                                        ->get()
                                        ->mapWithKeys(
                                            function (
                                                AttendanceRecord $attendance
                                            ): array {
                                                $studentName =
                                                    $attendance
                                                        ->student
                                                        ->user
                                                        ->name;

                                                $universityNumber =
                                                    $attendance
                                                        ->student
                                                        ->university_number;

                                                $subjectName =
                                                    $attendance
                                                        ->session
                                                        ->subject
                                                        ->subject_name;

                                                $lectureTitle =
                                                    $attendance
                                                        ->session
                                                        ->lecture_title;

                                                return [
                                                    $attendance->id =>
                                                        $studentName
                                                        .' — '
                                                        .$universityNumber
                                                        .' — '
                                                        .$subjectName
                                                        .' — '
                                                        .$lectureTitle,
                                                ];
                                            }
                                        )
                                        ->all();
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->disabledOn('edit')
                            ->dehydrated(),

                        Textarea::make('reason')
                            ->label('Excuse Reason')
                            ->placeholder(
                                'Enter the reason for the student absence.'
                            )
                            ->rows(5)
                            ->required()
                            ->maxLength(2000)
                            ->columnSpanFull(),

                        FileUpload::make('file_path')
                            ->label('Medical Report or Attachment')
                            ->disk('public')
                            ->directory('excuses')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(5120)
                            ->downloadable()
                            ->openable()
                            ->helperText(
                                'Accepted files: PDF, JPG, PNG or WEBP. Maximum size: 5 MB.'
                            )
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Excuse Status')
                            ->options([
                                'Pending' => 'Pending',
                                'Approved' => 'Approved',
                                'Rejected' => 'Rejected',
                            ])
                            ->default('Pending')
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}