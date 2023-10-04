<?php

    $db->getAllTables();

    $users = $db->content->get_where();
    echo json_encode($users);
;?>