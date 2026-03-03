<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("invoices", function (Blueprint $table) {
            $table->id();
            $table->foreignId("project_id")->constrained()->cascadeOnDelete();
            $table->string("invoice_number")->unique();
            $table->date("invoice_date");
            $table->date("period_start");
            $table->date("period_end");
            $table->decimal("subtotal", 12, 2)->default(0);
            $table->decimal("vat_amount", 12, 2)->default(0);
            $table->decimal("total_amount", 12, 2)->default(0);
            $table->text("notes")->nullable();
            $table->timestamps();
        });
        
        Schema::create("invoice_items", function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id")->constrained()->cascadeOnDelete();
            $table->string("description");
            $table->decimal("quantity", 8, 2);
            $table->decimal("rate", 8, 2);
            $table->decimal("amount", 12, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("invoice_items");
        Schema::dropIfExists("invoices");
    }
};