<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

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

    public function __construct(){
		parent::__construct();
		
        if(!$this->session->userdata('logged_in') == true){
			redirect('auth');
		}
		$this->title = 'Process';
    }
    
	function index()
	{
		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1)
		);
		
		page_view($this->title, 'data', $content_data);
    }

    function get($id)
	{
		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'node_id' => $id
		);
		
		page_view($this->title, 'data', $content_data);
    }
    
    function form()
	{
		
		$content_data = array(
			'form_title'=>'New Role Form',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1)
		);

		$fieldset = array(
			array(
				'name'=>'id',
				'label'=>'Role ID',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-hashtag',
				'custom_attributes'=>array(
					"placeholder"=>"Role ID",
					"data-input_type"=>"numeric"
				),
			),
			array(
				'name'=>'Name',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-user',
				'custom_attributes'=>array("placeholder"=>"Role Name")
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Create")
			)
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'form', $content_data);
	}

	function form_submit()
	{
		$data = $this->input->post();
		$table = 'roles';
		$data['updated_by'] = $this->session->userdata('id');
		$action = $data['action'];
		unset($data['action']);
		if(isset($data['password'])){
			$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]);
		}

		$this->db->trans_start();
		$result = true;
		$message = 'Data failed to insert';

		if($action == "Update"){
			$this->db->where('id',$data['id'])->update($table,$data);
		}else{
			if($this->db->get_where('roles',array('id'=>$data['id']))->num_rows()==0){
				$this->db->insert($table,$data);
			}else{
				$result = false;
				$message = 'Role ID '.$data['id'].' already exist';
			}
		}

		if($this->db->trans_complete() && $result){
			$result = array("status"=>TRUE,"message"=>"Data inserted");
		}else{
			$result = array("status"=>FALSE,"message"=>$message);
		}

		echo json_encode($result);
	}

	function list()
    {
		$table = 'v_request'; //nama tabel dari database
		$column_order = array(null,'id', 'flow_node_name', 'requester_name','created_at'); //field yang ada di table user
		$column_search = array('id', 'flow_node_name', 'requester_name','created_at'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->requester_name;
            $row[] = $field->flow_node_name;
            $row[] = date_format(date_create($field->created_at),"Y-m-d");
			$row[] = '
				<button class="btn-sm update btn-primary" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Edit this row" style="border-radius: 50%;"><i class="fas fa-edit"></i></button>
			';
			$row[] = $field->id;
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable_model->count_all($table),
            "recordsFiltered" => $this->datatable_model->count_filtered($table, $column_order, $column_search, $order),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	function delete(){
		
		$id = $this->input->post('id');

		if($this->db->where('id',$id)->delete('roles')){
			$result = array("status"=>TRUE,"message"=>"Data has been deleted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to be deleted");
		}

		echo json_encode($result);
	}
}
