<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('proofing_jobs')->onDelete('cascade')->index();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type', 30);
            $table->string('ip_address', 45)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['job_id', 'updated_at', 'activity_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}