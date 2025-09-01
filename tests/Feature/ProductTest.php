<?php

namespace Tests\Feature;

use App\Models\products;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductTest extends TestCase
{
//    use RefreshDatabase;

    #[Test]
    public function it_creates_a_product()
    {
        $data = [
            'name' => 'Test Product',
            'price' => 199,
            'description' => 'This is a test product',
            'category' => 'This is a test category',
            'stock' => 10,
            'added_by' => 1,
            'unique_id' => '345364b475nb5v46587b'
        ];

        $product = products::create($data);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 199,
        ]);

        $this->assertInstanceOf(products::class, $product);
        $this->assertEquals('Test Product', $product->name);
    }
}
