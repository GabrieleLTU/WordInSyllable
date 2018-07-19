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
        } else {
            $this->from = "FROM $tables";
        }

        return $this;
    }
    /**
     * @param $tables - array|string
     * @return SqlQueryBuilder
     */
//    public function join($tables, $onCondition): SqlQueryBuilder
//    {
//        if (is_array($tables)) {
//            $this->from = "FROM " . implode(", ", $tables);
//        }else {
//            $this->from = "FROM $tables";
//        }
//
//        return $this;
//    }

    /**
     * @param $conditions - array|string
     * @return SqlQueryBuilder
     */
    public function where($conditions): SqlQueryBuilder
    {
        if (is_array($conditions)) {
            $this->where = " WHERE " . implode(" AND ", $conditions);
        } else {
             $this->where = " WHERE " . $conditions;
        }
        return $this;
    }

    public function values(array $valuesByKey): SqlQueryBuilder
    {
        $this->values = "(" . implode(", ", array_keys($valuesByKey[0])) . ") VALUES ";
        foreach ($valuesByKey as $key => $value) {
            $this->values .= "('" .  implode("', '", $value) . "')";
            if ($key != (count($valuesByKey) - 1)) {
                $this->values .= ", ";
            }
        }
        return $this;
    }

    public function set(array $valuesByKey): SqlQueryBuilder
    {
        $temp = [];
        var_dump($valuesByKey);
        foreach ($valuesByKey as $key => $value) {
            //$temp[] = $valuesNames[$key] . "=" . $value;
            $temp[] = "$key='$value'";
        }
        $this->values =  " SET " . implode(", ", $temp);

        return $this;
    }

    public function __toString(): string
    {
        return $this->operation . " " . $this->from . " " . $this->values . " " . $this->where;
    }
}