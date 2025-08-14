<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('contract_reference', 20);
            $table->string('customer_city', 50)->nullable();
            $table->string('customer_country', 20)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('plain_password')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('customers');
    }
}