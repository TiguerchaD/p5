<?php
use function OpenFram\escape_to_html as h;

?>

<div class="page-header header-filter"
     style="background-image: url('../assets/img/bg7.jpg'); background-size: cover; background-position: top center;">
    <div class="container">
        <div class="row">

            <div class="col-lg-4 col-md-6 ml-auto mr-auto">

                <div class="card card-login">
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



                    <form class="form" method="post" action="/connection">
                        <div class="card-header card-header-primary text-center">
                            <h4 class="card-title">Login</h4>
                        </div>

                        <div class="card-body px-4">


                            <?= $form ?>

                        </div>
                        <div class="row">
                            <div class="ml-auto mr-auto text-center">
                                <button class="btn btn-primary btn-raised">
                                    Se connecter
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <p class = "col-12 ml-auto mr-auto text-center">Pas encore inscrit ? </p>
                            <div class = "ml-auto mr-auto text-center">

                                <a class="nav-link btn btn-primary" href="/register">
                                    <i class="material-icons">account_circle</i> S'inscrire
                                </a>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

</div>

