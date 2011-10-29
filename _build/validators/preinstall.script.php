<?php
/**
 * Mycomponent pre-install script
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 * @author Bob Ray <http://bobsguides.com>
 * 10/12/2011
 *
 * Mycomponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Mycomponent is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Mycomponent; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 */
/**
 * Description: Example validator checks for existence of getResources
 * @package botblockx
 * @subpackage build
 */
/**
 * @package botblockx
 * Validators execute before the package is installed. If they return
 * false, the package install is aborted. This example checks for
 * the installation of getResources and aborts the install if
 * it is not found.
 */

/* The $modx object is not available here. In its place we
 * use $object->xpdo
 */

return true;  /* ToDo: Remove this */



$modx =& $object->xpdo;


$modx->log(xPDO::LOG_LEVEL_INFO,'Running PHP Validator.');
switch($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        /* ToDo: Test for enabled fileAtime() and fileMtime() here */
        $modx->log(xPDO::LOG_LEVEL_INFO,'Checking for installed getResources snippet ');
        $success = true;
        /* Check for getResources */
        $gr = $modx->getObject('modSnippet',array('name'=>'getResources'));
        if ($gr) {
            $modx->log(xPDO::LOG_LEVEL_INFO,'getResources found - install will continue');
            unset($gr);
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR,'This package requires the getResources package. Please install it and reinstall BotBlockX');
            $success = false;
        }

        break;
   /* These cases must return true or the upgrade/uninstall will be cancelled */
   case xPDOTransport::ACTION_UPGRADE:
        $success = true;
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;
        break;
}

return $success;