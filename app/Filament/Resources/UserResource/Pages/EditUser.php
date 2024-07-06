<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\UserResource;
use Filament\Actions;

class EditUser extends EditRedirect
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
