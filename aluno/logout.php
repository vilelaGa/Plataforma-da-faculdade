<?php

//Logout
session_start();
session_destroy();
header("Location: https://intra.ubm.br:8080/web/app/edu/PortalEducacional/#/");
exit();
