<?php

save_form(post());

$user = $db->get_where_row('users', array('username' => post('username')));
if (!empty($user)) {
    set_flash_message('Username already exists');
} else if(post('password') !== post('confirm_password')) {
    set_flash_message('Password and confirm password not match');
} else {
    $payload = array(
        'id' => $user['id'],
        'full_name' => post('full_name'),
        'username' => post('username'),
        'password' => post('password'),
    );

    $isInserted = $db->insert('users', $payload);
    if (!$isInserted) {
        set_flash_message('Unable to insert user ' . $db->error());
    } else {
        set_flash_message('Registration successful', false);
        clear_form();
    }
}

return redirect();

;?> 