<?php

$guard->add('admin');
$pd_id = session('admin')['pd_id'];
$db->delete("queue", array("status" => "COMPLETED", "pd_id" => $pd_id));

return redirect();