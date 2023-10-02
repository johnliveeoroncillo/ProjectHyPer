<?php
    save_form(post());

    $pd_id = post('pd_id');
    
    $user = $db->get_where_row('users', array('username' => post('username'), 'role' => 'ADMIN'));
    if (empty($user)) {
        set_flash_message('Username not found');
    } 
    else if (post('password') !== $user['password']) {
        set_flash_message('Invalid password');
    } 
    else {
        $pd = $db->get_where_row('pds', array('id' => $pd_id));
        if (!empty($pd)) {
            $user['pd_name'] = $pd['description'];
        }
        $user['pd_id'] = $pd_id;
        $payload[strtolower($user['role'])] = $user;
        save_session($payload);
    }

    return redirect('admin/home');
;?>