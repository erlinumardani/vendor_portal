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
class Migration_create_table_menus extends CI_Migration {


	protected $table = 'menus';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'sequence' => [
				'type' => 'INT(11)',
				'default' => 1
			],
			'type' => [
				'type' => 'ENUM("Main","Sub","Single")',
				'default' => "Main"
			],
			'main_id' => [
				'type' => 'INT(11)',
				'null' => TRUE
			],
			'name' => [
				'type' => 'VARCHAR(50)'
			],
			'url' => [
				'type' => 'TEXT',
				'null' => TRUE
			],
			'icon' => [
				'type' => 'TEXT',
				'null' => TRUE
			],
			'privileges' => [
				'type' => 'JSON',
				'null' => TRUE
			],
			'status' => [
				'type' => 'ENUM("Active","Inactive")',
				'default' => "Active",
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
				'sequence'  => 1,
				'type'  	=> "Single",
				'main_id'  	=> NULL,
				'name'  	=> "Dashboard",
				'url'  		=> "dashboard/data",
				'icon'  	=> 'tachometer-alt',
				'privileges'=> '[1,2]'
			),
			array(
				'id'  		=> 2,
				'sequence'  => 2,
				'type'  	=> "Main",
				'main_id'  	=> NULL,
				'name'  	=> "Master Setting",
				'url'  		=> "#",
				'icon'  	=> 'cogs',
				'privileges'=> '[1]'
			),
			array(
				'id'  		=> 3,
				'sequence'  => 1,
				'type'  	=> "Sub",
				'main_id'  	=> 2,
				'name'  	=> "User Management",
				'url'  		=> "users/data",
				'icon'  	=> 'users',
				'privileges'=> '[1]'
			),
			array(
				'id'  		=> 4,
				'sequence'  => 2,
				'type'  	=> "Sub",
				'main_id'  	=> 2,
				'name'  	=> "Menu Management",
				'url'  		=> "menus/data",
				'icon'  	=> 'bars',
				'privileges'=> '[1]'
			),
			array(
				'id'  		=> 5,
				'sequence'  => 3,
				'type'  	=> "Sub",
				'main_id'  	=> 2,
				'name'  	=> "roles",
				'url'  		=> "roles/data",
				'icon'  	=> 'user-shield',
				'privileges'=> '[1]'
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
