<?php

namespace App\Filament\Superadmin\Resources\MinisurviceResource\Pages;

use App\Filament\Superadmin\Resources\MinisurviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMinisurvices extends ListRecords
{
    protected static string $resource = MinisurviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
