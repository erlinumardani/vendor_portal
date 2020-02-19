<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data = array(
			"base_url" => base_url()
		);

		$configs = array();
		foreach($this->db->get_where('configs')->result_array() as $config){
			$configs[$config['name']] = $config['value'];
		}

		$this->parser->parse('auth',array_merge($data,$configs));
	}

	public function mail_test(){

		$data = array(
			"to" => 'erlinumardani@gmail.com',
			"subject" => 'Notif',
			"message" => 'Test'
		);

		$this->load->model('notification_model');

		echo $this->notification_model->mail_notif($data);
	}

	public function register(){
		$data = array(
			"base_url" => base_url()
		);

		$configs = array();
		foreach($this->db->get_where('configs')->result_array() as $config){
			$configs[$config['name']] = $config['value'];
		}

		$this->parser->parse('register',array_merge($data,$configs));
	}

	public function authentication(){

		$data		= $this->input->post();

		$this->form_validation->set_data($data);

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required',
				array('required' => 'You must provide a %s.')
		);

		if ($this->form_validation->run() == TRUE)
		{
			$this->db->select('*');
			$user_data = $this->db->select('
				users.id AS user_id,
				users.*,
				persons.*')
			->join('persons','persons.id = users.person_id')
			->get_where('users', array('username' => $data['username']))
			->result_array();

			if(isset($user_data[0]['username'])){
				if (password_verify($data['password'], $user_data[0]['password'])) {
					$user_data[0]['logged_in'] = TRUE;
					$user_data[0]['password'] = NULL;
					$this->session->set_userdata($user_data[0]);
					echo json_encode(array("status"=>"success","messages"=>'Login success'));
				}else{
					echo json_encode(array("status"=>"failed","messages"=>'Wrong Password'));
				}
			}else{
				echo json_encode(array("status"=>"failed","messages"=>'Wrong Username/Password'));
			}
		}else
		{
			echo json_encode(array("status"=>"failed","messages"=>$this->form_validation->error_string()));
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('auth');
	}
}
