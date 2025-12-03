<?php

namespace App\Filament\Resources\Instances\Pages;

use App\Filament\Resources\Instances\InstancesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstances extends EditRecord
{
    protected static string $resource = InstancesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
