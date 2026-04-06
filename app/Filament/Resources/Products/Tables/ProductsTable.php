<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Ảnh')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Giá gốc')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 0, ',', '.') . ' VNĐ')
                    ->sortable(),
                TextColumn::make('discount_percent')
                    ->label('Giảm giá')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('final_price')
                    ->label('Giá sau giảm')
                    ->state(fn (Product $record): float => $record->final_price)
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 0, ',', '.') . ' VNĐ'),
                TextColumn::make('stock_quantity')
                    ->label('Tồn kho')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
