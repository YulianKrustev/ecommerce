<?php

namespace App\Filament;

use App\Models\Order;
use Illuminate\Support\Number;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class OrdersWithItemsExport extends ExcelExport
{
    public function setUp(): void
    {
        // Set up the export filename
        $this->withFilename('Export ' . now()->format('Y-m-d'))
            ->withColumns([
                Column::make('order_id')->heading('Order ID'),
                Column::make('user.name')->heading('Client Name'),
                Column::make('shipping_cost')->heading('Shipping'),
                Column::make('discount')->heading('Discount'),
                Column::make('total_price')->heading('Total Price'),
                Column::make('created_at')->heading('Order Date'),
                Column::make('item_id')->heading('Item ID'),
                Column::make('item_name')->heading('Item Name'),
                Column::make('item_size')->heading('Item Size'),
                Column::make('item_quantity')->heading('Item Quantity'),
                Column::make('item_price')->heading('Item Price'),
                Column::make('total_units_price')->heading('Total Units Price'),
            ]);
    }

    public function query()
    {
        // Modify the query to include only selected records and load items relationship
        if (!empty($this->recordIds)) {
            return Order::with('items.product') // Load related items and product details
            ->whereIn('id', $this->recordIds);
        }

        return Order::with('items.product'); // Default query
    }

    public function map($order): array
    {
        // Create an array to hold the rows for each order and its items
        $rows = [];

        // Add the order details as the first row
        $rows[] = [
            'order_id' => $order->id,
            'user.name' => $order->user->name,
            'shipping_cost' => Number::currency($order->shipping_cost, 'EUR'),
            'discount' => Number::currency($order->discount, 'EUR'),
            'total_price' => Number::currency($order->total_price, 'EUR'),
            'created_at' => $order->created_at,
            'item_id' => '',
            'item_name' => '',
            'item_size' => '',
            'item_quantity' => '',
            'item_price' => '',
            'total_units_price' => '',
        ];

        // Add each item in a separate row beneath the order details
        foreach ($order->items as $item) {
            $rows[] = [
                'order_id' => '',
                'user.name' => '',
                'shipping_cost' => '',
                'discount' => '',
                'total_price' => '',
                'created_at' => '',
                'item_id' => $item->product->id,
                'item_name' => $item->product->name,
                'item_size' => $item->size,
                'item_quantity' => $item->quantity,
                'item_price' => Number::currency($item->unit_price, 'EUR'),
                'total_units_price' => Number::currency($item->total_units_price, 'EUR')
            ];
        }

        return $rows; // Return the entire set of rows for this order
    }
}
