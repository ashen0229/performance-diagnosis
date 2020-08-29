<?php

class analysis{
    private $TRACE_ID='';
    private $result=array();
    private $last=array();
    public function doAnalysis($LogFile,$Delimiter=' '){
        $content='';
        if(file_exists($LogFile)){
            $content=file_get_contents($LogFile);
        }else{
            return null;
        }
        $lines=explode("\n",$content);
        foreach($lines as $line){
            $items=explode($Delimiter,$line);
            if(strpos($items[0],'TRACE_')===0){
                $position=json_decode($items[4],true);
                if(!$position){
                    continue;
                }
                if($this->last && $this->last['file']==$position[0]['file'] && $this->last['class']==$position[0]['class'] && $this->last['func']==$position[0]['func']){
                    $this->last['to_line']=$position[0]['line'];
                    $this->last['consume']=bcadd($this->last['consume'],$items[1],6);
                    $this->last['cmdCounts']++;
                }else{
                    if($this->last){
                        $this->result[]=$this->last;
                        $this->last=array();
                    }
                    $this->last['file']=$position[0]['file'];
                    $this->last['cmdCounts']=1;
                    $this->last['from_line']=$position[0]['line'];
                    $this->last['to_line']=$position[0]['line'];
                    $this->last['class']=$position[0]['class'];
                    $this->last['func']=$position[0]['func'];
                    $this->last['consume']=$items[1];
                }
            }
        }
        if($this->last){
            $this->result[]=$this->last;
        }
        return $this->result;

    }
}