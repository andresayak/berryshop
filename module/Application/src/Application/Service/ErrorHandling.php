<?php 
namespace Application\Service;
 
class ErrorHandling
{
    protected $logger;
    protected $_rotate_logger;
 
    function __construct(\Zend\Log\Logger $logger)
    {
        $this->logger = $logger;
    }
 
    function logException(\Exception $e)
    {
        $trace = $e->getTraceAsString();
        $log = "Exception:\n" . $e->__toString();
        $log .= "\nTrace:\n" . $trace;
        if(isset($_SERVER['argv']) and count($_SERVER['argv']))
            $log.="\n\n SERVER:".print_r($_SERVER['argv'], true);
        if(count($_GET))
            $log.="\n\n GET: ".print_r($_GET, true);
        if(count($_POST))
            $log.="\n\n POST: ".print_r($_POST, true);
        $this->logger->err($log);
    }
    
    public function rotate() 
    {
        $date = date('Y-m-d', strtotime('-1 day'));
        $file = './data/logs/error_' . $date . '.txt';
        
        $error = null;
        $errors = array();
        if(is_file($file)){
            $handle = @fopen($file, "r");
            if ($handle) {
                $status = false;
                while (($str = fgets($handle, 4096)) !== false) {
                    if(preg_match('/^\d{4}\-\d{2}\-\d{2}T\d{2}\:\d{2}\:\d{2}\+\d{2}:\d{2}[^\:]*\: (.*)$/', $str, $match)){
                        $status = true;
                        if($error !== null){
                            $key = md5($error);
                            if(!isset($errors[$key]))
                                $errors[$key] = array('msg'=>$error, 'count'=>1);
                            else $errors[$key]['count']++;
                        }
                        $error = $match[1];
                    }else{
                        if(preg_match('/^Stack trace\:/', $str)){
                            $status = false;
                        }
                        if($status)
                            $error.= $str;
                    }
                }
                if ($error !== null) {
                    $key = md5($error);
                    if (!isset($errors[$key]))
                        $errors[$key] = array('msg' => $error, 'count' => 1);
                    else $errors[$key]['count']++;
                }
                if (!feof($handle)) {
                    echo "Error: unexpected fgets() fail\n";
                    exit;
                }
                fclose($handle);
            }
        }else{
            echo 'file not found ['.$file.']'."\n";exit;
        }
        $this->_sendMail($errors);
        return $errors;
    }

    protected function _sendMail($logArray) 
    {
        $bodyErrMessage = '';
        if (count($logArray) == 0) 
            return;
        $bodyErrMessage = '<table width="100%">';
        foreach($logArray as $logElement)
           $bodyErrMessage.= '<tr><td>'.$logElement['msg'].'</td><td>'.$logElement['count'].'</td></tr>';
        $bodyErrMessage.= '</table>';
        $this->logger->warn($bodyErrMessage);
    }
}