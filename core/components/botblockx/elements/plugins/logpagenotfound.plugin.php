<?php

/**
 * LogPageNotFound Plugin
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 *
 * LogPageNotFound is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * LogPageNotFound is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * LogPageNotFound; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 * @subpackage build
 */


if (!function_exists("get_host")) {
    function get_host($ip){
            $ptr = implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
            $host = dns_get_record($ptr,DNS_PTR);
            if ($host == null) return $ip;
            else return $host[0]['target'];
    }
}

if (!function_exists("my_debug")) {
    function my_debug($message, $clear = false) {
        global $modx;

        $chunk = $modx->getObject('modChunk', array('name' => 'PageNotFoundLog'));
        if (!$chunk) {
            $modx->log(xPDO::LOG_LEVEL_ERROR, 'LogPageNotFound failed to retrieve the PageNotFoundLog chunk');
        } else {
            if ($clear) {
                $content = '';
            } else {
                $content = $chunk->getContent();
            }
        }
        $content .= $message . "\n";
        $chunk->setContent($content);
        $chunk->save();
    }
}

$data['page'] = htmlentities(strip_tags($_SERVER['REQUEST_URI']));
if ($scriptProperties['verbose'] ) {
    $data['ip'] = htmlentities(strip_tags($_SERVER['REMOTE_ADDR']));
    $data['userAgent'] = htmlentities(strip_tags($_SERVER['HTTP_USER_AGENT']));
    $data['host'] = get_host($data['ip']);
    $msg = implode('`', $data);
} else {
    $msg = $data['page'];
}
if ($scriptProperties['target'] == 'CHUNK') {
    my_debug($msg);
} else {
    $fp = fopen(MODX_CORE_PATH . 'blocklogs/pagenotfound.log', 'a');
    if ($fp) {
        fwrite($fp,$msg . "\n");
        fclose($fp);
    }
}

    return '';