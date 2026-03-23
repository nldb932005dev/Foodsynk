<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The pivot table was already created with both foreign keys and the
        // composite unique index, so this historical "fix" must stay as a no-op
        // to keep fresh migrations reproducible.
    }

    public function down(): void
    {
        //
    }
};
