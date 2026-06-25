<?php

namespace VEximweb\Core\EximCatchall\Filament\Resources\Pages;

use VEximweb\Core\EximCatchall\Filament\Resources\EximCatchallResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\ListRecords;

class ListEximCatchall extends ListRecords
{
    protected static string $resource = EximCatchallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
