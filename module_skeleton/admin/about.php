<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

include_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$aboutAdmin = new ModuleAdmin();

echo $aboutAdmin->addNavigation('about.php');
echo $aboutAdmin->renderAbout('xoopsfoundation@gmail.com', false);

include 'admin_footer.php';
