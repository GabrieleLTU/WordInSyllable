<?php
namespace WordInSyllable\Database;
class SqlQueryBuilder
{
    private $operation = "";
    private $from = "";
    private $where = "";
    private $values = "";

    public function select(array $atributes): SqlQueryBuilder
    {
        $this->operation = "SELECT " . implode(", ", $atributes);

        return $this;
    }

    public function insertInto(string $table): SqlQueryBuilder
    {
        $this->operation = "INSERT INTO  " . $table;

        return $this;
    }

    public function update(string $table): SqlQueryBuilder
    {
        $this->operation = "UPDATE  " . $table;

        return $this;
    }

    public function delete(): SqlQueryBuilder
    {
        $this->operation = "DELETE ";

        return $this;
    }

    /**
     * @param $tables - array|string
     * @return SqlQueryBuilder
     */
    public function from($tables): SqlQueryBuilder
    {
       if (is_array($tables)) {
            $this->from = "FROM " . implode(", ", $tables);
       }else {
           $this->from = "FROM $tables";
       }

        return $this;
    }

    /**
     * @param $conditions - array|string
     * @return SqlQueryBuilder
     */
    public function where($conditions): SqlQueryBuilder
    {
        if (is_array($conditions)) {
            $this->where = " WHERE " . implode(", ", $conditions);
        }else {
            $this->where = " WHERE " . $conditions;
       }

        return $this;
    }

    public function values(array $valuesNames, array $values): SqlQueryBuilder
    {
        $this->values = "(" . implode(", ", $valuesNames) . ") VALUES ";
        if (is_array($values[0])) {
            foreach ($values as $key => $value){
                $this->values .= "(" .  implode(", ", $value) . ")";
                if ($key != (count($values)-1))  $this->values .= ", ";
            }
            //$this->values = substr( $this->values, -2);

        } else {
            $this->values .= "(" .  implode(", ", $values) . ") ";
        }

        return $this;
    }

    public function set(array $valuesNames, array $values): SqlQueryBuilder
    {
        $temp = [];
        foreach ($values as $key => $value){
            $temp[] = $valuesNames[$key] . "=" . $value;
        }
        $this->values =  " SET " . implode(", ", $temp);

        return $this;
    }

    public function __toString(): string
    {
//        return sprintf(
//            'SELECT %s FROM %s WHERE %s',
//            join(', ', $this->atributes),
//            join(', ', $this->from),
//            join(' AND ', $this->where)
//        );

        return $this->operation . " " . $this->from . " " . $this->values . " " . $this->where;
    }
}