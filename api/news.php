<?php

$response = array();

$id = get('id');


// $lastId = $db->news->lastInsertedId();
// echo $lastId;

// /**
//  * Pagination
//  */
// $news = $db->news->paginate(
//     array(
//         'page' => 1,
//         'limit' => 10,
//         'order' => array(
//             'id' => 'DESC',
//         ),
//         'include' => array(
//             'news_details' => true
//         )
//     )
// );

// echo $db->news->lastQuery();

/**
 * Find All
 */
// $news = $db->news->find();
// $response['all'] = $news;

/**
 * Find By Id
 */
// $news = $db->news->find(
//     array(
//             'order' => array(
//                 'id' => 'DESC',
//             ),
//             'include' => array(
//                 'news_details' => array(
//                     'single' => true,
//                     'where' => array(
//                         'id' => 1
//                     ),
//                     'include' => array(
//                         'news_attachments' => array(
//                             'where' => array(
//                                 'created_at' => '2024-12-09 20:22:40'
//                             )
//                         )
//                     )
//                 )
//             )
//         )
//     );
// $response['news_by_id'] = $news;

// /**
//  * Count Id
//  */
// $news = $db->news->count(4);
// $response['news_by_id'] = $news;


// $where = array(
    // 'id' => $id,
    // 'id' => array(
    //     'in' => array(25, 23)
    // )
    // 'id' => array(
    //     'and' => array(
    //         'eq' => '1',
    //         'gt' => '2',
    //     )
    // ),
    // 'created_at' => array(
    //     'between' => ['2024-01-01 00:00:00', '2025-01-01 23:59:59'],
    // ),
    // 'id' => array(
    //     'eq' => 1,
    // ),
    // 'id' => array(
    //     'like' => 2,
    // ),
    // 'id' => array(
    //     'gt' => 2,
    // ),
    // 'id' => array(
    //     'gte' => 3,
    // ),
    // 'id' => array(
    //     'lt' => 3,
    // ),
    // 'id' => array(
    //     'lte' => 3,
    // ),
    // 'id' => array(
    //     'or' => array(
    //         'in' => [2,3],
    //         'eq' => '1',
    //     )
    // ),
// );
/**
 * Find with filter
 */
// $news = $db->news->find($where);
// $response['filtered'] = $news;

/**
 * Find Specific
 */
// $news = $db->news->findOne($where);
// $response['one'] = $news;

/**
 * Advanced Filtering
 */
// $news = $db->news->find(
//     array(
//         'where' => $where,
//         'limit' => 2,
//         'order' => array(
//             'id' => 'DESC',
//             'name' => 'DESC',
//         ),
//         'group' => ['id', 'name'],
//         'having' => ['id > 1', 'id <= 2']
//     )
// );
// $response['advanced'] = $news;

echo json($response);