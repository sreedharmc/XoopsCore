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
 * page module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Menus
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MenusMenus_menusForm extends XoopsThemeForm
{
    /**
     * @param MenusMenus_menus|XoopsObject $obj
     */
    public function __construct(MenusMenus &$obj)
    {
        $xoops = Xoops::getInstance();

        $title = $obj->isNew() ? sprintf( _AM_MENUS_ADD_MENUS ) : sprintf( _AM_MENUS_EDIT_MENUS );

        parent::__construct($title, 'form', 'admin_menus.php', 'post', true);

        //title
        $this->addElement(new XoopsFormText(_AM_MENUS_MENU_TITLE, 'title', 50, 255, $obj->getVar('title'), ''), true);

        $this->addElement(new XoopsFormHidden('id', $obj->getVar('id')));
        $this->addElement(new XoopsFormHidden( 'op', 'save' ) );
        $this->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit' ) );
    }
}