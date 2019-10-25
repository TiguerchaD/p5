<?php
use function OpenFram\escape_to_html as h;
use function OpenFram\u;

?>
<div class="col-12 col-md-3">

    <ul class="nav nav-pills nav-pills-icons  flex-column " role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="#profil" role="tab" data-toggle="tab" aria-selected="true">
                <i class="material-icons">person</i> Profile
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link  active show" href="#edit" role="tab" data-toggle="tab" aria-selected="false">
                <i class="material-icons">edit</i> Editer
            </a>
        </li>

    </ul>

</div>
<div class="col-md-8">
    <div class="tab-content pt-4">
        <div class="tab-pane" id="profil">
            <div class="card card-profile">
                <div class="card-avatar">
                    <a href="#pablo">
                        <img class="img" src="..<?php h($user->getProfileImage())?>"/>
                    </a>
                </div>
                <div class="card-body">
                    <h6 class="card-category text-gray"><?php h($user->getUserName()) ?></h6>
                    <h4 class="card-title"><?php h($user->getRole()->getName()) ?></h4>
                    <p><?php h($user->getLastName() . ' ' . $user->getFirstName()) ?></p>
                    <p><?php h($user->getEmail()) ?></p>
                    <div class="card-description">
                        <?php h($user->getDescription()) ?>

                    </div>
                    <a href="#pablo" class="btn btn-primary btn-round">Follow</a>
                </div>
            </div>

        </div>
        <div class="tab-pane  active show" id="edit">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Edit Profile</h4>
                    <p class="card-category">Complete your profile</p>
                </div>
                <div class="card-body">
                    <form class="contact-form" action="" method="post" enctype="multipart/form-data">

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



