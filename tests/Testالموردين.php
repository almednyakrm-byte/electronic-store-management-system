<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class Testالموردين extends TestCase
{
    protected $supplier;

    public function setUp(): void
    {
        parent::setUp();
        $this->supplier = Mockery::mock(Supplier::class);
        DB::shouldReceive('select')->andReturnUsing(function ($query) {
            return [
                ['id' => 1, 'name' => 'Supplier 1'],
                ['id' => 2, 'name' => 'Supplier 2'],
            ];
        });
        DB::shouldReceive('insert')->andReturn(true);
        DB::shouldReceive('update')->andReturn(true);
        DB::shouldReceive('delete')->andReturn(true);
    }

    public function test_get_suppliers()
    {
        $response = $this->get('/api/suppliers');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                ['id' => 1, 'name' => 'Supplier 1'],
                ['id' => 2, 'name' => 'Supplier 2'],
            ],
        ]);
    }

    public function test_post_supplier()
    {
        $data = [
            'name' => 'Supplier 3',
        ];
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);
        $this->assertTrue($validator->passes());

        $response = $this->post('/api/suppliers', $data);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'id' => 3,
                'name' => 'Supplier 3',
            ],
        ]);
    }

    public function test_put_supplier()
    {
        $data = [
            'name' => 'Supplier 1 Updated',
        ];
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);
        $this->assertTrue($validator->passes());

        $response = $this->put('/api/suppliers/1', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => 1,
                'name' => 'Supplier 1 Updated',
            ],
        ]);
    }

    public function test_delete_supplier()
    {
        $response = $this->delete('/api/suppliers/1');
        $response->assertStatus(200);
    }
}


This test file covers the following scenarios:

1.  **GET Suppliers**: Tests that a GET request to `/api/suppliers` returns a list of suppliers.
2.  **POST Supplier**: Tests that a POST request to `/api/suppliers` creates a new supplier with the provided data.
3.  **PUT Supplier**: Tests that a PUT request to `/api/suppliers/{id}` updates an existing supplier with the provided data.
4.  **DELETE Supplier**: Tests that a DELETE request to `/api/suppliers/{id}` deletes an existing supplier.

Note that this test file uses Mockery to mock the `Supplier` model and the `DB` facade. This allows us to isolate the dependencies and focus on the business logic of the API operations.