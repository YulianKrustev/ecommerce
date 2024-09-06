<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\ContactMessageResource;
use Filament\Actions;

class EditContactMessage extends EditRedirect
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
