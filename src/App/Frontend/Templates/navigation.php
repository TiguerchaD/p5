<?php
use function OpenFram\escape_to_html as h;

?>
<nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100"
     id="sectionsNav">
    <div class="container">
        <div class="navbar-translate">

            <a class="navbar-brand font-weight-bold" href="/">LOGO </a>


            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <i class="material-icons">home</i> Accueil
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/posts-1.html">
                        <i class="material-icons">view_list</i> Articles
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <i class="material-icons">work</i> Portfolio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="" target="_blank"
                       data-original-title="Follow us on Twitter">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="" target="_blank"
                       data-original-title="Like us on Facebook">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="" target="_blank"
                       data-original-title="Follow us on Instagram">
                        <i class="fa fa-instagram"></i>
                    </a>
                </li>


                <?php if ($currentUser->hasAttribute('user')) { ?>
                    <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?php h($currentUser->getAttribute('user')->getProfileImage()) ?>" alt="Circle Image" class="rounded-circle img-fluid" style="height: 25px">
                            <?php h($currentUser->getAttribute('user')->getUserName()) ?>
                            <div class="ripple-container"></div></a>
                        <div class="dropdown-menu dropdown-with-icons">
                            <a href="/logout" class="dropdown-item">
                                <i class="material-icons">power_settings_new</i> Se déconnecter
                            </a>
                            <a href="/profile" class="dropdown-item">
                                <i class="material-icons">content_paste</i> Profile
                            </a>
                        </div>
                    </li>

                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary" href="/connection">
                            <i class="material-icons">account_circle</i> Se connecter
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link btn btn-primary" href="/register">
                            <i class="material-icons">account_circle</i> S'inscrire
                        </a>
                    </li>

                <?php } ?>


            </ul>
        </div>
    </div>
</nav>


