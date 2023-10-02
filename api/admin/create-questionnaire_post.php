<?php

$db->insert('survey_questionnaires', post());

set_flash_message('Questionnaire successfully created', false);

return redirect();