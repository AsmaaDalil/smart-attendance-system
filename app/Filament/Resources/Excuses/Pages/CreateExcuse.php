<?php

namespace App\Filament\Resources\Excuses\Pages;

use App\Filament\Resources\Excuses\ExcuseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExcuse extends CreateRecord
{
    protected static string $resource = ExcuseResource::class;
        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
