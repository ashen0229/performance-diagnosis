<?php




define('PERFORMANCE_DIAGNOSIS_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('PERFORMANCE_DIAGNOSIS_LOGDRIVER_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'LogDrivers' . DIRECTORY_SEPARATOR);
define('PERFORMANCE_DIAGNOSIS_LANG_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'Lang' . DIRECTORY_SEPARATOR);
define('TRACE_ID','TRACE_'. ceil(microtime(true) * 1000));

class performanceDiagnosis
{


    static $instance = null;
    static $register_status = false;


    private $_LogType = 'OUTPUT';
    private $_LogDriver = null;

    /**
     * @var int 要记录的时间阀值,超过该数值则记录，0则全记录（单位：微秒）
     */
    private $_DoLogTimeConsuming = 0;

    /**
     * @var int 要记录的内存消耗阀值,超过该数值则记录，0则全记录（单位：byte）
     * 注意：该值并不能精准，只能做为参考，建议不要使用该阀值做为开关值
     */
    private $_DoLogMemoryConsuming = 0;




    private $_INIT_TIME=null;

    private $_CMD_COUNT=0;
    /**
     *
     * @param array $config
     * @param bool $reConstruct
     * @return performanceDiagnosis|null
     * @throws exception
     */
    public static function init($config = null, $reConstruct = false)
    {
        if (!self::$instance || $reConstruct) {
            if (!$config) {
                throw new Exception('配置丢失', 500);
            }
            self::$instance = new performanceDiagnosis($config);
        }
        return self::$instance;
    }


    public function __construct($config)
    {
        $this->_LogType = empty($config['LogType']) ? 'OUTPUT' : $config['LogType'];
        if (!file_exists(PERFORMANCE_DIAGNOSIS_LOGDRIVER_PATH . ucfirst(strtolower($this->_LogType)) . '.class.php')) {
            throw new Exception('未知的日志记录类型[' . $this->_LogType . ']', 500);
        }
        $driverClass = ucfirst(strtolower($this->_LogType));
        require PERFORMANCE_DIAGNOSIS_LOGDRIVER_PATH . $driverClass . '.class.php';

        $this->_LogDriver = new $driverClass($config);
        $this->_LogDriver->setTraceId(TRACE_ID);

        $this->_DoLogMemoryConsuming = intval(empty($config['DoLogMemoryConsuming']) ? 0 : $config['DoLogMemoryConsuming']);
        $this->_DoLogTimeConsuming = intval(empty($config['DoLogTimeConsuming']) ? 0 : $config['DoLogTimeConsuming']);


        if (!performanceDiagnosis::$register_status && !empty($config['enable'])) {
            performanceDiagnosis::$register_status = true;
            $this->_INIT_TIME=microtime();
            $this->_LogDriver->start();
            register_tick_function(array(&$this, 'log'));
            register_shutdown_function(array(&$this, 'finished'));
        }
    }


    public function finished(){
        $mul_base=1000000;
        $endTime=explode(" ",microtime());
        $startTime = $this->_INIT_TIME;
        $startTime=explode(' ',$startTime);
        $totalUsedTime = bcsub($endTime[1] . '.' . ($endTime[0] * $mul_base), $startTime[1] . '.' . ($startTime[0] * $mul_base), 6);
        $maxConsumeMemory=memory_get_peak_usage();
        $maxRealUsageMemory=memory_get_peak_usage(true);
        $this->_LogDriver->finished($this->_CMD_COUNT,$totalUsedTime,$maxConsumeMemory,$maxRealUsageMemory);

    }

    public function log()
    {
        $this->_CMD_COUNT++;
        $backtrace = debug_backtrace();
        $mul_base=1000000;
        $currentMemory = memory_get_usage(true);
        $currentTime = explode(" ",microtime());
        static $lasttime = null;
        static $lastMemoryUsed = 0;
        $startTime = $this->_INIT_TIME;

        if ($lasttime) {
            $startTime = $lasttime;
        }
        $startTime=explode(' ',$startTime);

        $usedTime = bcsub($currentTime[1] . '.' . ($currentTime[0] * $mul_base), $startTime[1] . '.' . ($startTime[0] * $mul_base), 6);

        $usedMemory = bcsub($currentMemory , $lastMemoryUsed);
        if (!$this->_DoLogMemoryConsuming || !$this->_DoLogTimeConsuming || $usedMemory >= $this->_DoLogMemoryConsuming || bcmul($usedTime, $mul_base) >= $this->_DoLogTimeConsuming) {
            $this->_LogDriver->log($usedTime,$currentMemory, $usedMemory, $backtrace);
        }
        $lastMemoryUsed = memory_get_usage(true);
        $lasttime = microtime();
    }


}