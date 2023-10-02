<?php

save_form(post());

$role = 'USER';

if (empty(post('email'))) {
    $_POST['email'] = ' ';
}
$find = $db->get_where_row('users', array(
    'username' => post('username'),
    'role' => $role,
));
if (!empty($find)) {
    set_flash_message("User already exists");
}

$_POST['role'] = $role;

$isInserted = $db->insert('users', post());
if ($isInserted) {
    set_flash_message('Registration successful', false);
} else {
    set_flash_message('Unable to complete registration ' . $db->error());
}

return redirect();