use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('task_status_id');
            $table->index('segment');
            $table->index('created_at');
            $table->index('task_code');
            $table->index(['task_status_id', 'segment']);
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('task_status_id');
            $table->dropIndex('segment');
            $table->dropIndex('created_at');
            $table->dropIndex('task_code');
            $table->dropIndex(['task_status_id', 'segment']);
        });
    }
} 