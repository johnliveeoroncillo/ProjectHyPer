<?php

$id = get('id');




$user = array();
$queue = $db->get_where_row('queue', "id = '{$id}' and status in ('HOLD', 'PENDING')");

if (!empty($queue)) {
    $nows = $db->get_where('queue', array('status' => 'NOW-SERVING'));
    if (!empty($nows)) {
        foreach($nows as $now) {
            $db->update('queue', array('status' => 'COMPLETED'), array('id' => $now['id']));
        }
    }

    $db->update('queue', array('status' => 'NOW-SERVING'), array('id' => $id));
    if (empty($queue['guest_id'])) {
        $user = $db->get_where_row('users', array('id' => $queue['user_id']));
    } else {
        $user = array('full_name' => $queue['guest_name']);
    }

    
    $data_push = array('id' => $queue['queue_no'], 'name' => $user['full_name']);
    include_once(__DIR__ .'/../websockets/refresh-monitor.php');
}
$id = $queue['queue_no'];


return redirect('admin/users-list?sound=true&id='.$id.'&name='.$user['full_name']);