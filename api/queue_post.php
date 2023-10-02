<?php
save_form(post());

$id = post('id');
$db->order_by('created_at DESC');
$last_no = $db->get_where_row('queue', 'date(created_at) = "'.date('Y-m-d').'" and pd_id = "'.post('pd_id').'"');

$last_no = (empty($last_no) ? 0 : $last_no['queue_no']) + 1;
$data_push = array('pd_id' => post('pd_id'));

if (empty($id)) {
    $data = array(
        'queue_no' => $last_no,
        'user_id' => session('id'),
        'student_no' => post('student_no'),
        'year_section' => post('year_section'),
        'request_type' => post('request_type'),
        'pd_id' => post('pd_id'),
    );
    if (empty(session('id'))) {
        $data['guest_id'] = session('guest_id');
        $data['guest_name'] = post('name');
    }

    $isInserted = $db->insert('queue', $data);
    
    if (!$isInserted) {
        set_flash_message('Unable to create queue ' . $db->error());
    } else {
        set_flash_message('Queue successfully placed', false);

        include_once(__DIR__ .'/websockets/refresh-monitor.php');
    }
} else {
    $data = array(
        'student_no' => post('student_no'),
        'year_section' => post('year_section'),
        'request_type' => post('request_type'),
        'pd_id' => post('pd_id'),
    );
    
    if (empty(session('id'))) {
        $data['guest_id'] = session('guest_id');
        $data['guest_name'] = post('name');
    }

    $isUpdated = $db->update('queue', $data, array('id' => $id));

    
    if (!$isUpdated) {
        set_flash_message('Unable to update queue ' . $db->error());
    } else {
        set_flash_message('Queue successfully updated', false);

        include_once(__DIR__ .'/websockets/refresh-monitor.php');
    }
}

return redirect('student/join-wait-list');