<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProofsTable extends Migration
{
    public function up()
    {
        Schema::create('proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id');
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->datetime('proof_sent')->nullable();
            $table->timestamps();

            $table->foreign('job_id')
                ->references('id')
                ->on('proofing_jobs')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('proofs');
    }
}