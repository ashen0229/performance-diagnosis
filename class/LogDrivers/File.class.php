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


            $l=count($backtrace)-1;
            $step=array();
            $tmp=array();
            $called_class='';
            $called_func='';
            for($inx=$l;$inx>=0;$inx--){

                $tmp['file']=$backtrace[$inx]['file'];
                $tmp['line']=$backtrace[$inx]['line'];
                $tmp['class']=$called_class;
                $tmp['func']=$called_func;
                $called_class=!empty($backtrace[$inx]['class'])?$backtrace[$inx]['class']:"";
                $called_func=empty($backtrace[$inx]['function'])?'':$backtrace[$inx]['function'];
                $step[]=$tmp;
                $tmp=array();
            }
            $step=array_reverse($step);
            $arr[]=json_encode($step,JSON_UNESCAPED_UNICODE);

        file_put_contents($this->trace_file,implode($this->delimiter,$arr)."\n",FILE_APPEND);
    }


    public function finished($rows,$totalUsedTime,$maxConsumeMemory,$maxRealUsageMemory){
        $arr=array();
        $arr[]='summary';
        $arr[]=$rows.' rows has run';
        $arr[]=$totalUsedTime.' second used';
        $arr[]=$this->memoryFormat($maxConsumeMemory).' used';
        $arr[]=$this->memoryFormat($maxRealUsageMemory).' real used';

        file_put_contents ($this->trace_file,implode($this->delimiter,$arr).PHP_EOL,FILE_APPEND);
    }

}