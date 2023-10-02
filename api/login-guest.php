<?php
$guest_id = uniqid();
$user = array('id' => 0, 'guest_id' => $guest_id, 'full_name' => '', 'username' => '', 'password' => '', 'email' => '', 'role' => 'GUEST');
save_session($user);

return redirect('student');
?>