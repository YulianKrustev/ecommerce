<?php

namespace App\Filament\Resources\CarouselItemResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\CarouselImagesResource;
use Filament\Actions;

class EditCarouselImage extends EditRedirect
{
    protected static string $resource = CarouselImagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
