<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarouselItemResource\Pages;
use App\Filament\Resources\CarouselItemResource\RelationManagers;
use App\Models\CarouselItem;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarouselImagesResource extends Resource
{
    protected static ?string $model = CarouselItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Carousel Images';

    protected static ?string $navigationGroup = 'Settings';

    public static function getPluralModelLabel(): string
    {
        return 'Carousel Images';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image_path')
                    ->label('Image')
                    ->optimize('webp')
                    ->columnSpanFull()
                    ->image()
                    ->directory('carousel')
                    ->required()
                    ->imageEditor()
                    ->imagePreviewHeight('350px'),

                Forms\Components\TextInput::make('alt_text')
                    ->label('Alt Text')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->alignCenter()
                    ->height(100)
                ->width(150),

                TextColumn::make('title')
                    ->alignCenter(),

                TextColumn::make('alt_text')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCarouselImage::route('/'),
            'create' => Pages\CreateCarouselImage::route('/create'),
            'edit' => Pages\EditCarouselImage::route('/{record}/edit'),
        ];
    }
}
