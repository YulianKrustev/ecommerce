<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

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
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(60)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                            if ($operation == 'edit' || $state == null) {
                                                return;
                                            }
                                            $set('meta_title', ASCII::to_ascii($state) . " - " . config("app.name"));
                                            $set('slug', ASCII::to_ascii(Str::slug($state)));
                                            $set('image_alt', $state);
                                        }),

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
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                            if ($operation == 'edit' || $state == null) {
                                                return;
                                            }
                                            $plainTextState = strip_tags($state);
                                            $truncatedState = mb_substr($plainTextState, 0, 155);
                                            $truncatedState = rtrim($truncatedState);
                                            $truncatedState .= '...';
                                            $set('meta_description', $truncatedState);
                                        }),
                                ]),

                            Section::make('Images')->schema([
                                FileUpload::make('images')
                                    ->required()
                                    ->label('')
                                    ->multiple()
                                    ->maxFiles(6)
                                    ->reorderable()
                                    ->columnSpanFull()
                                    ->directory('products')
                                    ->imageEditor()
                                    ->imagePreviewHeight('150'),
                            ])
                        ])->columnSpan(3),

                        Group::make()->schema([
                            Section::make('Prices and Quantity')->schema([
                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('BGN'),

                                TextInput::make('on_sale_price')
                                    ->numeric()
                                    ->prefix('BGN')
                                    ->disabled(fn(callable $get) => !$get('on_sale')),

                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric(),
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
                                    ->live()
                                    ->required(),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->required()
                                    ->default(true),

                                Toggle::make('in_stock')
                                    ->default(true)
                                    ->required(),

                                Toggle::make('is_featured')
                                    ->required(),
                            ])
                        ])->columnSpan(1),
                    ])->columns(4),

                    Tab::make('SEO')->schema([
                        TextInput::make('meta_title')
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
                            ->placeholder('New keyword')
                    ])
                ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('first_image'),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('categories')
                    ->getStateUsing(function ($record) {
                        return $record->categories->pluck('name')->join(', ');
                    })
                    ->sortable(),

                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('price')
                    ->money('BGN')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean(),

                IconColumn::make('on_sale')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('categories', 'name'),

                SelectFilter::make('is_active')
                ->options([
                    '1' => 'Yes',
                    '0' => 'No',
                ]),

                SelectFilter::make('on_sale')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
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
