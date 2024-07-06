<?php

namespace App\Filament;

use Filament\Resources\Pages\CreateRecord;

class CreateRedirect extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
