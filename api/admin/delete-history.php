<?php

$report = get('report');

switch ($report) {
    case "clients":
        $db->delete('queue');
        break;  
    
    case "survey-results":
        $isDeleted = $db->delete('survey');
        if (!$isDeleted) {
            set_flash_message("Unable to delete " . $db->error());
            return redirect();
        }
        break;

    case "queue-no":
        $admin = session('admin');
        $pd_id = $admin['pd_id'];
        $db->delete('queue', array('pd_id' => $pd_id));

        set_flash_message("Queue reset success");
        return redirect();
        break;
}

set_flash_message("Survey successfully reset");
return redirect();