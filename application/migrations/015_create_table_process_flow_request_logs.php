<?php
/**
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_create_table_users
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_process_flow_request_logs extends CI_Migration {


	protected $table = 'process_flow_request_logs';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'flow_request_id'=> [
				'type' => 'INT(11)'
			],
			'action'=> [
				'type' => 'VARCHAR(20)'
			],
			'notes'=> [
				'type' => 'TEXT'
			],
			'start' => [
				'type' => 'DATETIME',
				'null' => TRUE
			],
			'end' => [
				'type' => 'DATETIME',
				'null' => TRUE
			],
			'updated_by' => [
				'type' => 'VARCHAR(20)',
				'null' => TRUE,
				'unsigned' => TRUE,
				'default' => 'System'
			],
			'created_at' => [
				'type' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP' 
			],
			'updated_at' => [
				'type' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
				'null' => TRUE
			]
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->table, TRUE);

		//db seed
		$data = array(
			array(
				'id'  		=> 1,
				'name'  	=> "Admin"
			),
			array(
				'id'  		=> 2,
				'name'  	=> "SSO Team Leader"
			),
			array(
				'id'  		=> 3,
				'name'  	=> "SSO Verification"
			),
			array(
				'id'  		=> 4,
				'name'  	=> "Finance / Approver"
			),
			array(
				'id'  		=> 5,
				'name'  	=> "SSO Processing"
			),
			array(
				'id'  		=> 6,
				'name'  	=> "Vendor"
			)
		);
		$this->db->insert_batch($this->table, $data);
	}


	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}

}