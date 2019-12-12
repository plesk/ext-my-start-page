<?php
// Copyright 1999-2019. Plesk International GmbH. All Rights Reserved.

namespace PleskExt\MyStartPage;

class Helper
{
    public const SESSION_KEY = 'myStartPageRedirect';
    public const SETTING_KEY = 'myStartPageLink';

    /**
     * Gets the stored redirect URL
     *
     * @return string
     */
    public static function getRedirectUrl(): string
    {
        $redirectUrl = \pm_Settings::get(self::SETTING_KEY, '');

        if ($redirectUrl === '') {
            $redirectUrl = \pm_Config::get(self::SETTING_KEY, '');
        }

        if (!self::isValid($redirectUrl)) {
            return '';
        }

        return $redirectUrl;
    }

    /**
     * Checks the URL for validity
     *
     * @param string $url
     *
     * @return bool
     */
    public static function isValid(string $url): bool
    {
        // Only internal URLs are valid
        if (preg_match('@https?://(www\.)?@i', $url) && stripos($url, $_SERVER['HTTP_HOST']) === false) {
            return false;
        }

        // Prepend the host value for the filter_var validation check
        if (stripos($url, $_SERVER['HTTP_HOST']) === false) {
            $url = 'https://' . $_SERVER['HTTP_HOST'] . '/' . $url;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }

    /**
     * Gets the clean URL path
     *
     * @param string $url
     *
     * @return string
     */
    public static function getCleanUrlPath(string $url): string
    {
        $urlRaw = parse_url($url);

        if (empty($urlRaw['path'])) {
            return '';
        }

        $urlPath = $urlRaw['path'];

        if (!empty($urlRaw['query'])) {
            $urlPath .= '?' . $urlRaw['query'];
        }

        return self::removePrecedingSlash($urlPath);
    }

    /**
     * Removes all preceding slashes if set
     *
     * @param string $url
     *
     * @return string
     */
    private static function removePrecedingSlash(string $url): string
    {
        return ltrim($url, '/');
    }
}
