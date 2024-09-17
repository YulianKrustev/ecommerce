<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Create New Post')
                    ->tabs([
                        Tab::make('Create Post')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Section::make('Post Info')
                                            ->columns([
                                                'sm' => 2,
                                                'xl' => 4,
                                                '2xl' => 6,
                                            ])
                                            ->schema([
                                                TextInput::make('title')
                                                    ->columnSpan(3)
                                                    ->required()
                                                    ->maxLength(60)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (
                                                        string $operation,
                                                        ?string $state,
                                                        Forms\Set $set
                                                    ) {
                                                        if ($operation == 'edit' || $state == null) {
                                                            return;
                                                        }
                                                        $set('meta_title',
                                                            ASCII::to_ascii($state) . " - " . config("app.name"));
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


                                                FileUpload::make('image')
                                                    ->required()
                                                    ->columnSpanFull()
                                                    ->optimize('webp')
                                                    ->directory('posts')
                                                    ->imageEditor()
                                                    ->imagePreviewHeight('150'),

                                                TextInput::make('image_alt')
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


                                                RichEditor::make('content')
                                                    ->disableToolbarButtons([
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

                                                Select::make('products')
                                                    ->relationship('products', 'name')
                                                    ->multiple()
                                                    ->columnSpan(3)
                                                    ->searchable()
                                                    ->label('Select Products')
                                                    ->preload()
                                                    ->reactive(),
                                            ]),
                                    ])->columnSpan(3),

                                Group::make()->schema([

                                    Section::make('Category')->schema([
                                        Select::make('post_category_id')
                                            ->label('')
                                            ->relationship('category', 'name')
                                            ->preload()
                                            ->required(),
                                    ]),

                                    Section::make('Status')->schema([

                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->required()
                                            ->default(true),

                                        Toggle::make('is_featured')
                                            ->label('Featured')
                                            ->required(),
                                    ])
                                ])->columnSpan(1),
                            ])->columns(4),

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

                ImageColumn::make('image')
                    ->alignCenter()
                    ->height(100)
                    ->width(100)
                    ->label('Image'),

                TextColumn::make('title')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('categories')
                    ->alignCenter()
                    ->label('Category')
                    ->getStateUsing(function ($record) {
                        return $record->category->name;
                    }),

                SelectColumn::make('is_active')
                    ->alignCenter()
                    ->label('Active')
                    ->selectablePlaceholder(false)
                    ->options([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Blog';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;  // Sort order for this resource
    }
}
