<?php
// Copyright 1999-2019. Plesk International GmbH. All Rights Reserved.

namespace PleskExt\MyStartPage;

class Helper
{
    public const SESSION_KEY = 'myStartPageRedirect';
    public const SETTING_KEY = 'myStartPageLink';

    public static function isValid(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }
}
