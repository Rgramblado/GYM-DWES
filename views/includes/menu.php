<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid"> <a class="navbar-brand" href="/">
            <b> GYM_DWES</b>
        </a> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#top-nav-menu" aria-expanded="true">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse show" id="top-nav-menu">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"> <a class="nav-link" href="?controller=user&action=showschedule">Horarios</a> </li>
                <li class="nav-item"> <a class="nav-link" href="?controller=user&action=showmyactivities">Mis actividades</a> </li>
                <li class="nav-item"> <a class="nav-link" href="?controller=messages">Mis mensajes</a> </li>
                <?php if (isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 2) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Administración</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="?controller=admin&action=editactivities">Actividades</a>
                            <a class="dropdown-item" href="?controller=admin&action=editschedule">Horario</a>
                            <a class="dropdown-item" href="?controller=admin&action=listusers">Usuarios</a>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class=" fa fa-user mr-3 btn btn-primary"></i><?php echo $_SESSION["username"] ?></a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item" disabled><small><?php echo $_SESSION["date"];?></small></a>
                        <a href="?controller=user&action=edituser" class="dropdown-item">Mi perfil</a>
                        <hr>
                        <a class="dropdown-item" href="/?controller=user&action=sign_out">Cerrar sesión</a>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</nav>