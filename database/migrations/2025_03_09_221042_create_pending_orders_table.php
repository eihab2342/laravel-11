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
        Schema::create('pending_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // مفتاح أجنبي
            $table->json('customer_data'); // تخزين بيانات العميل بصيغة JSON
            $table->json('cart_items'); // تخزين المنتجات بصيغة JSON
            $table->decimal('final_total', 10, 2); // تخزين الإجمالي النهائي
            $table->uuid('order_reference')->unique(); // معرف فريد للطلب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_orders');
    }
};
