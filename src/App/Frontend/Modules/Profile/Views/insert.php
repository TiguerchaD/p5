

<?php
use function OpenFram\escape_to_html as h;

?>
<div class="page-header header-filter clear-filter purple-filter" data-parallax="true"
     style="">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
                <div class="brand text-center">

                    <h1>Inscription</h1>

                </div>
            </div>
        </div>
    </div>
</div>

<article class="main main-raised">
    <section class="section">

        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Edit Profile</h4>
                            <p class="card-category">Complete your profile</p>
                        </div>
                        <div class="card-body">
                            <form class="contact-form" action="/insert" method="post" enctype="multipart/form-data">

                                <?= $form ?>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</article>