<?php

namespace App\Filament\Superadmin\Resources\CountryResource\Pages;

use App\Filament\Superadmin\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;
}
