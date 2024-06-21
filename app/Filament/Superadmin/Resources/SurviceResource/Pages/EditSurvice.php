<?php

namespace App\Filament\Superadmin\Resources\SurviceResource\Pages;

use App\Filament\Superadmin\Resources\SurviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurvice extends EditRecord
{
    protected static string $resource = SurviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
