<?php


/**
 * Class IdentityManagement class that manages the identity of the user
 */
class IdentityManagement
{
    private static $ADMIN_ROLE = 'Administrator';
    private static $ADMIN_PAGES = ['users.php', 'editUser.php', 'addUser.php', 'deleteUser.php'];

    /**
     * @param $role String that contains the role of a user
     * @return bool true if it has admin rights and the page accessed is not an admin page false otherwise
     */
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

    /**
     * @param $password String password to check if policy is respected
     * @return bool true if password contains a char uppercase, a char lowercase, a special char and a number
     */
    public static function isPasswordStrong($password) {
        // Source: https://www.codexworld.com/how-to/validate-password-strength-in-php/
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        return $uppercase && $lowercase && $number && $specialChars && strlen($password) >= 8;
    }

    /**
     * @param $session array contain the session of the user
     * @param $dbManager dbManager Object dbManager to user a function
     * @param $isInFolder boolean true if page is in folder false otherwise
     */
    public static function isSessionValid($session, $dbManager, $isInFolder) {
        $user = $dbManager->findUserByID($session['id']);
        // If the user is not logged or the flag logon is false the user will be redirected to the login page
        if(!$session['logon'] || $user == NULL){
            $dbManager->closeConnection();
            if($isInFolder) {
                header('Location: ../login.php');
            } else{
                header('Location: login.php');
            }
            exit();
        }
    }
}
