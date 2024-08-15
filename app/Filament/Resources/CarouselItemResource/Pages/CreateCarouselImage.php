<?php

namespace App\Filament\Resources\CarouselItemResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\CarouselImagesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCarouselImage extends CreateRedirect
{
    protected static string $resource = CarouselImagesResource::class;
}
