
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">


        <?php
        $pagesNumber = intdiv($postsNumber, $limit) + (($postsNumber % $limit !== 0) ? 1 : 0);
        $page = 1;
        ?>
        <li class="page-item  <?= ($activePage == 1) ? ' disabled' : '' ?> ">
            <a class="page-link " href="/posts-<?= $activePage - 1 ?>.html" tabindex="-1">Précedent</a>
        </li>

        <?php
        while ($page <= $pagesNumber) { ?>

            <?php $active = ($page == $activePage) ? ' active' : ''; ?>
            <?php


            $display = ($page > 5) ? ' d-none' : '';

            if ($activePage > 4) {

                if ($activePage < $pagesNumber - 3) {

                    $display = (abs($page - $activePage) > 2) ? ' d-none' : '';

                }else {
                    $display = ($page < $pagesNumber - 4) ? ' d-none' : '';
                }

            }

            ?>


            <li class="page-item <?= $active . ' ' . $display ?>">
                <a class="page-link" href="/posts-<?= $page ?>.html">
                    <?= $page ?>
                    <?= ($page == $activePage) ? ' <span class="sr-only">(current)</span>' : ''; ?>
                </a>
            </li>
            <?php $page++;
        } ?>


        <li class="page-item  <?= ($activePage == $page - 1) ? ' disabled' : '' ?> ">
            <a class="page-link " href="/posts-<?= $activePage + 1 ?>.html" tabindex="-1">Suivant</a>
        </li>
    </ul>
</nav>
