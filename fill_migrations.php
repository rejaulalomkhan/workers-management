<?php
$dir = 'database/migrations/';
$files = scandir($dir);

$migrations = [
    'settings' => '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("settings", function (Blueprint $table) {
            $table->id();
            $table->string("company_name")->nullable();
            $table->string("company_name_arabic")->nullable();
            $table->string("trn")->nullable();
            $table->text("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("logo_path")->nullable();
            $table->string("header_image_path")->nullable();
            $table->string("currency")->default("AED");
            $table->decimal("vat_rate", 5, 2)->default(5.00);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("settings");
    }
};',
    'projects' => '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("projects", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("location")->nullable();
            $table->string("customer_name")->nullable();
            $table->string("customer_address")->nullable();
            $table->string("customer_trn")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("projects");
    }
};',
    'project_categories' => '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("project_categories", function (Blueprint $table) {
            $table->id();
            $table->foreignId("project_id")->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->decimal("billing_rate", 8, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("project_categories");
    }
};',
    'workers' => '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("workers", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("worker_id_number")->unique()->nullable();
            $table->string("trade");
            $table->decimal("internal_pay_rate", 8, 2)->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("workers");
    }
};',
    'worker_rates' => '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("worker_rates", function (Blueprint $table) {
            $table->id();
            $table->foreignId("worker_id")->constrained()->cascadeOnDelete();
            $table->decimal("rate", 8, 2);
            $table->date("effective_from");
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("worker_rates");
    }
};',
    'attendances' => '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("attendances", function (Blueprint $table) {
            $table->id();
            $table->foreignId("worker_id")->constrained()->cascadeOnDelete();
            $table->foreignId("project_id")->nullable()->constrained()->nullOnDelete();
            $table->date("date");
            $table->string("hours");
            $table->timestamps();
            
            $table->unique(["worker_id", "project_id", "date"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("attendances");
    }
};',
    'invoices' => '<?php
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
};'
];

foreach ($files as $file) {
    if ($file === "." || $file === "..") continue;
    foreach ($migrations as $key => $content) {
        if (strpos($file, "create_" . $key . "_table") !== false) {
            file_put_contents($dir . $file, $content);
            echo "Updated " . $file . "\n";
        }
    }
}
