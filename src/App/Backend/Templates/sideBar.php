<?php
use function OpenFram\escape_to_html as h;
use function OpenFram\u;

?>
<div class="sidebar  " data-color="purple" data-background-color="white">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

    Tip 2: you can also add an image using data-image tag
-->
    <div class="logo">
        <a href="" class="simple-text logo-mini">
            LOGO
        </a>
        <a href="" class="simple-text logo-normal">
            <?php $currentUser->hasAttribute('user') ? h($currentUser->getAttribute('user')->getUserName()) : h('connexion') ?>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">

            <li class="nav-item <?= ($module == 'Home') ? 'active' : ''?>">
                <a class="nav-link" href="/admin/">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-item  <?= (isset($user) && $currentUser->getAttribute('user')->getId() === ($user->getId())) ? 'active' : ''?> ">
                <a class="nav-link" href="/admin/user-<?php h(u($currentUser->getAttribute('user')->getId()))?>.html">
                    <i class="material-icons">Profil</i>
                    <p>Profil</p>
                </a>
            </li>

            <li class="nav-item  <?= ($module == 'Post') ? 'active' : ''?>">
                <a class="nav-link" href="/admin/posts">
                    <i class="material-icons">Articles</i>
                    <p>Articles</p>
                </a>
            </li>
            <li class="nav-item  <?= ($module == 'Comment') ? 'active' : ''?>">
                <a class="nav-link" href="/admin/comments">
                    <i class="material-icons">Comments</i>
                    <p>Commentaires</p>
                </a>
            </li>
            <?php if ($currentUser->hasAccessTo('User', 'Index')) { ?>
                <li class="nav-item  <?= ($module == 'User' &&  !(isset($user) && $currentUser->getAttribute('user')->getId() === ($user->getId()))) ? 'active' : ''?>">
                    <a class="nav-link" href="/admin/users">
                        <i class="material-icons">User</i>
                        <p>Users</p>
                    </a>
                </li>
            <?php } ?>

            <?php if ($currentUser->hasAccessTo('Role', 'Index')) { ?>
                <li class="nav-item  <?= ($module == 'Role') ? 'active' : ''?>">
                    <a class="nav-link" href="/admin/roles">
                        <i class="material-icons">Roles</i>
                        <p>Roles</p>
                    </a>
                </li>
            <?php } ?>

            <?php if ($currentUser->hasAccessTo('Permission', 'Index')) { ?>
                <li class="nav-item  <?= ($module == 'Permission') ? 'active' : ''?>">
                    <a class="nav-link" href="/admin/permissions">
                        <i class="material-icons">Permissions</i>
                        <p>Permissions</p>
                    </a>
                </li>
            <?php } ?>


            <!-- your sidebar here -->
        </ul>
    </div>
</div>

