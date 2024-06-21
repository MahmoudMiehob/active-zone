<?php

namespace App\Filament\Superadmin\Resources\SurviceResource\Pages;

use App\Filament\Superadmin\Resources\SurviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurvices extends ListRecords
{
    protected static string $resource = SurviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
