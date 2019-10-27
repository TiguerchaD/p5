<?php
use function OpenFram\escape_to_html as h;
use function OpenFram\u;

?>

<h2 class="col-12">Laisser un commentaire </h2>

<form name="sentMessage"  action="post-<?php h(u($post->getId())) ?>.html#commentsList"
      method="post" class="col-12">


    <?= $form ?>


    <div class="form-group">
        <button type="submit" class="btn btn-primary" <?= (!$currentUser->hasAttribute('user'))? ' disabled ' : '' ?>>
            Envoyer
        </button>

        <?php if (!$currentUser->hasAttribute('user')) { ?>
            <button class="btn btn-primary" type="submit" name="loginForComment" value="1"> Sign in</button>

        <?php } ?>
    </div>


</form>


