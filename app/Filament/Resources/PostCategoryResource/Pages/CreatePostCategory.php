<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePostCategory extends CreateRedirect
{
    protected static string $resource = PostCategoryResource::class;
}
