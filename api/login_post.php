<?php

save_form(post());

$user = $db->get_where_row('users', array('username' => post('username')));
if (empty($user)) {
    set_flash_message('Username not found');
} else if ($user['password'] !== post('password')) {
    set_flash_message('Invalid password');
} else {
    
    $payload = array(
        'id' => $user['id'],
        'full_name' => $user['full_name'],
        'username' => $user['user_name'],
        'created_at' => $user['created_at'],
    );

    save_session($payload);
    clear_form();
}

return redirect();

;?> 