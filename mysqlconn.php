<?php
/**
 * 要求：
 * 1，只能实例化出来一个对象——即实现单例模式；
 * 2，一实例化就完成了mysql数据库的连接功能；
 * 3，有一个方法可以执行增删改语句并返回true或false
 * 		比如：$sql = “insert into ....”;
 * 4，有一个方法可以返回多行数据并作为二维数组；
 * 		比如：$sql = “select * from users where age > 18”;
 * 5，有一个方法可以返回单行数据并作为一维数组；
 * 		比如：$sql = “select * from article where  id=8”;
 * 6，有一个方法可以返回单个数据；
 * 		比如：$sql = “select  age  from user where id = 18”;
 *   $sql = “select  count(*) as c  from  article  “;
 * 7，需要将这个单例对象的连接数据库的信息进行序列化存盘，并可以恢复
 */
class MySQLDB{
    private $arr_conf = null;
    private $link = null;
    static $instance = null;//实例，即该唯一实例
    private function __construct( $conf ){
        $this->link = mysqli_connect("{$conf['host']}:{$conf['port']}"
            ,$conf['user'], $conf['pass']) or die('连接失败');
        //这就是连接数据
        mysqli_query($this->link,"set names {$conf['charset']}");
        mysqli_query($this->link,"use {$conf['dbname']}");
        $this->arr_conf = $conf;
    }
    private function __clone(){}

    public static function GetDB( $conf ){
        if(empty(static::$instance)){
            static::$instance = new static( $conf );
        }
        return static::$instance;
    }

    //功能3：可以执行增删改语句并返回true或false
    function exec($sql){
        $result = $this->query($sql);//
        return $result;
    }

    //4，有一个方法可以返回多行数据并作为二维数组；
    //	比如：$sql = “select * from users where age > 18”;
    function GetRows($sql){
        $result = $this->query($sql);
        //成功的时候：$result是一个“结果集”（资源类型）
        $arr = array();
        while($rec = mysqli_fetch_assoc($result)){
            $arr[] = $rec;	//把每一行得到的一维数组放入$arr
            //就得到二维数组
        }
        return $arr;	//这就是二维数组
    }
    //5，有一个方法可以返回单行数据并作为一维数组；
    //	比如：$sql = “select * from article where  id=8”;
    function GetRow($sql){
        $result = $this->query($sql);//这个结果是一个单行结果集（资源类型）
        $rec = mysqli_fetch_assoc($result);
        return $rec ;
    }

    private function query($sql){
        $result = mysqli_query($sql, $this->link);
        if($result === false){	//表示执行失败，原因可能很多！
            echo "<p>非常抱歉，语句执行失败，请参考如下信息：";
            echo "<br />错误提示：" . mysqli_error();
            echo "<br />错误代号：" . mysqli_errno();
            echo "<br />错误语句：" . $sql;
            echo "</p>";
            die();
            //也可以返回false
        }
        return $result;
    }
    function __sleep(){
        return array('arr_conf');
    }
    function __wakeup(){
        //恢复该对象（也就是反序列化）的时候，要同时连接上该数据库
        //就像“实例化”该对象的时候，就同时连接该数据库。
        $conf = $this->arr_conf;	//从恢复的对象中，取出连接信息

        //将该连接信息，去执行连库操作
        $this->link = mysqli_connect("{$conf['host']}:{$conf['port']}"
            ,$conf['user'], $conf['pass']) or die('连接失败');
        //这就是连接数据
        mysqli_query($this->link,"set names {$conf['charset']}");
        mysqli_query($this->link,"use {$conf['dbname']}");

    }
}


