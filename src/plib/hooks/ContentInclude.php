<?php
// Copyright 1999-2019. Plesk International GmbH. All Rights Reserved.

use PleskExt\MyStartPage\Helper;

class Modules_MyStartPage_ContentInclude extends pm_Hook_ContentInclude
{
    public function init()
    {
        $authUrl = pm_Context::getActionUrl('index', 'auth');

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
            if (0 === strncmp($_SERVER['REQUEST_URI'], $excludeUrl, strlen($excludeUrl))) {
                return;
            }
        }

        $myStartPageRedirect = $_SESSION[Helper::SESSION_KEY] ?? false;

        if ($myStartPageRedirect) {
            return;
        }

        $redirectUrl = Helper::getRedirectUrl();

        if ($redirectUrl !== '') {
            $_SESSION[Helper::SESSION_KEY] = true;
            Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->gotoUrl($redirectUrl);

            exit;
        }
    }
}
