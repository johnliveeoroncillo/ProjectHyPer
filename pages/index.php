<?php
	$jl->guard->add('guest');

	// $jl->database->get_config();
	

	// $contents = $jl->database->content->find(array('text' => 'test2345', 'id' => 5));
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $contents = $jl->database->content->findOne(5);
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $jl->database->content->order_by('id', 'DESC');
	// $contents = $jl->database->content->find();
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $jl->database->content->order_by(array('id' => 'DESC', 'text' => 'ASC'));
	// $contents = $jl->database->content->find();
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $contents = $jl->database->content->count();
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $contents = $jl->database->content->insert(array('text' => 'JLOCODES'));
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $contents = $jl->database->content->update(array('text' => 'testupdate'), 1);
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $data = $jl->database->content->findOne(5);
	// $data->text = 'update from model';
	// $contents = $jl->database->content->save($data);
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	$data = new Content();
	$data->text = 'sdad';
	echo json_encode($data);
	// $data->text = 'insert from model';
	// $contents = $jl->database->content->update($data);
	// echo  $jl->database->content->last_query();
	// echo '<br>';
	// echo json_encode($contents);

	// echo '<br><br><br>';
	// $data = $jl->database->content->findOne(5);
	// $contents = $jl->database->content->delete(5);
	// echo $jl->database->content->last_query();
	// echo $jl->database->content->error();
	// echo '<br>';
	// echo json_encode($contents);


;?> 