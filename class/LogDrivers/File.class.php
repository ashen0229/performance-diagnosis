<?php
require PERFORMANCE_DIAGNOSIS_LOGDRIVER_PATH.'driver.class.php';


class File extends driver {
    private $path='';
    private $trace_file='';
    public function __construct($config = null)
    {
        if(empty($config['LogFilePath'])){
            throw new Exception('未指定存放日志目录',500);
        }elseif(!is_dir($config['LogFilePath']) && !mkdir($config['LogFilePath'],0666,true)){
            throw new Exception('存放日志目录不正确',500);
        }else{
            $this->path=$config['LogFilePath'];
        }
        parent::__construct($config);
    }

    public function start(){
        $this->trace_file=$this->path.date("YmdHis").'-'.$this->trace_id;
        $explode_str='-';
        while(file_exists($this->trace_file)){
            $this->trace_file.=$explode_str.rand(0,9);
            $explode_str='';
        }
        $arr=array('trace_id','consumeTime','currentUsedMemory','usedMemory','position');
        file_put_contents($this->trace_file,implode($this->delimiter,$arr).PHP_EOL);
    }

    public function log($usedTime,$currentMemory,$usedMemory,$backtrace){
        $arr=array();
        $arr[]=$this->trace_id;
        $arr[]=$usedTime;
        $arr[]=$this->memoryFormat($currentMemory);
        $arr[]=$this->memoryFormat($usedMemory);
        $arr[]=$backtrace[0]['file'].':'.$backtrace[0]['line'];
        file_put_contents($this->trace_file,implode($this->delimiter,$arr)."\n",FILE_APPEND);
    }


    public function finished($rows,$totalUsedTime,$maxConsumeMemory,$maxRealUsageMemory){
        $arr=array();
        $arr[]=$rows.' rows has run';
        $arr[]=$totalUsedTime.' second used';
        $arr[]=$this->memoryFormat($maxConsumeMemory).' used';
        $arr[]=$this->memoryFormat($maxRealUsageMemory).' real used';

        file_put_contents ($this->trace_file,'summary: '.implode("; ",$arr).PHP_EOL,FILE_APPEND);
    }

}