<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugFieldSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specifications', function($table) {
            $table->string('slug')->after('name');
        });

        $specifications = \App\Models\Specification::all();
        foreach ($specifications as $specification) {
            \App\Models\Specification::where(['id' => $specification->id])
                ->update([
                    'slug' => Str::slug($specification->name)
                ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specifications', function($table) {
            $table->dropColumn('slug');
        });
    }
}
