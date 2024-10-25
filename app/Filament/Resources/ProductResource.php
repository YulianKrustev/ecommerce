<?php

namespace App\Filament\Resources;

use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Tag;
use Faker\Core\Number;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\ColorEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Create New Product')
                    ->tabs([
                        Tab::make('Create Product')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Section::make('Product Info')
                                            ->columns([
                                                'sm' => 2,
                                                'xl' => 4,
                                                '2xl' => 6,
                                            ])
                                            ->schema([
                                                TextInput::make('name')
                                                    ->columnSpan(3)
                                                    ->required()
                                                    ->maxLength(60)
                                                    ->live()
                                                    ->afterStateUpdated(function (
                                                        string $operation,
                                                        ?string $state,
                                                        Forms\Set $set
                                                    ) {
                                                        if ($operation == 'edit' || $state == null) {
                                                            return;
                                                        }
                                                        $set('meta_title',
                                                            ASCII::to_ascii($state) . " | " . config("app.name"));
                                                        $set('slug', ASCII::to_ascii(Str::slug($state)));
                                                        $set('image_alt', $state);
                                                    }),

                                                TextInput::make('sku')
                                                    ->columnSpan(3)
                                                    ->maxLength(60),

                                                TextInput::make('slug')
                                                    ->columnSpan(3)
                                                    ->required()
                                                    ->maxLength(60)
                                                    ->unique(ignoreRecord: true)
                                                    ->live(debounce: 500)
                                                    ->hint(function ($state) {
                                                        $maxCount = 50;
                                                        $charactersCount = mb_strlen($state);
                                                        $leftCharacters = $maxCount - $charactersCount;
                                                        return 'Characters left: ' . $leftCharacters;
                                                    }),

                                                RichEditor::make('description')
                                                    ->disableToolbarButtons([
                                                        'attachFiles',
                                                        'codeBlock',
                                                        'strike',
                                                        'underline',
                                                        'blockquote',
                                                    ])
                                                    ->required()
                                                    ->columnSpanFull()
                                                    ->live(debounce: 500)
                                                    ->hint(function ($state) {
                                                        $charactersCount = mb_strlen($state);

                                                        return 'Characters: ' . $charactersCount;
                                                    })
                                                    ->afterStateUpdated(function (
                                                        string $operation,
                                                        ?string $state,
                                                        Forms\Set $set
                                                    ) {
                                                        if ($operation == 'edit' || $state == null) {
                                                            return;
                                                        }
                                                        $plainTextState = strip_tags($state);
                                                        $truncatedState = mb_substr($plainTextState, 0, 133);
                                                        $lastSpacePosition = mb_strrpos($truncatedState, ' ');

                                                        if ($lastSpacePosition !== false) {
                                                            $truncatedState = mb_substr($truncatedState, 0,
                                                                $lastSpacePosition);
                                                        }
                                                        $truncatedState = rtrim($truncatedState);
                                                        $truncatedState .= '...FREE NEXT DAY DELIVERY';
                                                        $set('meta_description', $truncatedState);
                                                    }),
                                            ]),

                                        Section::make('Attribute')->schema([
                                            Select::make('color_id')
                                                ->label('Color')
                                                ->allowHtml()
                                                ->native(false)
                                                ->required()
                                                ->createOptionForm([
                                                    TextInput::make('name')
                                                        ->required()
                                                        ->unique(ignoreRecord: true),

                                                    ColorPicker::make('hex_code')
                                                        ->required()

                                                ])
                                                ->createOptionUsing(function (Select $component, array $data) {
                                                    $color = Color::create($data);
                                                    return $color->id;
                                                })
                                                ->options(
                                                    collect(Color::all())
                                                        ->mapWithKeys(static fn($color) => [
                                                            $color->id => "<span class='flex items-center gap-x-4'>
                                                            <span class='rounded-full w-4 h-4' style='background: $color->hex_code'></span>
                                                            <span>" . $color->name . '</span>
                                                            </span>',
                                                        ]),
                                                ),

                                            Repeater::make('productSizes')
                                                ->relationship('sizes')
                                                ->required()
                                                ->schema([
                                                    Select::make('size_id')
                                                        ->label('Size')
                                                        ->options(Size::all()->pluck('name',
                                                            'id'))
                                                        ->required()
                                                        ->searchable()
                                                        ->preload()
                                                        ->distinct()
                                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                        ->createOptionForm([
                                                            TextInput::make('name')
                                                                ->required()
                                                                ->label('Size ')
                                                                ->unique(ignoreRecord: true)
                                                        ])
                                                        ->createOptionUsing(function (Select $component, array $data) {
                                                            $size = Size::create($data);
                                                            return $size->id;
                                                        }),

                                                    TextInput::make('quantity')
                                                        ->label('Quantity')
                                                        ->numeric()
                                                        ->required()
                                                        ->minValue(0),
                                                ])
                                                ->columns(2)
                                                ->addActionLabel('Add Size'),
                                        ]),

                                        Section::make('Images')->schema([
                                            FileUpload::make('images')
                                                ->required()
                                                ->label('Upload Images')
                                                ->multiple()
                                                ->maxFiles(6)
                                                ->reorderable()
                                                ->columnSpanFull()
                                                ->optimize('webp')
                                                ->directory('products')
                                                ->imageEditor()
                                                ->panelLayout('grid')
                                                ->imagePreviewHeight('300')
//                                                ->afterUpload(function (array $files) {
//                                                    foreach ($files as $file) {
//                                                        // Save the original file
//                                                        $originalPath = $file->store('products/original');
//
//                                                        // Create a thumbnail
//                                                        $thumbnailPath = 'products/thumbnails/' . $file->hashName();
//
//                                                        // Use Intervention Image to create a thumbnail
//                                                        $image = Image::make($file->getRealPath());
//                                                        $image->resize(300, 300); // Set your desired thumbnail size
//                                                        $image->save(public_path($thumbnailPath));
//
//                                                        // Here you can save the paths to the database if needed
//                                                        // Example: $this->images()->create(['original' => $originalPath, 'thumbnail' => $thumbnailPath]);
//                                                    },
                                        ]),
                                    ])->columnSpan(3),

                                Group::make()->schema([
                                    Section::make('Prices')->schema([
                                        TextInput::make('price')
                                            ->numeric()
                                            ->required()
                                            ->prefix('EUR'),

                                        TextInput::make('on_sale_price')
                                            ->label('On Sale Price')
                                            ->numeric()
                                            ->prefix('EUR')
                                            ->dehydrated()
                                            ->disabled(),
                                    ]),

                                    Section::make('Categories and Tags')->schema([
                                        Select::make('categories')
                                            ->label('Categories')
                                            ->relationship('categories', 'name')
                                            ->preload()
                                            ->required()
                                            ->multiple()
                                            ->placeholder(''),

                                        Select::make('tags')
                                            ->relationship('tags', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->required()
                                            ->placeholder('')
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->label('Tag Name')
                                                    ->unique(ignoreRecord: true)
                                            ])
                                            ->createOptionUsing(function (Select $component, array $data) {
                                                $tag = Tag::create($data);
                                                return $tag->id;
                                            }),
                                    ]),

                                    Section::make('Status')->schema([
                                        Toggle::make('on_sale')
                                            ->label('Sale')
                                            ->dehydrated()
                                            ->disabled()
                                            ->required(),

                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->required()
                                            ->default(true),

                                        Toggle::make('in_stock')
                                            ->label('Stock')
                                            ->default(true)
                                            ->required(),

                                        Toggle::make('is_featured')
                                            ->label('Featured')
                                            ->required(),
                                    ])
                                ])->columnSpan(1),
                            ])->columns(4),

                        Tab::make('Color Variants')->schema([

                            Select::make('related_products')
                                ->label('Color Variants')
                                ->placeholder('Enter the product ID or Name')
                                ->allowHtml()
                                ->relationship('relatedProducts', 'name')
                                ->multiple()
                                ->distinct()
                                ->native(false)
                                ->searchable(['products.id', 'name'])
                                ->options(
                                    Product::all()->mapWithKeys(fn($product) => [
                                        $product->id => view('components.product-option', ['product' => $product])->render(),
                                    ])
                                )
                                ->getSearchResultsUsing(function (string $search) {
                                    return Product::where('name', 'like', "%{$search}%")
                                        ->orWhere('id', 'like', "%{$search}%")
                                        ->limit(10)
                                        ->get()
                                        ->mapWithKeys(function ($product) {
                                            return [
                                                $product->id => view('components.product-option', ['product' => $product])->render(),
                                            ];
                                        });
                                })
                                ->getOptionLabelUsing(function ($value) {
                                    $product = Product::find($value);
                                    if ($product) {
                                        return view('components.product-option', ['product' => $product])->render();
                                    }
                                    return 'Product Not Found';  // Fallback if the product is not found
                                })

                        ]),

                        Tab::make('SEO')->schema([
                            TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->required()
                                ->maxLength(60)
                                ->live(debounce: 500)
                                ->hint(function ($state) {
                                    $maxCount = 60;
                                    $charactersCount = mb_strlen($state);
                                    $leftCharacters = $maxCount - $charactersCount;
                                    return 'Characters left: ' . $leftCharacters;
                                }),

                            Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->required()
                                ->maxLength(160)
                                ->live(debounce: 500)
                                ->hint(function ($state) {
                                    $maxCount = 160;
                                    $charactersCount = mb_strlen($state);
                                    $leftCharacters = $maxCount - $charactersCount;
                                    return 'Characters left: ' . $leftCharacters;
                                }),

                            TagsInput::make('meta_keywords')
                                ->label('Keywords')
                                ->placeholder('Add New keyword')
                        ])
                    ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->alignCenter()
                    ->numeric(),

                ImageColumn::make('first_image')
                    ->alignCenter()
                    ->height(100)
                    ->width(100)
                    ->label('Image'),

                TextColumn::make('name')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('categories')
                    ->alignCenter()
                    ->label('Category')
                    ->getStateUsing(function ($record) {
                        return $record->categories->pluck('name')->join(', ');
                    }),

                IconColumn::make('on_sale')
                    ->alignCenter()
                    ->label('Sale')
                    ->boolean(),

                IconColumn::make('in_stock')
                    ->alignCenter()
                    ->label('Stock')
                    ->boolean(),


                TextColumn::make('price')
                    ->alignCenter()
                    ->money('EUR')
                    ->sortable(),

                SelectColumn::make('is_active')
                    ->alignCenter()
                    ->label('Active')
                    ->selectablePlaceholder(false)
                    ->options([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ])
                    ->sortable(),
            ])->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->relationship('categories', 'name'),

                SelectFilter::make('is_active')
                    ->label('Active')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ]),

                SelectFilter::make('on_sale')
                    ->label('Sale')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ]),

                SelectFilter::make('in_stock')
                    ->label('Stock')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(function (Tables\Actions\DeleteAction $action, Product $record) {
                            if ($record->orderItems()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title('Failed to delete!')
                                    ->body('This product is used in orders and cannot be deleted.')
                                    ->send();

                                $action->cancel();
                            }
                        }),
                    ReplicateAction::make()
                        ->beforeReplicaSaved(function (Model $replica, Model $record): void {
                            $replica->slug = $record->slug . "-" . rand(1, 100);
                            $replica->images = null;
                            $replica->on_sale = 0;
                            $replica->on_sale_price = null;
                            $replica->offer_id = null;
                            $replica->color_id = null;
                            $replica->is_active = false;
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make()->before(function (DeleteBulkAction $action, $records) {
                        $shouldCancel = false;

                        foreach ($records as $record) {
                            if ($record->orderItems()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title("Failed to delete $record->name!")
                                    ->body('This product is used in orders and cannot be deleted.')
                                    ->send();

                                $shouldCancel = true;
                            }
                        }

                        if ($shouldCancel) {
                            $action->cancel();
                        }
                    }),
                ])->label('Delete')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
