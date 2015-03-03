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
 * Module_skeleton module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         module_skeleton
 * @since           1.00
 * @author          Xoops Development Team
 * @version         svn:$id$
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class ExtraItemfieldType
 */
class ExtraItemfieldType
{
    private $itemObj;
    private $itemfieldObj;

    /**
     * @param $itemObj
     * @param $itemfieldObj
     */
    public function __construct($itemObj, $itemfieldObj)
    {
        $this->itemObj = $itemObj;
        $this->itemfieldObj = $itemfieldObj;
    }

    public function getEditElement()
    {
    }

    public function getOutputValue()
    {
    }

    public function getValueForSave()
    {
    }

    public function insert()
    {
    }

    public function delete()
    {
    }

    public function itemfield_typeconfigs()
    {
    }

    public function search()
    {
    }
}
