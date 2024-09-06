<?php

namespace App\Filament\Resources\CarouselItemResource\Pages;

use App\Filament\Resources\CarouselImagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarouselImage extends ListRecords
{
    protected static string $resource = CarouselImagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Image'),
        ];
    }
}
