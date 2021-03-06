<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Log
 */

namespace Zend\Log\Writer\FirePhp;

/**
 * @category   Zend
 * @package    Zend_Log
 * @subpackage Writer
 */
interface FirePhpInterface
{
    public function getEnabled();
    public function error($line);
    public function warn($line);
    public function info($line);
    public function trace($line);
    public function log($line);
}
