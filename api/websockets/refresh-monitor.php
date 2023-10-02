<?php
require_once('init.php');

$admin = session('admin');
if (!empty($admin['pd_id']) || !empty($data_push['pd_id'])) {
    $data['message'] = $data_push;
    $pusher->trigger('queue-self-channel-'.(!empty($admin['pd_id']) ? $admin['pd_id'] : $data_push['pd_id']), 'second_monitor', $data);
    $pusher->trigger('queue-general-channel', 'general_second_monitor', $data);
}