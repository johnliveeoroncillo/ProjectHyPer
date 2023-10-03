<?php

$id = post('id');
$name = post('name');
$name = empty($name) ? 'Untitled - '.random()  : $name;
$content = post('content');

if (empty($id)) {
    $db->insert('user_contents', array(
        'user_id' => session('id'),
        'name' => $name,
        'text' => $content
    ));
    $id = $db->lastInsertedId();
    echo $id; die();
} else {
    $db->update('user_contents', array(
        'name' => $name,
        'text' => $content
    ), array(
        'user_id' => session('id'),
        'id' => $id
    ));
    echo $id;
}

;?>