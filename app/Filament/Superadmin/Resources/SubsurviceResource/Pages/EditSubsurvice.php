<?php

namespace App\Filament\Superadmin\Resources\SubsurviceResource\Pages;

use App\Filament\Superadmin\Resources\SubsurviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubsurvice extends EditRecord
{
    protected static string $resource = SubsurviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
