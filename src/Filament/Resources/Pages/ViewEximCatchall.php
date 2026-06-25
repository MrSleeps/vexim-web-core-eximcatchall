<?php

namespace VEximweb\Core\EximCatchall\Filament\Resources\Pages;

use VEximweb\Core\EximCatchall\Filament\Resources\EximCatchallResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEximCatchall extends ViewRecord
{
    protected static string $resource = EximCatchallResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}