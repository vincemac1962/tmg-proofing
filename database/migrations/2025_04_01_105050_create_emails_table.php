<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('proofing_jobs')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('emails');
    }
}