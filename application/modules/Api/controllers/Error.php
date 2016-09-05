<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 2015/4/7
 * Time: 9:45
 */
class ErrorController extends Yaf\Controller_Abstract
{

    public function errorAction($exception)
    {
        $this->getView()->assign("exception", $exception);
        $log = Yaf\Application::app()->getConfig()->log->toArray();
        if($log['enable']){
            $logFile = $log['dir'].date('Ymd').'.log';
            error_log(date('【Y-m-d H:i:s】').$exception->getMessage().PHP_EOL , 3 , $logFile);
        }
        switch ($exception->getCode()):
            case Yaf\ERR\NOTFOUND\MODULE:
            case Yaf\ERR\NOTFOUND\CONTROLLER:
            case Yaf\ERR\NOTFOUND\ACTION:
            case Yaf\ERR\NOTFOUND\VIEW:
                return $this->_pageNotFound();
            default:
                return $this->_unknownError();
        endswitch;
    }

    private function _pageNotFound()
    {
        $this->getResponse()->setHeader($this->getRequest() -> getServer( 'SERVER_PROTOCOL' ), '404 Not Found');
        $this->getResponse()->setBody("page not found");
        $this->getResponse()->response();
    }

    private function _unknownError()
    {
        $this->getResponse()->setHeader($this->getRequest() -> getServer( 'SERVER_PROTOCOL' ), '404 Not Found');
        $this->getResponse()->setBody("page not found");
        $this->getResponse()->response();
    }

}
