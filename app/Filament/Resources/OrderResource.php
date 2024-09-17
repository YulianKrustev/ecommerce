<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;

use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?int $navigationSort = 2;

    public static function getGloballySearchableAttributes(): array
    {
        return ['id', 'user.name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->id;
    }

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([

                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->placeholder('Select a customer'),

                        Select::make('payment_method')
                            ->label('Payment Type')
                            ->required()
                            ->dehydrated()
                            ->disabled()
                            ->default('stripe')
                            ->options([
                                'stripe' => 'Stripe',
//                                'cod' => 'Cash on delivery',
                            ]),

                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->required()
                            ->default('pending')
                            ->placeholder('Select a payment status'),

                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->colors([
                                'new' => 'primary',
                                'processing' => 'danger',
                                'shipped' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-shopping-cart',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ]),

                        Hidden::make('currency')
                            ->default('EUR')
                            ->required()
                            ->disabled()
                            ->dehydrated(),

                        Textarea::make('notes')
                            ->rows(7)
                            ->columnSpanFull(),
                    ])->columns(2),

                    Section::make('Order Products')->schema([
                        Repeater::make('items')
                            ->label('Products')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(3)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = Product::find($state);
                                        $set('unit_price', $product ? $product->price : 0);
                                        $set('total_units_price', $product ? $product->price * $get('quantity') : 0);
                                    }),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $unitPrice = $get('unit_price');
                                        $totalUnitsPrice = $unitPrice * $state;
                                        $set('total_units_price', $totalUnitsPrice);

//                                        // Update product quantity
//                                        $productId = $get('product_id');
//                                        if ($productId) {
//                                            $product = Product::find($productId);
//                                            if ($product) {
//                                                $product->quantity -= $state;
//                                                $product->save();
//                                            }
//                                        }
                                    }),

                                TextInput::make('unit_price')
                                    ->prefix('EUR')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3),

                                TextInput::make('total_units_price')
                                    ->prefix('EUR')
                                    ->label('Total Sum')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3),

                                ViewField::make('first_image_display')
                                    ->view('components.product-image-view')
                                    ->columnSpan(2)
                                    ->formatStateUsing(function ($get) {
                                        $productId = $get('product_id');
                                        $product = Product::find($productId);
                                        return $product ? $product->first_image : null;
                                    }),


                            ])
                            ->disabled()
                            ->columns(12)
                            ->addActionLabel('Add new product'),

                        Placeholder::make('Subtotal')
                            ->label('Subtotal:')
                            ->extraAttributes(['style' => 'font-size: 1.5rem; font-weight: 500;'])
                            ->content(function (Get $get) {
                                $discount = $get('discount');
                                $shipping = $get('shipping_cost');
                                $total = $get('total_price');

                                $subtotal = $total - $shipping + $discount;
                                return Number::currency($subtotal ?? 0, 'EUR');
                            }),


                        Placeholder::make('discount')
                            ->label('Discount:')
                            ->extraAttributes(['style' => 'font-size: 1.5rem; font-weight: 500;'])
                            ->content(function (Get $get) {

                                return Number::currency($get('discount') ?? 0, 'EUR');
                            }),


                        Placeholder::make('shipping_cost')
                            ->label('Shipping Cost:')
                            ->extraAttributes(['style' => 'font-size: 1.5rem; font-weight: 500;'])
                            ->content(function (Get $get) {
                                $shipping_cost = $get('shipping_cost');

                                return Number::currency($shipping_cost, 'EUR');
                            }),

                        Placeholder::make('total_price')
                            ->label('Total Price:')
                            ->extraAttributes(['style' => 'font-size: 1.5rem; font-weight: 500; text-decoration: underline;'])
                            ->content(function (Get $get, Set $set) {

                                if ( $get('total_price') > 0) {
                                    return Number::currency($get('total_price'), 'EUR');
                                }

                                $total = 0;
                                $repeaters = $get('items');
                                $shipping_cost = $get('shipping_cost');

                                if (!$repeaters) {
                                    return $total;
                                }

                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("items.{$key}.total_units_price");
                                }

                                $set("total_price", $total);

                                return Number::currency($total + $shipping_cost, 'EUR');
                            })
                    ]),

                    Hidden::make('total_price')
                        ->default(0),

                    Hidden::make('shipping_cost')
                        ->default(0)

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

//                TextColumn::make('payment_method')
//                    ->label('Payment type')
//                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        // Capitalize the first letter of the payment status
                        return ucfirst($state);
                    })
                    ->color(function ($state) {
                        // Set badge color based on the payment status
                        return match ($state) {
                            'paid' => 'success',  // Green badge for paid
                            'failed' => 'danger',  // Red badge for failed
                            default => 'primary',  // Default color for other statuses
                        };
                    }),


                TextColumn::make('total_price')
                    ->label('Price')
                    ->numeric()
                    ->sortable()
                    ->money('EUR'),

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()

            ])->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->label('Delete'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
           AddressRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $newCount = static::getModel()::where('status', 'new')->count();

        return $newCount > 0 ? (string)$newCount : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
