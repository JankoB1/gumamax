<?php namespace Delmax\Webapp\Traits;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 18.8.2015
 * Time: 16:32
 */


trait DmxDBTrait
{
    public function getKeyName(){

        return $this->primaryKey;

    }

    public function getTableColumns() {

        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());

    }


    public static function getDtSqlFields()
    {
        $dtSqlFields = [];

        foreach (static::$dtColumns as $column){
            if (isset($column['name'])&&($column['visible']==1))
                $dtSqlFields[] = $column['name'];
        }

        return $dtSqlFields;
    }



}