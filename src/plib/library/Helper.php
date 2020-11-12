<?php
// Copyright 1999-2020. Plesk International GmbH. All Rights Reserved.

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
        if (preg_match('@https?://(www\.)?@i', $url) && stripos($url, self::getHost()) === false) {
            return false;
        }

        // Prepend the host value for the filter_var validation check
        $url = self::addHost($url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the host required for validity check and redirect without the trailing slash
     *
     * @return string
     */
    public static function getHost(): string
    {
        return rtrim((string) $_SERVER['HTTP_HOST'], '/');
    }

    /**
     * Adds the host value if not set already
     *
     * @param string $url
     *
     * @return string
     */
    public static function addHost(string $url): string
    {
        if (empty($url)) {
            return '';
        }

        if (stripos($url, self::getHost()) === false) {
            $url = 'https://' . self::getHost() . '/' . $url;
        }

        return $url;
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
