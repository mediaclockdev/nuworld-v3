<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

class SupportController extends Controller
{
  public function phpinfo()
  {
    return phpinfo();
  }
  public function migrate(string $key = '')
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";

    Artisan::call('migrate');
    echo 'Database migrated successfully';
  }

  public function migrateByName(string $key = '', string $migrationName = '')
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";
    $migrationPath = 'database/migrations/' . $migrationName . '.php';

    if (!file_exists($migrationPath))
      return "Invalid migration name";

    Artisan::call('migrate --path=' . $migrationPath);
    echo 'Database migrated successfully for ' . $migrationName;
  }

  public function migrateFresh(string $key)
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";

    Artisan::call('migrate:fresh');
    echo 'Database migrated (fresh) successfully';
  }
  public function seed(string $key = '')
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";

    Artisan::call('db:seed');
    echo 'Database seeded successfully';
  }

  public function seedBy(string $key = '', string $seedName = '')
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";

    Artisan::call('db:seed --class=' . $seedName);
    echo 'Database seeded successfully for ' . $seedName;
  }

  public function optimizeClear(string $key = '')
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";

    Artisan::call('optimize:clear');
    echo 'All Site cache optimized successfully';
  }


  public function createSymLink()
  {
    Artisan::call('storage:link');
    echo 'Storage linked with your src folder path';
  }

  public function generateToken()
  {
    Artisan::call('key:generate');
    echo 'Token generated successfully';
  }

  public function showTable(string $key = '', string $tblName = '')
  {
    if ($key != config('app.super_admin_key'))
      return response()->json(['error' => 'Invalid key'], 403);

    try {
      // Check if the table exists
      if (!Schema::hasTable($tblName)) {
        return response()->json(['error' => 'Table not found'], 404);
      }

      // Retrieve all data from the specified table
      $data = DB::table($tblName)->get();

      if ($data->isEmpty())
        return response()->json(['error' => 'Table is empty'], 200);

      return response()->json($data);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function migrateList(string $key = '')
  {
    if ($key !== config('app.super_admin_key')) {
      return response()->json(['error' => 'Invalid key'], 403);
    }

    // Execute the migrate:status command
    Artisan::call('migrate:status');
    $output = Artisan::output();

    // Convert output into an array of lines
    $lines = explode("\n", trim($output));

    $migrations = [];

    foreach ($lines as $line) {
      // Extract migration name and batch status using a regex pattern
      if (preg_match('/\s*([\d_]+_create_[\w\d_]+)\s+\.+\s+\[(\d+)\]\s+(Ran|Pending)/', $line, $matches)) {
        $migrations[] = [
          'migration' => $matches[1], // Migration file name
          'batch' => (int) $matches[2], // Batch number
          'status' => $matches[3] === 'Ran' ? 'Migrated' : 'Pending'
        ];
      }
    }

    return response()->json([
      'success' => true,
      'migrations' => $migrations
    ]);
  }

  public function rawsql(string $key = '', string $raw = '')
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";

    DB::statement($raw);
    echo 'Raw SQL executed successfully';
  }

  public function getFullTableMetadata(string $key = '', string $tableName)
  {
    if ($key != config('app.super_admin_key'))
      return "Invalid key";

    $tableName = DB::getTablePrefix() . $tableName;
    $separator = '----------------------------------------------';
    // Fetch and group indexes
    $indexes = DB::select("SHOW INDEXES FROM {$tableName}");
    $groupedIndexes = [];
    foreach ($indexes as $index) {
      $keyName = $index->Key_name;
      if (!isset($groupedIndexes[$keyName])) {
        $groupedIndexes[$keyName] = [
          'non_unique' => (bool) $index->Non_unique,
          'columns' => [],
          'type' => $index->Index_type,
          'comment' => $index->Comment,
        ];
      }
      $groupedIndexes[$keyName]['columns'][$index->Seq_in_index] = [
        'column_name' => $index->Column_name,
        'collation' => $index->Collation,
        'cardinality' => $index->Cardinality,
        'sub_part' => $index->Sub_part,
        'null' => $index->Null === 'YES',
      ];
    }
    foreach ($groupedIndexes as &$index) {
      ksort($index['columns']); // Sort columns by sequence
    }
    unset($index); // Unset the reference after the loop

    // Fetch and group constraints (foreign keys and unique constraints)
    $foreignKeys = DB::select("
        SELECT
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE
            TABLE_NAME = ?
            AND REFERENCED_TABLE_NAME IS NOT NULL
    ", [$tableName]);

    $uniqueConstraints = DB::select("
        SELECT
            TABLE_NAME,
            INDEX_NAME,
            COLUMN_NAME,
            SEQ_IN_INDEX
        FROM
            INFORMATION_SCHEMA.STATISTICS
        WHERE
            TABLE_NAME = ?
            AND NON_UNIQUE = 0
    ", [$tableName]);

    // Group constraints
    $groupedConstraints = [];

    // Group foreign keys by CONSTRAINT_NAME
    foreach ($foreignKeys as $fk) {
      $constraintName = $fk->CONSTRAINT_NAME;
      if (!isset($groupedConstraints[$constraintName])) {
        $groupedConstraints[$constraintName] = [
          'type' => 'foreign_key',
          'columns' => [],
          'referenced_table' => $fk->REFERENCED_TABLE_NAME,
          'referenced_columns' => [],
        ];
      }
      $groupedConstraints[$constraintName]['columns'][] = $fk->COLUMN_NAME;
      $groupedConstraints[$constraintName]['referenced_columns'][] = $fk->REFERENCED_COLUMN_NAME;
    }

    // Group unique constraints by INDEX_NAME
    foreach ($uniqueConstraints as $uc) {
      $indexName = $uc->INDEX_NAME;
      if (!isset($groupedConstraints[$indexName])) {
        $groupedConstraints[$indexName] = [
          'type' => 'unique',
          'columns' => [],
          'referenced_table' => null,
          'referenced_columns' => [],
        ];
      }
      $groupedConstraints[$indexName]['columns'][$uc->SEQ_IN_INDEX] = $uc->COLUMN_NAME;
      ksort($groupedConstraints[$indexName]['columns']); // Ensure correct order
    }

    // Return full metadata
    return [
      "Table Name" => $tableName,
      "{$separator}" => $separator,
      "Columns" => DB::select("SHOW COLUMNS FROM {$tableName}"),
      "{$separator}--" => $separator,
      "Definition" => DB::selectOne("SHOW CREATE TABLE {$tableName}")->{'Create Table'},
      "{$separator}-" => $separator,
      "Grouped Indexes" => $groupedIndexes,
      "{$separator}---" => $separator,
      "Foreign Keys" => $foreignKeys,
      "{$separator}-----" => $separator,
      "Grouped Constraints" => $groupedConstraints,
    ];
  }
}
