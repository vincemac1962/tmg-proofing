<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmendmentsTable extends Migration
{
    public function up()
    {
        Schema::create('amendments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proof_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->text('amendment_notes');
            $table->string('contract_reference', 20);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('amendments');
    }
}