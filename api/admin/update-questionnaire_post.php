<?php
    save_form(post());

    $id = post('id');
    $question = post('question');

    $data = $db->get_where_row('survey_questionnaires', array('id' => $id));
    if (!empty($data)) {
        $db->update('survey_questionnaires', array('question' => $question), array('id' => $id));
        set_flash_message('Questionnaire successfully updated', false);
    } else {
        set_flash_message('Unable to update questionnaire');
    }

    return redirect();
;?>