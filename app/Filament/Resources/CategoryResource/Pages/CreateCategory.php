<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\CategoryResource;
use Filament\Actions;

class CreateCategory extends CreateRedirect
{
    protected static string $resource = CategoryResource::class;
}
