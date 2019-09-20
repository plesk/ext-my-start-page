<?php
// Copyright 1999-2019. Plesk International GmbH. All Rights Reserved.

class Modules_MyStartPage_ContentInclude extends pm_Hook_ContentInclude
{
    public function init()
    {
        $authUrl = pm_Context::getActionUrl('index', 'auth');
        $requestUrl = $_SERVER['REQUEST_URI'];

        $excludeUrls = [
            $authUrl,
            '/server/key_info.php',
            '/server/key_upload.php',
            '/server/key_update.php',
            '/server/key_revert.php',
            '/plesk/license/',
            '/smb/redirect/pleskin',
            '/smb/settings/tools-proxy?url=/plesk/license/',
            '/smb/help/',
            '/admin/license/get-trial',
        ];

        foreach ($excludeUrls as $excludeUrl) {
            if (0 === strncmp($requestUrl, $excludeUrl, strlen($excludeUrl))) {
                return;
            }
        }

        $myStartPageRedirect = $_SESSION['myStartPageRedirect'] ?? false;

        if (!$myStartPageRedirect) {
            $myStartPageLink = pm_Settings::get('myStartPageLink');

            if (!empty($myStartPageLink)) {
                $_SESSION['myStartPageRedirect'] = true;

                header("Location: {$myStartPageLink}");
                die();
            }
        }
    }
}
