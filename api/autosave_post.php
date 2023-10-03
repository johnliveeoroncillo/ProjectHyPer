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
    echo $id;
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

$db->insert('user_content_histories', array(
    'transaction_id' => $id,
    'user_id' => session('id'),
    'name' => $name,
    'text' => $content,
));


;?>