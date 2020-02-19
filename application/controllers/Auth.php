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

	public function register_submit(){

		$this->load->model('notification_model');

		$data = array(
			"email"=>$this->input->post('email'),
			"fullname"=>$this->input->post('fullname'),
			"password"=>$this->input->post('password')
		);

		$token = base64_encode(json_encode($data));

		$message = 'Click Link below to verify your account: <br /><a href="'.base_url().'auth/verify/'.$token.'">Verify</a>';
		
		$this->db->insert('users_verification',array(
			"token" => $token,
			"expired_at" => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")."+ 1 hour"))
		));

		$mail = array(
			"to" => $data['email'],
			"subject" => 'SSO - Account Verification',
			"message" => $message
		);

		if($this->notification_model->mail_notif($mail)==TRUE){
			echo json_encode(array(
				"status" => TRUE,
				"message" => "Please check your email for verifivation"
			));
		}else{
			echo json_encode(array(
				"status" => FALSE,
				"message" => "Please try again later"
			));
		}
	}

	public function verify($token){
		
		$data = json_decode(base64_decode($token));

		$data_view = array(
			"base_url" => base_url()
		);

		$configs = array();
		foreach($this->db->get_where('configs')->result_array() as $config){
			$configs[$config['name']] = $config['value'];
		}

		$check = $this->db->limit(1)->query('select * from users_verification where token="'.trim($token).'" and expired_at > NOW() and status = "Waiting"');

		if($check->num_rows()>0){
			$this->db->where('id',$check->row()->id)->update('users_verification',array('status'=>'Verified'));
			$this->db->insert('persons',array(
				'fullname'  => $data->fullname,
				'email'  	=> $data->email
			));

			$this->db->insert('users',array(
				'person_id'  => $this->db->insert_id(),
				'username'  => $data->email,
				'password'  => password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 10]),
				'role_id'  => 6,
			));

			$data_view['alert']="
				$('#success').show(); 
				$('#success_message').append('Your are verified. please try to login.'); 
				setTimeout(function() {
					$('#success').slideUp('slow');
					$('#success').empty(); 
				}, 5000);
			";
		}else{
			$data_view['alert']="
				$('#alert').show(); 
				$('#error').append('Your link has been expired, please retry to register.'); 
				setTimeout(function() {
					$('#alert').slideUp('slow');
					$('#error').empty(); 
				}, 5000);
			";
		}

		$this->parser->parse('auth',array_merge($data_view,$configs));

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
