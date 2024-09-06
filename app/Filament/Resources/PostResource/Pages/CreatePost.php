<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\PostResource;
use Filament\Actions;

class CreatePost extends CreateRedirect
{
    protected static string $resource = PostResource::class;
}
