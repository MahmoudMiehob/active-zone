<?php

namespace App\Filament\Superadmin\Resources\WelcomeResource\Pages;

use App\Filament\Superadmin\Resources\WelcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWelcome extends CreateRecord
{
    protected static string $resource = WelcomeResource::class;
}
