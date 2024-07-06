<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\UserResource;
use Filament\Actions;

class CreateUser extends CreateRedirect
{
    protected static string $resource = UserResource::class;
}
