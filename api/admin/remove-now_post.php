<?php

$id = post('id');
$status = post('status');
// if (empty($status) || !empty(post('no_status'))) {
//     $status = 'N/A';
// }

$queue = $db->get_where_row('queue', array('id' => $id, 'status' => 'NOW-SERVING'));
if (!empty($queue)) {
    $db->update('queue', array('status' => $status), array('id' => $id));

    if (empty($queue['guest_id'])) {
        $user = $db->get_where_row('users', array('id' => $queue['user_id']));
    } else {
        $user = array('full_name' => $queue['guest_name']);
    }

    $data_push = array();
    include_once(__DIR__ .'/../websockets/refresh-monitor.php');
}


return redirect('admin/users-list');