<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\CreateRedirect;
use App\Filament\Resources\OrderResource;

class CreateOrder extends CreateRedirect
{
    protected static string $resource = OrderResource::class;
}
