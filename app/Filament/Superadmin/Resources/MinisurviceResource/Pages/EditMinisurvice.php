<?php

namespace App\Filament\Superadmin\Resources\MinisurviceResource\Pages;

use App\Filament\Superadmin\Resources\MinisurviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMinisurvice extends EditRecord
{
    protected static string $resource = MinisurviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
