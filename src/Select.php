<?php
namespace SnorkelWeb\QueryBuilder;
use SnorkelWeb\DBManager\Connection;
// Traits
use SnorkelWeb\QueryBuilder\Traits\Orderby;
use SnorkelWeb\QueryBuilder\Traits\Where;
use SnorkelWeb\QueryBuilder\Traits\GroupBy;
use SnorkelWeb\QueryBuilder\Traits\Having;
use SnorkelWeb\QueryBuilder\Traits\Joins;
use SnorkelWeb\QueryBuilder\Traits\Limit;
use SnorkelWeb\QueryBuilder\Traits\Params;
use SnorkelWeb\QueryBuilder\Traits\From;

class Select extends Connection
{
    private $sql;
    private $stmt;
    public $RowCount;

        use From;
        use Orderby;
        use Where;
        use GroupBy;
        use Joins;
        use Limit;
        use Having;
        use Params;
    
        public function select($values)
        {
            $this->sql = "SELECT " . $values. " ";
            return $this;
        }

        private function sqlloader()
    {

        // Joins
        // Joins wiull go here joins needs to be worked on;
        // Where and or where;
        $this->sql .= $this->fetchJoins();
        $this->Fetchwhere();
        // GroupBy is complete
        $this->sql .= $this->FetchGroupBy();
        // Having is complete
        $this->sql .= $this->FetchHaving();
        // Order By is complete
        $this->sql .= $this->FetchOrderBy();

        //Limits are complete 
        $this->sql .= $this->FetchLimit();
    }

    public function prepare($sql)
    {
       return $this->connect()->prepare($sql);
    }


    public function save()
    {
        // QueryBuilder LOader goes here
        $this->sqlloader();
        // Start Prepare query
        $this->stmt = $this->prepare($this->sql);
    // Param Binder
       $this->parambinder();
        // Execute the final Script;
        // echo $this->toSql();
       
         $this->stmt->execute();

         return $this;
    }


    public function AsSql($sql)
    {
        $this->sql = $sql;
        return $this;
    }

    public function GetRowCount()
    {
        return $this->stmt->rowCount();
    }

     public function first()
    {
             $data = $this->stmt->fetch(); 
             return $data;
      
    }

    public function tosql()
    {
        return $this->sql;
    }

    public function tojson($value)
    {
        return json_encode($value);
    }

    public function get()
    {
    
            return $this->stmt->fetchall(); 

    }

    }
