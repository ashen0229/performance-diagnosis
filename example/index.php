<?php
$config=array(

    'LogType'=>'FILE',              //日志记录形式，支持： FILE（文件形式） OUTPUT(直接输出)
    'LogFilePath'=>__DIR__.DIRECTORY_SEPARATOR.'Log'.DIRECTORY_SEPARATOR,                  //当日志记录是FILE时,该参数必须,指向可写目录


    'DoLogTimeConsuming'=>0,        //要记录的时间阀值,超过该数值则记录，0则全记录（单位：微秒）
    'DoLogMemoryConsuming'=>0,      //记录的内存消耗阀值,超过该数值则记录，0则全记录（单位：byte）
    'enable'=>true,                 //是否启用
    'Delimiter'=>'|'                //分隔符
);

require __DIR__.DIRECTORY_SEPARATOR.'../class/performanceDiagnosis.class.php';

performanceDiagnosis::init($config);
declare(ticks=1);



for($i=0;$i<5;$i++){
    $a=$i;
}

include __DIR__.DIRECTORY_SEPARATOR.'b.php';
include __DIR__.DIRECTORY_SEPARATOR.'c.php';
include __DIR__.DIRECTORY_SEPARATOR.'d.php';
b::b2('test');
$b=new b();
$b->b1('haha',$a);


sleep(3);