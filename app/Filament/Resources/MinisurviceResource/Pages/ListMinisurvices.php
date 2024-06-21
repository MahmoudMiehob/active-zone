<?php

namespace App\Filament\Resources\MinisurviceResource\Pages;

use App\Filament\Resources\MinisurviceResource;
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
