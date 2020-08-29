# PHP性能诊断小插件
## performance diagnosis for your PHP code

**注意**
>不要在生产环境长期打开<br />不要在生产环境长期打开<br />不要在生产环境长期打开


**使用**
>引入class目录下的 `performanceDiagnosis.class.php`

```
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

```

>在要诊断的PHP文件合适位置添加 `declare(ticks=1);`

好了.完事,记得配置的`enable`设为`true`

**运行**
>正常访问你的WEB或CLI,<br />按照你的日志记录形式,OUTPUT会直接输出,FILE则会写进文件,每一次执行都会有一个文件.



**其它**
>再次重申,不要在生产环境长期打开.该小工具打开情况下,会占用很多资源,以及程序速度会下降很多.


**最后**
>该工具除了监控每一行的运行时间.更多时候本可以拿来做程序运行的追踪.特别是大型系统,相信你懂的<br />日志格式可以自行调整,都是最基础的PHP语句.<br />最后只能祝你玩得愉快了.

**生产中的应用情况**
压测时,50QPS的情况下,未加该插件时, 平均响应1.2S的程序,加完该插件,平均响应去到7S.<br />
所以,再次重申,<br />
切忌在生产环境中长期打开该功能.只在必要的时候<br />
切忌在生产环境中长期打开该功能.只在必要的时候<br />
切忌在生产环境中长期打开该功能.只在必要的时候<br />
