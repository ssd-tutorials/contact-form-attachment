<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\View\Helper;

/**
 * @uses       \Zend\View\Helper\HtmlObject
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HtmlQuicktime extends HtmlObject
{
    /**
     * Default file type for a movie applet
     *
     */
    const TYPE = 'video/quicktime';

    /**
     * Object classid
     *
     */
    const ATTRIB_CLASSID  = 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B';

    /**
     * Object Codebase
     *
     */
    const ATTRIB_CODEBASE = 'http://www.apple.com/qtactivex/qtplugin.cab';

    /**
     * Default attributes
     *
     * @var array
     */
    protected $_attribs = array('classid'  => self::ATTRIB_CLASSID,
                                'codebase' => self::ATTRIB_CODEBASE);

    /**
     * Output a quicktime movie object tag
     *
     * @param string $data The quicktime file
     * @param array  $attribs Attribs for the object tag
     * @param array  $params Params for in the object tag
     * @param string $content Alternative content
     * @return string
     */
    public function direct($data = null, array $attribs = array(), array $params = array(), $content = null)
    {
        if ($data == null) {
            throw new \InvalidArgumentException('HTMLQuicktime: missing argument. $data is required in htmlQuicktime($data, array $attribs = array(), array $params = array(), $content = null)');
        }
        
        // Attrs
        $attribs = array_merge($this->_attribs, $attribs);

        // Params
        $params = array_merge(array('src' => $data), $params);

        return parent::direct($data, self::TYPE, $attribs, $params, $content);
    }
}
