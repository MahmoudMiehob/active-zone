<?php

namespace App\Filament\Superadmin\Resources\SubsurviceResource\Pages;

use App\Filament\Superadmin\Resources\SubsurviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubsurvices extends ListRecords
{
    protected static string $resource = SubsurviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
