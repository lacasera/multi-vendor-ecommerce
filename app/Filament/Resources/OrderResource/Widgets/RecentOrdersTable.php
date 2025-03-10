<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::forSupplier())
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('checkout.code')->label('Order Code')->sortable(),
                Tables\Columns\TextColumn::make('checkout.user.name')->label('User'),
                Tables\Columns\TextColumn::make('items.product.title')->label('Product')->sortable(),
                Tables\Columns\TextColumn::make('items.quantity')->label('QTY')->sortable(),
                Tables\Columns\TextColumn::make('checkout')
                    ->label('Total')
                    ->formatStateUsing(fn ($record) => '$' . $record?->total() ?? 'N/A'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->formatStateUsing(fn($record) => $record->created_at->format('d.m.Y')),
            ]);
    }
}
