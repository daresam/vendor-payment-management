<?php

namespace Tests\Feature;

use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_an_invoice()
    {
        $data = [
            'amount' => 100.50,
            'due_date' => now()->addDays(7)->toDateString(),
            'description' => 'Test invoice',
        ];

        $response = $this->postJson('/api/corporate/1/vendor/1/invoice', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'corporate_id',
                'vendor_id',
                'invoice_number',
                'amount',
                'status',
                'due_date',
                'description',
            ])
            ->assertJsonFragment(['status' => 'OPEN']);
    }

    public function test_it_fails_to_create_invoice_with_past_due_date()
    {
        $data = [
            'amount' => 100.50,
            'due_date' => now()->subDay()->toDateString(),
            'description' => 'Test invoice',
        ];

        $response = $this->postJson('/api/corporate/1/vendor/1/invoice', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['due_date']);
    }

    public function test_it_can_list_invoices_for_a_vendor()
    {
        Invoice::factory()->count(3)->create([
            'corporate_id' => 1,
            'vendor_id' => 1,
        ]);

        $response = $this->getJson('/api/corporate/1/vendor/1/invoice');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'corporate_id',
                    'vendor_id',
                    'invoice_number',
                    'amount',
                    'status',
                    'due_date',
                    'description',
                ],
            ]);
    }

    public function test_it_can_show_a_specific_invoice()
    {
        $invoice = Invoice::factory()->create([
            'corporate_id' => 1,
            'vendor_id' => 1,
        ]);

        $response = $this->getJson("/api/corporate/1/vendor/1/invoice/{$invoice->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $invoice->id]);
    }

    public function test_it_can_update_invoice_status_to_closed()
    {
        $invoice = Invoice::factory()->create([
            'corporate_id' => 1,
            'vendor_id' => 1,
            'status' => 'OPEN',
        ]);

        $response = $this->putJson("/api/corporate/1/vendor/1/invoice/{$invoice->id}", [
            'status' => 'CLOSED',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'CLOSED']);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'CLOSED',
        ]);
    }

    public function test_it_fails_to_update_invoice_with_invalid_status()
    {
        $invoice = Invoice::factory()->create([
            'corporate_id' => 1,
            'vendor_id' => 1,
        ]);

        $response = $this->putJson("/api/corporate/1/vendor/1/invoice/{$invoice->id}", [
            'status' => 'INVALID',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
