<?php

use function OpenFram\escape_to_html as h;
use function OpenFram\u;

?>


<div class="page-header header-filter clear-filter purple-filter" data-parallax="true"
     style="background-image: url( <?php h($user->getProfileImage()) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
                <div class="brand text-center p-4">

                    <h1><?php h($title) ?></h1>

                </div>
            </div>
        </div>
    </div>
</div>


<article class="main main-raised">
    <section class="section profile-content">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-3">
                    <ul class="nav nav-pills nav-pills-icons  flex-column " role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show" href="#profil" role="tab" data-toggle="tab"
                               aria-selected="true">
                                <i class="material-icons">person</i> Profil
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#edit" role="tab" data-toggle="tab" aria-selected="false">
                                <i class="material-icons">edit</i> Editer
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#delete" role="tab" data-toggle="tab" aria-selected="false">
                                <i class="material-icons">delete</i> Supprimer
                            </a>
                        </li>

                    </ul>
                </div>


                <div class="col-md-9">

                    <div class="tab-content pt-4">
                        <div class="tab-pane active show" id="profil">

                            <div class="card">
                                <div class="card-header card-header-primary profile">
                                    <div class="avatar w-25 float-right">
                                        <img src="..<?php h($user->getProfileImage()) ?>" alt="Circle Image"
                                             class="img-raised rounded-circle img-fluid">
                                    </div>
                                    <h3 class="card-title"><?php h($user->getUserName()) ?></h3>
                                    <h4 class="card-title"><?php h($user->getRole()->getName()) ?></h4>
                                    <p class="card-header-text"><?php h($user->getLastName() . ' ' . $user->getFirstName()) ?></p>
                                    <p class="card-header-text"> <?php h($user->getEmail()) ?></p>

                                </div>
                                <div class="card-body">
                                    <div class="card-description">
                                        <?php h($user->getDescription()) ?>

                                    </div>
                                    <a href="#pablo" class="btn btn-primary btn-round">Follow</a>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="edit">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Edit Profile</h4>
                                    <p class="card-category">Complete your profile</p>
                                </div>
                                <div class="card-body">
                                    <form class="contact-form"
                                          action=""
                                          method="post"
                                          enctype="multipart/form-data">

                                        <?= $form ?>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Envoyer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="delete">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Supprmier mon Profil</h4>
                                    <p class="card-category">Êtes vous certain de vouloir supprimer votre profil?</p>
                                </div>
                                <div class="card-body">
                                    <form class="contact-form"
                                          action="/delete"
                                          method="post"


                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Supprimer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>
</article>



