<?php

namespace App\Filament\Resources\Excuses;

use App\Filament\Resources\Excuses\Pages\CreateExcuse;
use App\Filament\Resources\Excuses\Pages\EditExcuse;
use App\Filament\Resources\Excuses\Pages\ListExcuses;
use App\Filament\Resources\Excuses\Schemas\ExcuseForm;
use App\Filament\Resources\Excuses\Tables\ExcusesTable;
use App\Models\Excuse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ExcuseResource extends Resource
{
    protected static ?string $model =
        Excuse::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-document-text';

    protected static ?string $navigationLabel =
        'Excuses';

    protected static ?string $modelLabel =
        'Excuse';

    protected static ?string $pluralModelLabel =
        'Excuses';

    protected static string|UnitEnum|null $navigationGroup =
        'Academic Management';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute =
        'id';

    public static function form(Schema $schema): Schema
    {
        return ExcuseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExcusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExcuses::route('/'),
            'create' => CreateExcuse::route('/create'),
            'edit' => EditExcuse::route('/{record}/edit'),
        ];
    }
}