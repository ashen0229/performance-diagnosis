<?php
class driver{
    protected $trace_id='';
    protected $delimiter='';
    public function __construct($config=null)
    {
        $this->delimiter=!empty($config['Delimiter'])?$config['Delimiter']:' ';
    }


    public function setTraceId($traceId){
        $this->trace_id=$traceId;
    }

    public function memoryFormat($usedMemory){
        $unit=array('b','kb','mb','gb','tb','pb');
        $inx=0;
        $used=$usedMemory;
        while(true){
            if($used>1024){
                $used=bcdiv($used,1024,2);
                $inx++;
            }else{
                break;
            }
        }
        return $used.$unit[$inx];
    }


}