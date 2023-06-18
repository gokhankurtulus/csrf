<?php
/**
 * @author Gökhan Kurtuluş @gokhankurtulus
 * Date: 18.06.2023 Time: 09:05
 */

namespace Csrf;
class Csrf
{
    /**
     * @param string $name
     * @param int $expiry default 600 = 10mins
     * @return \stdClass|null
     */
    public static function newToken(string $name, int $expiry = 600): ?\stdClass
    {
        if (!self::isSessionStarted()) {
            return null;
        }

        $token = new \stdClass();
        $token->name = $name;
        $token->expiry = time() + $expiry;
        $token->value = md5(uniqid(mt_rand(), true));

        $_SESSION['tokens'][$name] = $token;
        return $token;
    }

    /**
     * @param string $name
     * @return \stdClass|null
     */
    public static function getToken(string $name): ?\stdClass
    {
        if (!self::isSessionStarted()) {
            return null;
        }

        return $_SESSION['tokens'][$name] ?? null;
    }

    public static function isSessionStarted(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @param int $expiry default 600 = 10mins
     * @return string|null
     */
    public static function createInput(string $name, int $expiry = 600): ?string
    {
        if (!self::isSessionStarted() || empty($name)) {
            return null;
        }

        $token = self::getToken($name) ?? self::newToken($name, $expiry);

        if (time() > $token->expiry) {
            $token = self::newToken($name, $expiry);
        }

        return sprintf('<input type="hidden" id="token" name="%s" value="%s">', $name, $token->value);
    }

    /**
     * @param string $name
     * @param bool $unsetTokenIfVerified
     * @param string|null $sentToken
     * @return bool
     */
    public static function verify(string $name, bool $unsetTokenIfVerified = false, ?string $sentToken = null): bool
    {
        if (!self::isSessionStarted() || empty($name)) {
            return false;
        }

        $sentToken = $sentToken ?? ($_POST['token'] ?? null);
        if (empty($sentToken)) {
            return false;
        }

        $token = self::getToken($name);
        if (empty($token)) {
            return false;
        }

        if (time() > $token->expiry) {
            self::unsetToken($name);
            return false;
        }

        if ($unsetTokenIfVerified && $token->value === $sentToken) {
            self::unsetToken($name);
        }

        return $token->value === $sentToken;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function unsetToken(string $name): bool
    {
        if (!self::isSessionStarted() || empty($name)) {
            return false;
        }

        unset($_SESSION['tokens'][$name]);
        return true;
    }
}