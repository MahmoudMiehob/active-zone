<?php

namespace App\Filament\Superadmin\Resources\ApplicationRatingResource\Pages;

use App\Filament\Superadmin\Resources\ApplicationRatingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplicationRatings extends ListRecords
{
    protected static string $resource = ApplicationRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
