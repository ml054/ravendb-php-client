<?php


namespace RavenDB\Client\Http\Logger;

use Analog\Analog;

class LogFactory extends Analog
{
    // TODO LOGGER CLASS TO COMPLETE
    private object $log;
    private $storageType;
    const SEVERITY_URGENT = Analog::URGENT;
    const SEVERITY_ALERT = Analog::ALERT;
    const SEVERITY_CRITICAL = Analog::CRITICAL;
    const SEVERITY_ERROR = Analog::ERROR;
    const SEVERITY_WARNING = Analog::WARNING;
    const SEVERITY_NOTICE = Analog::NOTICE;
    const SEVERITY_INFO = Analog::INFO;
    const SEVERITY_DEBUG = Analog::DEBUG;

    /**
     * @throws
    */
    public function __construct()
    {

    }

}