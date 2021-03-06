<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * banners module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

class BannersBannerclient extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('bannerclient_cid', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('bannerclient_uid', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('bannerclient_name', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('bannerclient_extrainfo', XOBJ_DTYPE_TXTAREA, null, false);
    }
    public function get_new_id()
    {
        return Xoops::getInstance()->db()->getInsertId();
    }
}

class BannersBannerclientHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsConnection $db
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'banners_bannerclient', 'BannersBannerclient', 'bannerclient_cid', 'bannerclient_name');
    }
}