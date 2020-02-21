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
class Migration_create_table_users extends CI_Migration {


	protected $table = 'users';


	public function up()
	{
		$fields = array(
			'id'=> [
				'type' => 'BIGINT(20)',
				'auto_increment' => TRUE
			],
			'person_id' => [
				'type'   => 'BIGINT(20)'
			],
			'vendor_id' => [
				'type'   => 'INT(11)',
				'null' => TRUE
			],
			'role_id'  => [
				'type' => 'INT(11)',
				'null' => TRUE
			],
			'username'      => [
				'type'   => 'VARCHAR(50)'
			],
			'password'  => [
				'type' => 'TEXT'
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
				'person_id'  	=> 1,
				'username'  	=> "admin",
				'password'  	=> password_hash('infonusa', PASSWORD_BCRYPT, ['cost' => 10]),
				'role_id'  		=> 1,
			),
			
		);
		$this->db->insert_batch($this->table, $data);

		//create view
		$this->db->query(
			"CREATE 
				OR REPLACE VIEW `v_users` AS 
			SELECT
				b.id AS user_id,
				b.person_id,
				b.username,
				b.role_id,
				c.`name` AS role_name,
				a.*
			FROM
				persons AS a
				LEFT JOIN users AS b ON a.id = b.person_id
				LEFT JOIN roles AS c ON b.role_id = c.id"
		);
	}


	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}

}
