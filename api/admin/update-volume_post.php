<?php

$volume = post('volume');

$settings = $db->get_where_row('settings');
if (empty($settings)) {
    $db->insert('settings', array('volume' => $volume));
}
else {
    $db->update('settings', array('volume' => $volume), array('id' => $settings['id']));
}
return redirect();