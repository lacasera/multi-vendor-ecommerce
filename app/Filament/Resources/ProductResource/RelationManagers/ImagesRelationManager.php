<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('url')
                    ->columnSpanFull()
                    ->image()
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('product-images')
                    //->preserveFilenames()
                    // ->afterStateUpdated(function ($state, $set, $get) {
                    //     if (is_array($state)) {
                    //         foreach ($state as $path) {
                    //             $this->getOwnerRecord()->images()->create([
                    //                 'url' => $path
                    //             ]);
                    //         }
                    //     }
                    //     $set('url', null);
                    // })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('url')->disk('public'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
