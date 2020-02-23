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
class Migration_create_table_process_flow_nodes extends CI_Migration {


	protected $table = 'process_flow_nodes';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'flow_process_id'=> [
				'type' => 'INT(11)'
			],
			'name'=> [
				'type' => 'VARCHAR(50)'
			],
			'privileges' => [
				'type' => 'JSON',
				'null' => TRUE
			],
			'type' => [
				'type' => 'ENUM("Start","IO","Process","Decision","End")',
				'default' => 'Start'
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
				'id'  				=> 1,
				'flow_process_id'  	=> 1,
				'name'  			=> "Request",
				'privileges'  		=> '{"roles":[6]}',
				'type'  			=> "IO"
			),
			array(
				'id'  				=> 2,
				'flow_process_id'  	=> 1,
				'name'  			=> "Verification",
				'privileges'  		=> '{"roles":[3]}',
				'type'  			=> "Process"
			),
			array(
				'id'  				=> 3,
				'flow_process_id'  	=> 1,
				'name'  			=> "Approval",
				'privileges'  		=> '{"roles":[4]}',
				'type'  			=> "Decision"
			),
			array(
				'id'  				=> 4,
				'flow_process_id'  	=> 1,
				'name'  			=> "Processing",
				'privileges'  		=> '{"roles":[5]}',
				'type'  			=> "Process"
			),
			array(
				'id'  				=> 5,
				'flow_process_id'  	=> 1,
				'name'  			=> "Done",
				'privileges'  		=> '{"roles":[6]}',
				'type'  			=> "End"
			),
			array(
				'id'  				=> 6,
				'flow_process_id'  	=> 1,
				'name'  			=> "Rejected",
				'privileges'  		=> '{"roles":[6]}',
				'type'  			=> "End"
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
