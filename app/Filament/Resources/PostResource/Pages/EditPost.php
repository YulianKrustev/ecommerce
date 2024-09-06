<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\PostResource;
use Filament\Actions;

class EditPost extends EditRedirect
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
