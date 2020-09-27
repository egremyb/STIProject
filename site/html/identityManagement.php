<?php


/**
 * Class IdentityManagement class that manages the identity of the user
 */
class IdentityManagement
{
    private static $ADMIN_ROLE = 'Administrator';
    private static $ADMIN_PAGES = ['users.php', 'editUser.php', 'addUser.php', 'deleteUser.php'];

    public static function isPageAllowed($role) {
        $page = basename($_SERVER['PHP_SELF']);

        // Admins have full access to all pages
        if ($role === self::$ADMIN_ROLE)
            return true;
        // Not admin

        // Admin pages are not allowed
        if (in_array($page, self::$ADMIN_PAGES, true))
            return false;

        return true;
    }
}