//database/migrations/2024_12_29_create_activity_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('animal_id');
            $table->json('changed_data'); // To store edited data as JSON
            $table->unsignedBigInteger('editor_id')->nullable(); // If you have user management
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraints
            $table->foreign('animal_id')->references('id')->on('animals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
