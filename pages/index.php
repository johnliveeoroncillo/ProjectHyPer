<?php
// $content = new Content();
// $content->text = "new-upsert";
// $inserted = $content->insert($content);

// $find = $content->findOne(1);
// $updated = $content->update($find, 1);

// $find->text = "updated";
// $saved = $content->save($find);
// $saved1 = $content->save($content);

// $content->delete(99);
// $content->softDelete(1);

// $finds = $content->find();
// echo json_encode($finds);

$user = new Users();
$users = $user->find();
echo json_encode($users);
;?>