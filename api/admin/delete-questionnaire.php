<?php

$id = get('id');
$db->delete('survey_questionnaires', array('id' => $id));

set_flash_message('Questionnaire successfully deleted', false);

return redirect();