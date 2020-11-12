<?php
// Copyright 1999-2020. Plesk International GmbH. All Rights Reserved.

use PleskExt\MyStartPage\Helper;

class Modules_MyStartPage_ConfigDefaults extends pm_Hook_ConfigDefaults
{
    public function getDefaults()
    {
        return [
            Helper::SETTING_KEY => '',
        ];
    }
}
