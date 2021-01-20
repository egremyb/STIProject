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
     * @return bool true if it has admin rights or if the page accessed is not an admin page false otherwise
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
     * @param $session array contain the session of the user
     * @param $msgId string id of the required msg
     * @param $dbManager dbManager Object dbManager to use a function
     * @return PDOStatement if allowed
     */
    public static function isMessageAccessAllowed($session, $msgId, $dbManager) {
        // If the user is not logged or the flag logon is false the user will be redirected to the login page
        if(!$session['logon']) {
            IdentityManagement::loginRedirect($dbManager);
        }
        $msg = $dbManager->findMessageByID($msgId);
        if ($msg == null || $session['id'] != $msg['recipientId']) {
            IdentityManagement::inboxRedirect($dbManager);
        }
        return $msg;
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
     * @param $dbManager dbManager Object dbManager to use a function
     */
    public static function isSessionValid($session, $dbManager) {
        $user = $dbManager->findUserByID($session['id']);
        // If the user is not logged or the flag logon is false the user will be redirected to the login page
        if(!$session['logon'] || $user == NULL){
            IdentityManagement::loginRedirect($dbManager);
        }
    }

    /**
     * @param $session array contain the session of the user
     * @param $token string received from the post request
     */
    public static function isTokenValid($session, $token)  {
        if ($session == null || $token == null) {
            return false;
        }
        // https://stackoverflow.com/questions/32671908/hash-equals-alternative-for-php-5-5-9
        $str1 = $session['token'];
        $str2 = $token;
        if(strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
            return !$ret;
        }
    }

    /**
     * @param $dbManager dbManager Object dbManager to use a function
     */
    private static function loginRedirect($dbManager) {
        if ($dbManager != null) {
            $dbManager->closeConnection();
        }
        header('Location: /login.php');
        exit();
    }

    /**
     * @param $dbManager dbManager Object dbManager to use a function
     */
    private static function inboxRedirect($dbManager) {
        if ($dbManager != null) {
            $dbManager->closeConnection();
        }
        header('Location: /inbox.php');
        exit();
    }
}
