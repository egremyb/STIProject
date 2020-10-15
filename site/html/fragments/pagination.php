<?php
// Get the current page
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>

<!-- Pagination -->
<nav aria-label="Page navigation example mt-5">
    <ul class="pagination justify-content-center">
        <!-- if the number of page is under 1 the previous button will be disabled -->
        <li class="page-item <?php if($pageInit['page'] <= 1){ echo 'disabled'; } ?>">
            <a class="page-link"
               href="<?php if($pageInit['page'] <= 1){ echo '#'; } else { echo "?page=" . $prev; } ?>">Previous</a>
        </li>

        <!-- Show the pagination menu with all the available pages-->
        <?php for($i = 1; $i <= $totalPages; $i++ ): ?>
            <li class="page-item <?php if($pageInit['page'] == $i) {echo 'active'; } ?>">
                <a class="page-link" href="<?= $curPageName ?>?page=<?= $i; ?>"> <?= $i; ?> </a>
            </li>
        <?php endfor; ?>

        <!-- if the number of page is above the total of pages the next button will be disabled -->
        <li class="page-item <?php if($pageInit['page'] >= $totalPages) { echo 'disabled'; } ?>">
            <a class="page-link"
               href="<?php if($pageInit['page'] >= $totalPages){ echo '#'; } else {echo "?page=". $next; } ?>">Next</a>
        </li>
    </ul>
</nav>
