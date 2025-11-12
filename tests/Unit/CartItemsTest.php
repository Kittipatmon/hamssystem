<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\serviceshams\Cart_items;

class CartItemsTest extends TestCase
{
    /** @test */
    public function cart_items_model_can_be_instantiated()
    {
        $model = new Cart_items([
            'cart_item_id' => 1,
            'cart_code' => 'X001',
            'cart_name' => 'Test Item',
            'cart_quantity' => 2,
            'user_id' => 1,
        ]);

        $this->assertInstanceOf(Cart_items::class, $model);
        $this->assertEquals('Test Item', $model->cart_name);
    }
}
