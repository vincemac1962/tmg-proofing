<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proof_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('contract_reference', 20)->nullable();
            $table->timestamp('approved_at')->useCurrent();
            $table->string('approved_by', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}