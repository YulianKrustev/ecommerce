<?php

namespace App\Filament\Resources\ColorResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\ColorResource;
use Filament\Actions;

class CreateColor extends CreateRedirect
{
    protected static string $resource = ColorResource::class;
}
