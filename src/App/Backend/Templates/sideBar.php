<div class="sidebar" data-color="purple" data-background-color="white">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

    Tip 2: you can also add an image using data-image tag
-->
    <div class="logo">
        <a href="" class="simple-text logo-mini">
            LOGO
        </a>
        <a href="" class="simple-text logo-normal">
            <?=  $currentUser->hasAttribute('user') ? $currentUser->getAttribute('user')->getUserName() : 'connexion'  ?>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">

            <li class="nav-item active  ">
                <a class="nav-link" href="#0">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="/admin/">
                    <i class="material-icons">Posts</i>
                    <p>posts</p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="/admin/users/">
                    <i class="material-icons">User</i>
                    <p>Users</p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="/admin/roles/">
                    <i class="material-icons">Roles</i>
                    <p>Roles</p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="/admin/permissions/">
                    <i class="material-icons">Permissions</i>
                    <p>Permissions</p>
                </a>
            </li>


            <!-- your sidebar here -->
        </ul>
    </div>
</div>