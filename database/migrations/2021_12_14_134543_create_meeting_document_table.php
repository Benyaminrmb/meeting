<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onUpdate( 'cascade' );
            $table->foreignId('upload_id')->constrained()->onUpdate( 'cascade' );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_uploads');
    }
}
