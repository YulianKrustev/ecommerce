<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;



class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->color("primary")
                ->validateUsing([
                    'name' => 'required',
                    'slug' => 'required',
                    'description' => 'required',
                    'price' => 'required|numeric',
                    'quantity' => 'required|numeric',
                ])
                ->processCollectionUsing(function (string $modelClass, \Illuminate\Support\Collection $collection) {

                    $collection->each(function ($row) use ($modelClass) {
                        $modelClass::insert([
                            'name' => $row['name'],
                            'slug' => $row['name'].rand(1,100),
                            'description' => $row['description'],
                            'price' => $row['price'],
                            'quantity' => $row['quantity'],
                            'is_active' =>  0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    });
                    Notification::make()
                        ->title('Import Successful')
                        ->body('All products have been successfully imported.')
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make()
            ->label('New Product')
        ];
    }
}
