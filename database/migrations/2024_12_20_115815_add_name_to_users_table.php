<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('name')->after('id'); // Adjust placement as needed
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('name');
    });
}

};
