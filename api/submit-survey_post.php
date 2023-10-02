<?php


save_form(post());

$user_id = session('id');
$guest_id = session('guest_id');
$role = session('role');

$question_count = count($db->get_where('survey_questionnaires'));
$submitted_questions = post('questions');
$submitted_questions_count = count(array_keys($submitted_questions));


if ($question_count != $submitted_questions_count) {
    set_flash_message('Please answer all the questions');
    return redirect();
}

$db->delete('survey', array('user_id' => ($role === 'GUEST' ? $guest_id : $user_id)));

foreach($submitted_questions as $key => $question) {
    $db->insert('survey', array('transaction_id' => post('transaction_id'), 'user_id' => ($role === 'GUEST' ? $guest_id : $user_id), 'question_no' => $key, 'answer' => $question));
}


$where = array('id' => post('transaction_id'));
if ($role === 'GUEST') {
    $payload['guest_id'] = $guest_id;
} else {
    $payload['user_id'] = $user_id;
}

// $db->update('queue', array('status' => 'COMPLETED'), $where);
$transaction = $db->get_where_row('queue', array('id' => post('transaction_id')));
if (!empty($transaction)) {
    $data_push = array('pd_id' => $transaction['pd_id']);
    include_once(__DIR__ .'/websockets/refresh-monitor.php');
}

set_flash_message('Survey successfully submitted', false);

return redirect('student/survey?success-message=1');
