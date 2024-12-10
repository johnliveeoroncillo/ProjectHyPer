<?php

/**
 * Soft delete news
 */
$id = post('id');
if (post('force') == true) {
    $db->news->delete($id);
} else {
    $db->news->softDelete($id);
}

/**
 * Soft delete news
 */
if (post('force') == true) {
    $db->news_details->delete(array('news_id' => $id));
} else {
    $db->news_details->softDelete(array('news_id' => $id));
}

$news = $db->news->findOne(array(
    'where' => array('id' => $id),
    'delete' => true,
));
$news_details = $db->news_details->findOne(array(
    'where' => array('news_id' => $id),
    'delete' => true,
));
echo json_encode([$news, $news_details]);