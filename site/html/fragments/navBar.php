<?php
    // Get the current page
    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    // List with all the pages in the Navigation bar
    $pages = array("inbox.php", "message.php", "users.php", "profile.php", "logout.php");

    /**
     * Add current span tag in a html tag
     * @param $curPageName String containing the current page
     * @param $pages array with all the pages in the nav bar
     * @param $index int that indicates the index of the page in the list
     */
    function makePageCurrent($curPageName, $pages, $index){
        if (strcmp($curPageName, $pages[$index]) == 0) {
            echo '<span class="sr-only">(current)</span>';
        }
    }

    /**
     * Add a class active in a html tag
     * @param $curPageName String containing the current page
     * @param $pages array with all the pages in the nav bar
     * @param $index int that indicates the index of the page in the list
     */
    function makeActivePage($curPageName, $pages, $index){
        if (strcmp($curPageName, $pages[$index]) == 0) {
            echo 'active';
        }
    }
?>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light static-top mb-5 shadow">
    <div class="container">
        <a class="navbar-brand"><?php echo ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME));?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?php makeActivePage($curPageName, $pages, 0); ?>">
                    <a class="nav-link" href="../inbox.php">Inbox
                        <?php makePageCurrent($curPageName, $pages, 0); ?>
                    </a>
                </li>
                <li class="nav-item <?php makeActivePage($curPageName, $pages, 1); ?>">
                    <a class="nav-link" href="../message/message.php">New Message
                        <?php makePageCurrent($curPageName, $pages, 1); ?>
                    </a>
                </li>
                <li class="nav-item <?php makeActivePage($curPageName, $pages, 2); ?>">
                    <a class="nav-link" href="../users.php">User management
                        <?php makePageCurrent($curPageName, $pages, 2); ?>
                    </a>
                </li>
                <li class="nav-item <?php makeActivePage($curPageName, $pages, 3); ?>">
                    <a class="nav-link" href="../profile.php">Profile
                        <?php makePageCurrent($curPageName, $pages,3); ?>
                    </a>
                </li>
                <li class="nav-item <?php makeActivePage($curPageName, $pages, 4); ?>">
                    <a class="nav-link" href="../logout.php">Logout
                        <?php makePageCurrent($curPageName, $pages, 4); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

