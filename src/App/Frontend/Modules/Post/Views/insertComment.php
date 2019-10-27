<form name="sentMessage" action="" method="post">

    <div class="control-group">

        <?= $form ?>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"
                    id="sendMessageButton" <?= (!$currentUser->hasAttribute('user')) ? ' disabled ' : '' ?>>Envoyer
            </button>

            <?php if (!$currentUser->hasAttribute('user')) { ?>
                <?php
                $currentUser->setAttribute('lastUrl', $currentUser->getApp()->getHttpRequest()->requestUri());
                ?>
                <a class="btn btn-primary"
                   href="/admin/login/">Login</a>

            <?php } ?>
        </div>
    </div>

</form>
