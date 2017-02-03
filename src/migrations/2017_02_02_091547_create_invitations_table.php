<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email');
            $table->string('code');

            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_accepted')->default(false);
            $table->timestamp('accepted_at')->nullable();

            $table->string('model_type')->nullable();
            $table->integer('model_id')->nullable();

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
        Schema::drop('invitations');
    }
}
