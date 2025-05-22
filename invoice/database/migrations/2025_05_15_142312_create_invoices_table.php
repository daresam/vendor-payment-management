<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->integer('corporate_id');
                $table->integer('vendor_id');
                $table->string('invoice_number')->unique();
                $table->integer('quantity')->default(1);
                $table->decimal('rate', 10, 2);
                $table->decimal('amount', 10, 2); // Total cost: quantity * rate
                $table->enum('status', ['OPEN', 'CLOSED'])->default('OPEN');
                $table->date('issue_date'); // Date invoice is issued
                $table->date('due_date'); // Due date based on payment terms
                $table->string('payment_terms')->default('Net 30'); // e.g., Net 7, Net 14, Net 30
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // Add indexes for faster queries
                $table->index('corporate_id');
                $table->index('vendor_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
