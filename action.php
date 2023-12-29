<?php
if(isset($_POST["action"]))
{
	
	//---for add and edit the data------
	if($_POST['action'] == 'Add' || $_POST['action'] == 'Edit')
	{
		$file = 'data.json';
		$error = array();

		$data = array();

		$data['id'] = time();

		if(empty($_POST['first_name']))
		{
			$error['first_name_error'] = 'First Name is Required';
		}
		else
		{
			$data['first_name'] = trim($_POST['first_name']);
		}

		if(empty($_FILES['file']))
		{
			$error['file'] = 'file required';
		}
		else
		{
			$filename = $_FILES['file']['name'];
			$extension = pathinfo($filename, PATHINFO_EXTENSION);

			/* Location */
			$location = "upload/".time().".". $extension;
			move_uploaded_file($_FILES['file']['tmp_name'],$location);
			$data['img'] = time().".". $extension;
		}

		if(empty($_POST['last_name']))
		{
			$error['last_name_error'] = 'Last Name is Required';
		}
		else
		{
			$data['last_name'] = trim($_POST['last_name']);
		}

		$data['gender'] = trim($_POST['gender']);

		if(count($error) > 0)
		{
			$output = array(
				'error'		=>	$error
			);
		}
		else
		{
			$file_data = json_decode(file_get_contents($file), true);

			if($_POST['action'] == 'Add')
			{

				$file_data[] = $data;

				file_put_contents($file, json_encode($file_data));

				$output = array(
					'success' => 'Data Added'
				);
			}

			if($_POST['action'] == 'Edit')
			{
				$key = array_search($_POST['id'], array_column($file_data, 'id'));

				$file_data[$key]['first_name'] = $data['first_name'];

				$file_data[$key]['img'] = $data['img'];

				$file_data[$key]['last_name'] = $data['last_name'];

				$file_data[$key]['gender'] = $data['gender'];

				file_put_contents($file, json_encode($file_data));

				$output = array(
					'success' => 'Data Edited'
				);
			}
		}

		echo json_encode($output);
	}
}