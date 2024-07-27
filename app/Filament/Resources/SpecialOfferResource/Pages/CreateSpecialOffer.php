<?php

namespace App\Filament\Resources\SpecialOfferResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\SpecialOfferResource;

class CreateSpecialOffer extends CreateRedirect
{
    protected static string $resource = SpecialOfferResource::class;

    protected function afterCreate(): void
    {
        $this->record->applySpecialOffer();
    }
}
