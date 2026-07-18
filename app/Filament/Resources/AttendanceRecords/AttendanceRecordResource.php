<?php

namespace App\Filament\Resources\AttendanceRecords;

use App\Filament\Resources\AttendanceRecords\Pages\CreateAttendanceRecord;
use App\Filament\Resources\AttendanceRecords\Pages\EditAttendanceRecord;
use App\Filament\Resources\AttendanceRecords\Pages\ListAttendanceRecords;
use App\Filament\Resources\AttendanceRecords\Schemas\AttendanceRecordForm;
use App\Filament\Resources\AttendanceRecords\Tables\AttendanceRecordsTable;
use App\Models\AttendanceRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AttendanceRecordResource extends Resource
{
    protected static ?string $model =
        AttendanceRecord::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel =
        'Attendance';

    protected static ?string $modelLabel =
        'Attendance Record';

    protected static ?string $pluralModelLabel =
        'Attendance Records';

    protected static string|UnitEnum|null $navigationGroup =
        'Attendance Management';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute =
        'id';

    public static function form(Schema $schema): Schema
    {
        return AttendanceRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceRecords::route('/'),

            'create' => CreateAttendanceRecord::route(
                '/create'
            ),

            'edit' => EditAttendanceRecord::route(
                '/{record}/edit'
            ),
        ];
    }
}