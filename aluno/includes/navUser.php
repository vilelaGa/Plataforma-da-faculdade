<?php

if (!isset($response->data[0]->NOMEALUNO)) {
    header("location: https://intra.ubm.br:8080/web/app/edu/PortalEducacional/#/");
}

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle Menu</span>
        </button>
        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#"><strong><?= $response->data[0]->NOMEALUNO ?></strong> (RA: <?= $response->data[0]->RA ?>)
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>