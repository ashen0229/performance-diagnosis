<?php
require PERFORMANCE_DIAGNOSIS_LOGDRIVER_PATH.'driver.class.php';


class Output extends driver {

    public function __construct($config = null)
    {
        parent::__construct($config);
    }

    public function start(){
        echo 'trace_id consumeTime currentUsedMemory usedMemory position'.PHP_EOL;
    }


    public function log($usedTime,$currentMemory,$usedMemory,$backtrace){
        $arr=array();
        $arr[]=$this->trace_id;
        $arr[]=$usedTime;
        $arr[]=$this->memoryFormat($currentMemory);
        $arr[]=$this->memoryFormat($usedMemory);
        $arr[]=$backtrace[0]['file'].':'.$backtrace[0]['line'];
        if(count($backtrace)>1){
            $l=count($backtrace)-1;
            $tmp=array();

            for($inx=$l;$inx>0;$inx--){
                $str=$backtrace[$inx]['file'].'('.$backtrace[$inx]['line'].')';
                $str.=!empty($backtrace[$inx]['class'])?($backtrace[$inx]['class']."::"):"";
                $str.=$backtrace[$inx]['function'].'()';
                $tmp[]=$str;
            }
            $arr[]=implode('->',$tmp);
        }
        echo implode($this->delimiter,$arr).PHP_EOL;
    }

    public function finished($rows,$totalUsedTime,$maxConsumeMemory,$maxRealUsageMemory){
        $arr=array();
        $arr[]=$rows.' rows has run';
        $arr[]=$totalUsedTime.' second used';
        $arr[]=$this->memoryFormat($maxConsumeMemory).' used';
        $arr[]=$this->memoryFormat($maxRealUsageMemory).' real used';

        echo 'summary: '.implode("; ",$arr).PHP_EOL;
    }

}