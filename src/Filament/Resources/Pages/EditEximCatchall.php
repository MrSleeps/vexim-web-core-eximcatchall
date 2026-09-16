<?php

namespace VEximweb\Core\EximCatchall\Filament\Resources\Pages;

use VEximweb\Core\EximCatchall\Filament\Resources\EximCatchallResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEximCatchall extends EditRecord
{
    protected static string $resource = EximCatchallResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return EximCatchallResource::prepareForwardingDestinationForForm($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return EximCatchallResource::processForwardingDestination($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
