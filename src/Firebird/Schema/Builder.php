<?php namespace Firebird\Schema;

use Illuminate\Database\Schema\Builder as BaseBuilder;

class Builder extends BaseBuilder {
    private static function ensureCharLength(string $str, int $length = 31){
        if (strlen($str) > $length){
            throw new Exception("\"$str\" overflow $length char");
        }
    }

    /**
     * Create a new sequence on the schema.
     *
     * @param  string     $sequence max length 31 char
     * @param  integer    $start_with
     * @param  integer    $increment_by
     * @param  integer    $current_value
     * @param  string     $comment
     * @return void
     */
    public function createSequence(string $sequence, $comment = null, $start_with = 1, $increment_by = 1, $current_value = 1)
    {
        static::ensureCharLength($sequence);
        $this->connection->statement("CREATE SEQUENCE \"$sequence\" START WITH $start_with INCREMENT BY $increment_by");
        $this->connection->statement("SET GENERATOR \"$sequence\" TO $current_value");
        if (is_string($comment)){
            $this->connection->statement("COMMENT ON GENERATOR \"$sequence\" IS '$comment'");
        }
    }

    /**
     * Drop sequence
     * @param  string     $sequence max length 31 char
     * @return void
     */
    public function dropSequence(string $sequence)
    {
        static::ensureCharLength($sequence);
        $this->connection->statement("DROP SEQUENCE \"$sequence\"");
    }

    /**
     * Update sequence value with max id in table
     * @param  string     $sequence
     * @param  string     $table
     * @param  string     $primary_key
     * @return void
     */
    public function freshSequenceTable(string $sequence, string $table, string $primary_key = 'id')
    {
        static::ensureCharLength($sequence);
        static::ensureCharLength($table);
        $max_id = \DB::table($table)->max($primary_key);
        $this->connection->statement("SET GENERATOR \"$sequence\" TO $max_id");
    }

    /**
     * Update sequence value with max id in table
     * @param  string     $sequence
     * @param  string     $table
     * @param  string     $primary_key
     * @return void
     */
    public function createIdMakerWithTrigger(string $sequence, string $table, string $primary_key = 'id')
    {
        static::ensureCharLength($sequence);
        static::ensureCharLength($table);

        $trigger_name = substr($sequence, 0,31-3).'_bi';
        $this->connection->statement("
CREATE OR ALTER TRIGGER \"$trigger_name\" FOR \"$table\" 
ACTIVE BEFORE INSERT POSITION 0 
AS 
BEGIN 
    IF (NEW.\"$primary_key\" IS NULL) THEN 
        NEW.\"$primary_key\" = GEN_ID(\"$sequence\", 1); 
END 
        ");
    }
}