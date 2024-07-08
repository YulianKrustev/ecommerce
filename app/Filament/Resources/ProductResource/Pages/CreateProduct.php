<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRedirect
{
    protected static string $resource = ProductResource::class;
}
