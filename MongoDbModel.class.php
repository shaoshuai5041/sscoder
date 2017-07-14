<?php

/**
 * Created by PhpStorm.
 * User: 50412
 * Date: 2017/7/13
 * Time: 19:57
 */

namespace Admin\Model;

use Think\Model;

class MongoDbModel extends Model {

    //Tp框架这里随便指向一个数据库存在的表名称  以后在优化吧 我也想不起来了~~
    protected $trueTableName = 'news';

    public function MongoDbConnet() {
        $manager = new \MongoDB\Driver\Manager('mongodb://root:123456@localhost:27017');
        return $manager;
    }

    public function insertMongoDb($arr, $db, $collection) {
        $manager = $this->MongoDbConnet();
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $bulk = new \MongoDB\Driver\BulkWrite(['ordered' => true]);
        //插入模版示例
//        $bulk->insert(['_id' => 3, 'hello' => 'world']);
        $bulk->insert($arr);
        $result = $manager->executeBulkWrite("{$db}.{$collection}", $bulk, $writeConcern);
        //成功会返回插入成功的行数
        return $result->getInsertedCount();
    }

    //$where 为修改条件
    //$set  为被修改的值
    public function updateMongoDb($db, $collection, $where, $set) {
        $manager = $this->MongoDbConnet();
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $bulk = new \MongoDB\Driver\BulkWrite(['ordered' => true]);
        //修改模版示例   //修改条件    ,//修改内容
//      $bulk->update(['_id' => 4], ['$set' => ['hello' => 'moon']]);
        $bulk->update($where, $set);
        $result = $manager->executeBulkWrite("{$db}.{$collection}", $bulk, $writeConcern);
        //成功会返回修改的条数
        return $result->getModifiedCount();
    }

    public function deleteMongoDb($db, $collection, $where) {
        $manager = $this->MongoDbConnet();
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $bulk = new \MongoDB\Driver\BulkWrite(['ordered' => true]);
        //删除模版示例   //删除条件
//      $bulk->delete(['_id' => 4]);
        $bulk->delete($where);
        $result = $manager->executeBulkWrite("{$db}.{$collection}", $bulk, $writeConcern);
        //成功会返回删除的条数
        return $result->getDeletedCount();
    }

    //$filter array()   筛选条件  //注意_id的条件时 _id的value为数值型 否则会报错
    //$options 需要的字段值
    //  需要的字段值  array('key'=>1);格式1为需要
    //$sort  为排序方式  默认为-1  asc方式
    public function select($filter, $get = array(), $sort = -1) {
//        $mongodb = new MongoDB\Driver\Manager("mongodb://root:123456@localhost:27017");
//        $query  = new MongoDB\Driver\Query([]);
//        $cursor = $mongodb->executeQuery('itheima4.goods',$query);
//        $it     = new IteratorIterator($cursor);
//        $it->rewind();
//        while ($doc=$it->current()) {
//            print_r($doc);
//            $it->next();
//            echo '<br/>';
//        }
//        $filter = array();
        //获取需要字段的模版
//        $options = array(
//            /* Only return the following fields in the matching documents */
//            "projection" => array(
//                "hello" => 1,
//                "article" => 1,
//            ),
//            "sort" => array(
//                "views" => -1,
//            ),
//            "modifiers" => array(
//                '$comment'   => "This is a query comment",
//                '$maxTimeMS' => 100,
//            ),
//        );
        $options = array(
            /* Only return the following fields in the matching documents */
            "projection" => $get,
            "sort" => array(
                "views" => $sort,
            ),
        );
        
        $query = new \MongoDB\Driver\Query($filter, $options);

        $manager = new \MongoDB\Driver\Manager('mongodb://root:123456@localhost:27017');
        $readPreference = new \MongoDB\Driver\ReadPreference(\MongoDB\Driver\ReadPreference::RP_PRIMARY);
        $cursor = $manager->executeQuery("demo.text", $query, $readPreference);

        foreach ($cursor as $document) {
            var_dump($document);
//            foreach ($document as $v){
//                dump($v);
//            }
        }
    }

}
