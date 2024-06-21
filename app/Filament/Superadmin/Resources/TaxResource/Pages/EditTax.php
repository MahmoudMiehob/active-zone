<?php

namespace App\Filament\Superadmin\Resources\TaxResource\Pages;

use App\Filament\Superadmin\Resources\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTax extends EditRecord
{
    protected static string $resource = TaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
