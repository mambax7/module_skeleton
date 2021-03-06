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

$currentFile = basename(__FILE__);
include_once __DIR__ . '/admin_header.php';

// Check directories
if (!is_dir($module_skeleton->getConfig('uploadPath'))) {
    redirect_header('index.php', 3, _CO_MODULE_SKELETON_WARNING_NOUPLOADDIR);
    exit();
}

$op = Module_skeletonRequest::getString('op', 'itemfieldcategories.list');
switch ($op) {
    default:
    case 'itemfieldcategories.list':
        //  admin navigation
        xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);
        // buttons
        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_CO_MODULE_SKELETON_BUTTON_ITEMFIELDCATEGORY_ADD, "{$currentFile}?op=itemfieldcategory.add", 'add');
        echo $adminMenu->renderButton();
        //
        $itemfieldcategoryCount = $module_skeleton->getHandler('itemfieldcategory')->getCount();
        $GLOBALS['xoopsTpl']->assign('itemfieldcategories_count', $itemfieldcategoryCount);
        if ($itemfieldcategoryCount > 0) {
            $sortedItemfieldcategories = module_skeleton_sortItemfieldcategories(); // as array
            $GLOBALS['xoopsTpl']->assign('sorted_itemfieldcategories', $sortedItemfieldcategories);
            $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
        }
        $GLOBALS['xoopsTpl']->display("db:{$module_skeleton->getModule()->dirname()}_admin_itemfieldcategories_list.tpl");
        //
        include 'admin_footer.php';
        break;

    case 'itemfieldcategory.new':
    case 'itemfieldcategory.add':
    case 'itemfieldcategory.edit':
        //  admin navigation
        xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);
        // buttons
        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_CO_MODULE_SKELETON_BUTTON_ITEMFIELDCATEGORIES_LIST, "{$currentFile}?op=itemfieldcategories.list", 'list');
        echo $adminMenu->renderButton();
        //
        $itemfieldcategory_id = Module_skeletonRequest::getInt('itemfieldcategory_id', 0);
        if (!$itemfieldcategoryObj = $module_skeleton->getHandler('itemfieldcategory')->get($itemfieldcategory_id)) {
            // ERROR
            redirect_header($currentFile, 3, _CO_MODULE_SKELETON_ERROR_NOITEMFIELDCATEGORY);
            exit();
        }
        $form = $itemfieldcategoryObj->getForm();
        $form->display();
        //
        include 'admin_footer.php';
        break;

    case 'itemfieldcategory.save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $itemfieldcategory_id = Module_skeletonRequest::getInt('itemfieldcategory_id', 0, 'POST');
        $isNewCategory = ($itemfieldcategory_id == 0) ? true : false;
        $itemfieldcategory_pid = Module_skeletonRequest::getInt('itemfieldcategory_pid', 0, 'POST');
        $itemfieldcategory_title = Module_skeletonRequest::getString('itemfieldcategory_title', '', 'POST');
        $itemfieldcategory_description = $_REQUEST['itemfieldcategory_description']; //Module_skeletonRequest::getString('itemfieldcategory_description', '', 'POST');
        //
        $itemfieldcategory_weight = Module_skeletonRequest::getInt('itemfieldcategory_weight', 0, 'POST');
        $itemfieldcategory_status = 0; // IN PROGRESS
        $itemfieldcategory_version = 0; // IN PROGRESS
        $itemfieldcategory_owner_uid = Module_skeletonRequest::getInt('itemfieldcategory_owner_uid', 0, 'POST');
        $itemfieldcategory_date = time();
        //
        $itemfieldcategoryObj = $module_skeleton->getHandler('itemfieldcategory')->get($itemfieldcategory_id);
        // a itemfieldcategory can not be a child of itself
        if (!$itemfieldcategoryObj->isNew()) {
            $itemfieldcategoryObjs = $module_skeleton->getHandler('itemfieldcategory')->getObjects();
            $itemfieldcategoryObjsTree = new Module_skeletonObjectTree($itemfieldcategoryObjs, 'itemfieldcategory_id', 'itemfieldcategory_pid');
            $childItemfieldcategoryObjs = $itemfieldcategoryObjsTree->getAllChild($itemfieldcategory_id);
            //$childcats = $module_skeleton->getHandler('itemfieldcategory')->getChildItemcategories($childitemfieldcategoryObjs);
            if ($itemfieldcategory_pid == $itemfieldcategory_id || in_array($itemfieldcategory_pid, array_keys($childItemfieldcategoryObjs))) {
                // ERROR
                xoops_cp_header();
                $itemfieldcategoryObj->setErrors(_AM_MODULE_SKELETON_ERROR_ITEMFIELDCATEGORY_CHILDASPARENT);
                echo $itemfieldcategoryObj->getHtmlErrors();
                xoops_cp_footer();
                exit();
            }
        }
        //
        $itemfieldcategoryObj->setVar('itemfieldcategory_title', $itemfieldcategory_title);
        $itemfieldcategoryObj->setVar('itemfieldcategory_description', $itemfieldcategory_description);
        $itemfieldcategoryObj->setVar('dohtml', isset($_POST['dohtml']));
        $itemfieldcategoryObj->setVar('dosmiley', isset($_POST['dosmiley']));
        $itemfieldcategoryObj->setVar('doxcode', isset($_POST['doxcode']));
        $itemfieldcategoryObj->setVar('doimage', isset($_POST['doimage']));
        $itemfieldcategoryObj->setVar('dobr', isset($_POST['dobr']));
        $itemfieldcategoryObj->setVar('itemfieldcategory_pid', $itemfieldcategory_pid);
        //
        $itemfieldcategoryObj->setVar('itemfieldcategory_weight', $itemfieldcategory_weight);
        $itemfieldcategoryObj->setVar('itemfieldcategory_status', $itemfieldcategory_status); // IN PROGRESS
        $itemfieldcategoryObj->setVar('itemfieldcategory_version', $itemfieldcategory_version); // IN PROGRESS
        $itemfieldcategoryObj->setVar('itemfieldcategory_owner_uid', $itemfieldcategory_owner_uid);
        $itemfieldcategoryObj->setVar('itemfieldcategory_date', $itemfieldcategory_date);
        //
        if (!$module_skeleton->getHandler('itemfieldcategory')->insert($itemfieldcategoryObj)) {
            // ERROR
            xoops_cp_header();
            echo $itemfieldcategoryObj->getHtmlErrors();
            xoops_cp_footer();
            exit();
        }
        $itemfieldcategory_id = (int) $itemfieldcategoryObj->getVar('itemfieldcategory_id');
        // save permissions
        $read_groups = Module_skeletonRequest::getArray('itemfieldcategory_read', array(), 'POST');
        module_skeleton_savePermissions($read_groups, $itemfieldcategory_id, 'itemfieldcategory_read');
        $write_groups = Module_skeletonRequest::getArray('itemfieldcategory_write', array(), 'POST');
        module_skeleton_savePermissions($write_groups, $itemfieldcategory_id, 'itemfieldcategory_write');
        //
        if ($isNewCategory) {
            // Notify of new itemfieldcategory
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS
        } else {
            // Notify of itemfieldcategory modified
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS
        }
        //
        redirect_header($currentFile, 3, _CO_MODULE_SKELETON_ITEMFIELDCATEGORY_STORED);
        break;

    case 'itemfieldcategory.delete':
        $itemfieldcategory_id = Module_skeletonRequest::getInt('itemfieldcategory_id', 0);
        $itemfieldcategoryObj = $module_skeleton->getHandler('itemfieldcategory')->get($itemfieldcategory_id);
        if (!$itemfieldcategoryObj) {
            redirect_header($currentFile, 3, _CO_MODULE_SKELETON_ERROR_NOITEMFIELDCATEGORY);
            exit();
        }
        if (Module_skeletonRequest::getBool('ok', false, 'POST') == true) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($module_skeleton->getHandler('itemfieldcategory')->delete($itemfieldcategoryObj)) {
                redirect_header($currentFile, 3, _CO_MODULE_SKELETON_ITEMFIELDCATEGORY_DELETED);
            } else {
                // ERROR
                xoops_cp_header();
                echo $itemfieldcategoryObj->getHtmlErrors();
                xoops_cp_footer();
                exit();
            }
        } else {
            xoops_cp_header();
            xoops_confirm(
                array('ok' => true, 'op' => 'itemfieldcategory.delete', 'itemfieldcategory_id' => $itemfieldcategory_id),
                $_SERVER['REQUEST_URI'],
                _CO_MODULE_SKELETON_ITEMFIELDCATEGORY_DELETE_AREUSURE,
                _DELETE
            );
            xoops_cp_footer();
        }
        break;

    case 'itemfieldcategories.reorder':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
        }

        if (isset($_POST['new_itemfieldcategory_weights']) && count($_POST['new_itemfieldcategory_weights']) > 0) {
            $new_itemfieldcategory_weights = $_POST['new_itemfieldcategory_weights'];
            $ids = array();
            foreach ($new_itemfieldcategory_weights as $itemfieldcategory_id => $new_itemfieldcategory_weight) {
                $itemfieldcategoryObj = $module_skeleton->getHandler('itemfieldcategory')->get($itemfieldcategory_id);
                $itemfieldcategoryObj->setVar('itemfieldcategory_weight', $new_itemfieldcategory_weight);
                if (!$module_skeleton->getHandler('itemfieldcategory')->insert($itemfieldcategoryObj)) {
                    redirect_header($currentFile, 3, $itemfieldcategoryObj->getErrors());
                }
                unset($itemfieldcategoryObj);
            }
            redirect_header($currentFile, 3, _CO_MODULE_SKELETON_ITEMFIELDCATEGORIES_REORDERED);
            exit();
        }
        break;
}
