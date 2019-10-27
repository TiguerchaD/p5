<?php
use function OpenFram\escape_to_html as h;

?>
<div class="small-header col-12 p-0 ">
    <div class="admin-header header-filter clear-filter purple-filter" data-parallax="true"
         style="background-image: url('../assets/img/bg2.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ml-auto mr-auto">
                    <div class="brand text-center">
                        <h1><?php h($title) ?></h1>
                        <p class="h3"><?php h($message) ?></p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


