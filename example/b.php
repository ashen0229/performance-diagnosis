<?php
declare(ticks=1);
echo 'nice';

class b{

    public function b1($text,$a){
        echo $text;
        mysqli_connect('127.0.0.1','root','123456','dj');
        $c=new c();
        $c->c1();
        sleep(1);
    }

    public static function b2($msg){
        for($i=0;$i<10;$i++){
            echo $msg;
        }
    }
}