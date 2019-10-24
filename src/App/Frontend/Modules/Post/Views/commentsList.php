<?php
use  function OpenFram\escape_to_html as h;

?>
<div class="col-12">

        <h2> Commentaires <small class="float-right"><?php h(count($comments)) ?> commentaires</small></h2>
    <?php foreach ($comments as $comment) { ?>
        <div class="media">
            <div class="media-body">
                <p class="float-right"><small>
                        Le <?php h($comment->getPublicationDate()->format('d/m/Y à H\hi\ ')) ?></small></p>

                <h4 class="media-heading currentUser_name"><?php h($comment->getUser()->getUserName()) ?></h4>
                <p><?php h($comment->getContent()) ?></p>
            </div>
        </div>

    <?php } ?>
</div>
