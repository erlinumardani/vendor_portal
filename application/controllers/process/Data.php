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

		$data = $this->db->get_where('process_flow_nodes',array('id'=>$id));

		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'node_type' => $data->row()->type,
			'node_id' => $id
		);
		
		page_view($data->row()->name, 'data', $content_data);
    }
    
    function form($id)
	{
		if(strlen($this->session->userdata('vendor_id'))>0){
			$get_vat = $this->db->get_where('vendors',array('id'=>$this->session->userdata('vendor_id')))->row()->vat;
		}else{
			$get_vat = '';
		}
		
		$fieldset = array(
			array(
				'name'=>'field_1',
				'label'=>'Invoice Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric'
				)
			),
			array(
				'name'=>'field_2',
				'label'=>'Invoice Tax Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric'
				)
			),
			array(
				'name'=>'request_date',
				'label'=>'Request Date',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => date("Y-m-d"),
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_3',
				'label'=>'Currency',
				'type'=>'select',
				'options'=>array('IDR'=>'IDR','USD'=>'USD',),
				'default_options'=>'IDR'
			),
			array(
				'name'=>'field_4',
				'label'=>'Customer PO Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric'
				)
			),
			array(
				'name'=>'field_5',
				'label'=>'Region',
				'type'=>'text'
			),
			array(
				'name'=>'field_6',
				'label'=>'Contract Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric'
				)
			),
			array(
				'name'=>'field_7',
				'label'=>'Due Date',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-inputmask-alias' => 'datetime',
					'data-inputmask-inputformat' => 'yyyy-mm-dd',
					'data-mask' => '',
					'im-insert' => 'false'
				)
			),
			array(
				'name'=>'field_8',
				'label'=>'Base Amount',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'currency'
				)
			),
			array(
				'name'=>'field_9',
				'label'=>'VAT',
				'type'=>'text',
				'custom_attributes'=>array(
					'value'=>$get_vat
				)
			),
			array(
				'name'=>'action',
				'type'=>'hidden',
				'custom_attributes'=>array("value"=>"Create")
			),
			array(
				'name'=>'flow_node_id',
				'type'=>'hidden',
				'custom_attributes'=>array("value"=>$id)
			)
		);

		$fieldset2 = array(
			array(
				'name'=>'name',
				'label'=>'Document Name',
				'type'=>'text'
			),
			array(
				'name'=>'file',
				'label'=>'File',
				'type'=>'file'
			)
		);

		$content_data = array(
			'form_title'=>'Basic Information',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'node_id' => $id
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
		$content_data['form2'] = form_render('add_attachments', $fieldset2, TRUE,TRUE,"$('#document_draft_list').DataTable().ajax.reload();","upload_attachment");
        page_view('Form', 'form', $content_data);
	}

	function update($id)
	{
		$view_data = $this->db->where('id',$id)->get('v_request')->row();
		
		$fieldset = array(
			array(
				'name'=>'field_1',
				'label'=>'Invoice Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_1,
					'disabled' => true,
					'data-input_type' => 'numeric'
				)
			),
			array(
				'name'=>'field_2',
				'label'=>'Invoice Tax Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_2,
					'disabled' => true,
					'data-input_type' => 'numeric'
				)
			),
			array(
				'name'=>'request_date',
				'label'=>'Request Date',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => date_format(date_create($view_data->created_at),"Y-m-d"),
					'disabled' => true,
				)
			),
			array(
				'name'=>'field_3',
				'label'=>'Currency',
				'type'=>'select',
				'options'=>array('IDR'=>'IDR','USD'=>'USD',),
				'default_options'=>$view_data->field_3,
				'custom_attributes'=>array(
					'disabled' => true,
				)
			),
			array(
				'name'=>'field_4',
				'label'=>'Customer PO Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					'value' => $view_data->field_4,
					'disabled' => true,
				)
			),
			array(
				'name'=>'field_5',
				'label'=>'Region',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_5,
					'disabled' => true,
				)
			),
			array(
				'name'=>'field_6',
				'label'=>'Contract Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					'value' => $view_data->field_6,
					'disabled' => true,
				)
			),
			array(
				'name'=>'field_7',
				'label'=>'Due Date',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-inputmask-alias' => 'datetime',
					'data-inputmask-inputformat' => 'yyyy-mm-dd',
					'data-mask' => '',
					'im-insert' => 'false',
					'value' => $view_data->field_7,
					'disabled' => true,
				)
			),
			array(
				'name'=>'field_8',
				'label'=>'Base Amount',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'currency',
					'value' => $view_data->field_8,
					'disabled' => true,
				)
			),
			array(
				'name'=>'field_9',
				'label'=>'VAT',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_9,
					'disabled' => true,
				)
			),
			array(
				'name'=>'process_action',
				'label'=>'Action',
				'type'=>'select',
				'options'=>array(''=>'Pilih','Approve'=>'Approve','Reject'=>'Reject'),
				'default_options'=>''
			),
			array(
				'name'=>'Notes',
				'type'=>'textarea',
			),
			array(
				'name'=>'action',
				'type'=>'hidden',
				'custom_attributes'=>array("value"=>"Update")
			),
			array(
				'name'=>'flow_node_id',
				'type'=>'hidden',
				'custom_attributes'=>array("value"=>$view_data->flow_node_id)
			),
			array(
				'name'=>'id',
				'type'=>'hidden',
				'custom_attributes'=>array("value"=>$view_data->id)
			)
		);

		$content_data = array(
			'form_title'=>'Basic Information',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'node_id' => $id,
			'flow_request_id' => $view_data->id
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view('Form', 'update', $content_data);
	}

	function view($id)
	{

		$view_data = $this->db->where('id',$id)->get('v_request')->row();
		$fieldset = array(
			array(
				'name'=>'field_1',
				'label'=>'Invoice Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_1,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_2',
				'label'=>'Invoice Tax Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_2,
					'disabled' => true,
				),
			),
			array(
				'name'=>'request_date',
				'label'=>'Request Date',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => date_format(date_create($view_data->created_at),"Y-m-d"),
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_3',
				'label'=>'Currency',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_3,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_4',
				'label'=>'Customer PO Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_4,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_5',
				'label'=>'Region',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_5,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_6',
				'label'=>'Contract Number',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_6,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_7',
				'label'=>'Due Date',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_7,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_8',
				'label'=>'Based Amount',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_8,
					'disabled' => true,
				),
			),
			array(
				'name'=>'field_9',
				'label'=>'VAT',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->field_9,
					'disabled' => true,
				),
			)
		);

		$content_data = array(
			'form_title'=>'Basic Information',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'flow_request_id' => $view_data->id
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'view', $content_data);
	}

	function form_submit()
	{
		$data = $this->input->post();
		$table = 'process_flow_request';
		$data['updated_by'] = $this->session->userdata('id');
		$action = $data['action'];
		$flow_node_id = $data['flow_node_id'];
		unset($data['action']);
		unset($data['flow_node_id']);

		if(isset($data['id'])){
			$flow_request_id = $data['id'];
			unset($data['id']);
		}

		if(isset($data['notes'])){
			$notes = $data['notes'];
			unset($data['notes']);
		}

		if(isset($data['process_action'])){
			$process_action = $data['process_action'];
			unset($data['process_action']);

			if($process_action=='Approve'){
				$flow_update = $this->get_flow($flow_node_id,'next');
			}else{
				$flow_update = $this->get_flow($flow_node_id,'previous');
			}
		}

		$result = true;
		$message = 'Data failed to insert';

		$this->db->trans_start();

		if($action == "Update"){
			$this->db->where('id',$flow_request_id)->update($table,array(
				'flow_node_id' => $flow_update,
				'updated_by' => NULL
			));
			$this->db->where('flow_request_id',$flow_request_id)
			->where('updated_by',$this->session->userdata('user_id'))
			->where('action','')
			->update('process_flow_request_logs',array(
				'action' => $process_action,
				'notes' => $notes,
				'end' => date('Y-m-d H:i:s')
			));
		}else{
			
			$this->db->insert($table,array(
				'flow_ticket_id' => $this->create_ticket(),
				'flow_node_id' => $this->get_flow($flow_node_id,'next'),
				'requested_by' => $this->session->userdata('user_id'),
			));
			$data['flow_request_id'] = $this->db->insert_id();
			$this->db->insert('process_flow_request_detail',$data);
			$this->db->where('updated_by',$this->session->userdata('user_id'))
			->where('flow_request_id',0)
			->update('process_flow_request_attachments',array(
				'flow_request_id' => $data['flow_request_id']
			));
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

		if($this->session->userdata('role_id')=='6'){
			if($id==1){
				$list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, 'flow_node_type != "End" and requested_by ="'.$this->session->userdata('user_id').'"');
			}else{
				$list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, array('flow_node_id'=>$id,'requested_by'=>$this->session->userdata('user_id')));
			}
		}else{
			$list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, 'flow_node_id ="'.$id.'" and (updated_by is null or updated_by="'.$this->session->userdata('user_id').'")');
		}
        
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {

			if($this->session->userdata('role_id')=='6'||$this->session->userdata('role_id')=='1'||$field->flow_node_type=='End'){
				$action = '';
			}else{
				$action = '<button class="btn-sm update btn-primary" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Edit this row" style="border-radius: 50%;"><i class="fas fa-edit"></i></button>';
			}

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
			$row[] = $field->flow_ticket_id;
            $row[] = $field->requester_name;
            $row[] = '<span class="right badge badge-'.$label.'">'.$field->flow_node_name.'</span>';
            $row[] = date_format(date_create($field->created_at),"Y-m-d");
			$row[] = $action;
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

	function attachments_draft()
    {
		$table = 'process_flow_request_attachments'; //nama tabel dari database
		$column_order = array(null, 'name','created_at'); //field yang ada di table user
		$column_search = array('name','created_at'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		$filter = 'flow_request_id = 0 and updated_by = "'.$this->session->userdata('user_id').'"'; // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, $filter);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = date_format(date_create($field->created_at),"Y-m-d");
			$row[] = '';//'<button class="btn-sm delete btn-danger" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Delete this row" style="border-radius: 50%;"><i class="fas fa-trash"></i></button>';
			$row[] = $field->id;
			$row[] = $field->url;
 
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

	function attachments_list($id)
    {
		$table = 'process_flow_request_attachments'; //nama tabel dari database
		$column_order = array(null, 'name','created_at'); //field yang ada di table user
		$column_search = array('name','created_at'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		$filter = 'flow_request_id = '.$id; // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, $filter);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = date_format(date_create($field->created_at),"Y-m-d");
			$row[] = '';//'<button class="btn-sm delete btn-danger" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Delete this row" style="border-radius: 50%;"><i class="fas fa-trash"></i></button>';
			$row[] = $field->id;
			$row[] = $field->url;
 
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

	function logs($id)
    {
		$table = 'v_request_logs'; //nama tabel dari database
		$column_order = array(null, 'flow_node_name','updater','action','start','end','created_at'); //field yang ada di table user
		$column_search = array('flow_node_name','updater','action','start','end','created_at'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		$filter = 'flow_request_id = '.$id; // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, $filter);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->flow_node_name;
			$row[] = $field->updater;
			$row[] = $field->action;
			$row[] = $field->notes;
            $row[] = $field->start;
            $row[] = $field->end;
            $row[] = $field->duration;
			$row[] = '';//'<button class="btn-sm delete btn-danger" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Delete this row" style="border-radius: 50%;"><i class="fas fa-trash"></i></button>';
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

	public function get_flow($node_id,$direction){
		if($direction=="next"){
			return $this->db->get_where('process_flow',array('current_node_id'=>$node_id))->row()->next_node_id;
		}else{
			return $this->db->get_where('process_flow',array('current_node_id'=>$node_id))->row()->previous_node_id;
		}
	}	

	public function create_ticket(){

		$batch = '';
        if(strtotime(DATE('H:i:s'))>=strtotime('08:00:00') && strtotime(DATE('H:i:s'))<=strtotime('12:00:00')){
            $batch = 'A';
        }else{
            $batch = 'B';
		}
		
		$max = $this->db->query("select max(CAST(SUBSTRING(flow_ticket_id,6,3) AS SIGNED))+1 as `max` from process_flow_request where SUBSTRING(flow_ticket_id,1,5) = '".DATE('md').$batch."'")->result()[0]->max;

		if(strlen($max)<1){
			$max = '1';
		}

		if(strlen($max)==1){
			$nol ='00';	
		}elseif(strlen($max)==2){
			$nol ='0';
		}else{
			$nol ='';
		}

		return DATE('md').$batch.$nol.$max;

	}

	public function upload_attachment(){

		$data = $this->input->post();
		$config['upload_path']          = './storage/'.$this->session->userdata('user_id').'/'.'attachments';
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 5000;
        $config['overwrite']            = TRUE;
		$config['file_name']            = $this->session->userdata('user_id').time();
		
		!is_dir($config['upload_path'])?mkdir($config['upload_path'],0777,TRUE):'';

		$this->load->library('upload', $config);
		
		if ($this->upload->do_upload('file')){

			$data['url'] = 'storage/'.$this->session->userdata('user_id').'/attachments/'.$this->upload->data('file_name');
			$data['updated_by'] = $this->session->userdata('user_id');

			$this->db->insert('process_flow_request_attachments',$data);

			echo json_encode(array(
				"status"=>TRUE,
				"message"=>$this->upload->display_errors()
			));
		}else{
			echo json_encode(array(
				"status"=>FALSE,
				"message"=> $this->upload->display_errors()//$this->upload->data()
			));
		}
		
	}

	function pickup(){
		
		$id = $this->input->post('id');
		$node_id = $this->input->post('node_id');

		if($this->db->get_where('process_flow_request',array('updated_by'=>$this->session->userdata('user_id'),'id'=>$id))->num_rows()==0){
			
			if(
				$this->db->where('id',$id)->update('process_flow_request',array('updated_by'=>$this->session->userdata('user_id'))) &&
				$this->db->insert('process_flow_request_logs',array(
					"flow_request_id"=>$id,
					"flow_node_id"=>$node_id,
					"start"=>date('Y-m-d H:i:s'),
					'updated_by'=>$this->session->userdata('user_id')
				))
			){
				$result = array("status"=>TRUE,"message"=>"Data has been picked Up by you","id"=>$id);
			}else{
				$result = array("status"=>FALSE,"message"=>"Data failed to be picked up","id"=>$id);
			}
		}else{
			$result = array("status"=>FALSE,"message"=>"Data already picked up by you","id"=>$id);
		}
		

		echo json_encode($result);
	}
}
