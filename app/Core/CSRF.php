<?php

namespace App\Core;

/**
 * Provides CSRF (Cross-Site Request Forgery) protection.
 *
 * This class handles the generation and validation of CSRF tokens to ensure
 * that form submissions are legitimate and originate from the application itself.
 */
class CSRF
{
    /**
     * The session key for storing the CSRF token.
     */
    private const TOKEN_KEY = 'csrf_token';

    /**
     * Generates a new CSRF token and stores it in the session.
     *
     * If a token already exists, it will be overwritten.
     *
     * @return string The generated token.
     */
    public static function generateToken(): string
    {
        // Ensure session is active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_KEY] = $token;
        return $token;
    }

    /**
     * Validates a given CSRF token against the one stored in the session.
     *
     * For security, this comparison is timing-attack safe.
     *
     * @param string|null $token The token from the user's request.
     * @return bool True if the token is valid, false otherwise.
     */
    public static function validateToken(?string $token): bool
    {
        // Ensure session is active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$token || !isset($_SESSION[self::TOKEN_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::TOKEN_KEY], $token);
    }

    /**
     * Returns an HTML hidden input field with the CSRF token.
     *
     * This can be echoed directly inside a form.
     *
     * @return string
     */
    public static function getInputField(): string
    {
        $token = self::generateToken();
        return sprintf('<input type="hidden" name="csrf_token" value="%s">', $token);
    }
}
