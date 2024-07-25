<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->sortable()
                    ->searchable()
                    ->badge(),


                TextColumn::make('status')
                    ->label('Order Status')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state):string => match($state)
                    {
                        'new' => 'primary',
                        'processing' => 'danger',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->icon(fn(string $state):string => match($state)
                    {
                        'new' => 'heroicon-m-shopping-cart',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                    }),

                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('BGN')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->sortable()
                    ->dateTime(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Edit')
                        ->url(fn(Order $record) => OrderResource::getUrl('edit', ['record' => $record]))
                        ->icon('heroicon-s-pencil-square'),
                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
