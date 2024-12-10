<?php

/**
 * Insert news
 */
$news = new News();
$news->name = post('name');
$news->status = post('status');
$news = $db->news->save($news);

/**
 * Insert news details
 */
$news_details = new NewsDetails();
$news_details->news_id = $news->id;
$news_details->content = post('content');
$news_details = $db->news_details->save($news_details);

echo json_encode([$news, $news_details]);