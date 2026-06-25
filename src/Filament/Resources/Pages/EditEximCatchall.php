<?php

namespace VEximweb\Core\EximCatchall\Filament\Resources\Pages;

use VEximweb\Core\EximCatchall\Filament\Resources\EximCatchallResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEximCatchall extends EditRecord
{
    protected static string $resource = EximCatchallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
