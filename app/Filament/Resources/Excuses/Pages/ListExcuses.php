<?php

namespace App\Filament\Resources\Excuses\Pages;

use App\Filament\Resources\Excuses\ExcuseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExcuses extends ListRecords
{
    protected static string $resource = ExcuseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
