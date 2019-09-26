<?php
// Copyright 1999-2019. Plesk International GmbH. All Rights Reserved.

abstract class Modules_MyStartPage_Helper
{
    /**
     * @param string $url
     *
     * @return bool
     */
    public static function isValid($url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }
}
