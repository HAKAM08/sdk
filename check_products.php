<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = DB::table('products')->select('id', 'name', 'image')->limit(5)->get();

echo "Products:\n";
foreach ($products as $product) {
    echo "ID: {$product->id}, Name: {$product->name}, Image: {$product->image}\n";
}