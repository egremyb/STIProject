<?php
// Get the current page
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>
<!-- Select dropdown -->
<div class="d-flex flex-row-reverse bd-highlight mb-3">
    <form id="paginationForm" action="<?= $curPageName ?>" method="post">
        <select name="records-limit" id="records-limit" class="custom-select">
            <option disabled selected>Number of data to show per page</option>
            <!-- Show the different option for the number of object to select-->
            <?php foreach([5,7,10,12] as $pageInit['limit']) : ?>
                <option
                    <?php if(isset($_SESSION['records-limit']) && $_SESSION['records-limit'] == $pageInit['limit']) echo 'selected'; ?>
                    value="<?= $pageInit['limit']; ?>">
                    <?= $pageInit['limit']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
