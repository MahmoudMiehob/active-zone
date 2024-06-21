<?php

namespace App\Filament\Superadmin\Resources\UsersResource\Pages;

use App\Filament\Superadmin\Resources\UsersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUsers extends CreateRecord
{
    protected static string $resource = UsersResource::class;
}
