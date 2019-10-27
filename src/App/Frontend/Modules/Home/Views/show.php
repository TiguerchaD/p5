<?php

use function OpenFram\escape_to_html as h;
use function OpenFram\u;

?>

<div class="page-header header-filter clear-filter purple-filter" data-parallax="true"
     style="background-image: url('./assets/img/bg2.jpg');  background-position: center;">
    <div class="container">
        <div class="col-md-8 ml-auto mr-auto">
            <div class="brand text-center">
                <h1>Futur Developpeur php Symfony</h1>
                <h3>En apprentissage intensif à OpenClassrooms</h3>

                <a class="btn btn-primary btn-lg btn-round" href="docs/cv.pdf" download>
                    <i class="material-icons">cloud_download</i> Télécharger mon CV
                </a>

            </div>
        </div>
    </div>
</div>
</div>


<div class="main main-raised">

    <div class="profile-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 ml-auto mr-auto">
                    <div class="profile">
                        <div class="avatar">
                            <img src="images/logo-1.jpg" alt="Circle Image" class="img-raised rounded-circle img-fluid">
                        </div>
                        <div class="name">
                            <h3 class="title">Tiguercha Djillali</h3>
                            <a href="" class="btn btn-just-icon btn-link btn-dribbble"><i
                                        class="fa fa-dribbble"></i></a>
                            <a href="" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"></i></a>
                            <a href="" class="btn btn-just-icon btn-link btn-pinterest"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="description text-center">
                <p>Un texte de presentation : Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad beatae
                    commodi consectetur dicta eaque earum enim est expedita libero modi nemo obcaecati perferendis quas
                    quos rerum similique, ullam. Architecto, necessitatibus! </p>
            </div>
        </div>
    </div>


    <div class="section section-tabs">
        <div class="container">
            <div class="row">

                <h2 class="text-center title col-12">Derniers articles</h2>

                <?php foreach ($postsList as $post) { ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                    <h4 class="card-title"><?php h($post->getTitle()) ?></h4>
                                </div>
                            </div>
                            <div class="card-body">

                                <p style="height:100px; overflow-y: hidden"><?php h($post->getSubTitle()) ?></p>
                                <a href="/post-<?php h(u($post->getId())) ?>.html" class="btn btn-primary">Lire</a>

                                <p class="float-right pt-3">Publié par <a href="#"
                                                                          class="font-italic"> <?php h($post->getUser()->getUserName()) ?> </a>
                                    - Le
                                    <?php h($post->getPublicationDate()->format('d/m/Y à H\hi\ ')) ?>

                                </p>


                            </div>
                        </div>
                    </div>

                <?php } ?>


            </div>
        </div>
    </div>


    <div id="contactSection" class="section section-contacts px-3">

        <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
                <?php
                if ($currentUser->hasFlash()) {
                    $flash = $currentUser->getFlash();
                    ?>
                    <div class="alert alert-<?= h($flash['type']) ?>">
                        <div class="container">
                            <div class="alert-icon">
                                <i class="material-icons">check</i>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="material-icons">clear</i></span>
                            </button>
                            <b>Alert:</b> <?php h($flash['message']) ?>
                        </div>
                    </div>

                <?php } ?>


                <h2 class="text-center title">Contactez moi</h2>
                <h4 class="text-center description">Divide details about your product or agency work into parts. Write a
                    few lines about each one and contact us about any further collaboration. We will responde get back
                    to you in a couple of hours.</h4>
                <form class="contact-form" action=".#contactSection" method="post">

                    <?= $form ?>

                    <div class="row">
                        <div class="col-md-4 ml-auto mr-auto text-center">
                            <button class="btn btn-primary btn-raised">
                                Envoyer
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

