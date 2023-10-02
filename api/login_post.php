<?php
    save_form(post());

    $user = $db->get_where_row('users', array('username' => post('username'), 'role' => 'USER'));
    if (empty($user)) {
        set_flash_message('Username not found');
    } 
    else if (post('password') !== $user['password']) {
        set_flash_message('Invalid password');
    } 
    else {
        save_session($user);
        return redirect('student/home');
    }
    
    return redirect();
;?>