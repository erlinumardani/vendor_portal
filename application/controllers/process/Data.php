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
		
		page_view($this->db->get_where('process_flow_nodes',array('id'=>$id))->row()->name, 'data', $content_data);
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

	function view($id)
	{

		$view_data = $this->db->where('id',$id)->get('v_request')->row();
		$fieldset = array(
			array(
				'name'=>'field_1',
				'label'=>'Invoice Number',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_1,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_2',
				'label'=>'Invoice Tax Number',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_2,
					'disabled' => true,
				),
			),
			array(
				'name'=>'request_date',
				'label'=>'Request Date',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => date_format(date_create($view_data->created_at),"Y-m-d"),
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_3',
				'label'=>'Currency',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_3,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_4',
				'label'=>'Customer PO Number',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_4,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_5',
				'label'=>'Region',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_5,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_6',
				'label'=>'Contract Number',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_6,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_7',
				'label'=>'Due Date',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_7,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_8',
				'label'=>'Based Amount',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_8,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_9',
				'label'=>'VAT',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-file-invoice-dollar',
				'custom_attributes'=>array(
					'value' => $view_data->field_9,
					'disabled' => true,
				),
			)
		);

		$content_data = array(
			'form_title'=>'View Detail',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1)
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'view', $content_data);
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

	function list($id)
    {
		$table = 'v_request'; //nama tabel dari database
		$column_order = array(null,'id', 'flow_node_name', 'requester_name','created_at'); //field yang ada di table user
		$column_search = array('id', 'flow_node_name', 'requester_name','created_at'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, 'flow_node_id = '.$id);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {

			switch ($field->flow_node_type) {
				case 'Start':
					$label = 'default';
					break;
				case 'IO':
					$label = 'primary';
					break;
				case 'Process':
					$label = 'primary';
					break;
				case 'Decision':
					$label = 'warning';
					break;
				case 'End':
					$label = 'success';
					break;
				
				default:
					$label = 'primary';
					break;
			}

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->requester_name;
            $row[] = '<span class="right badge badge-'.$label.'">'.$field->flow_node_name.'</span>';
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
}
