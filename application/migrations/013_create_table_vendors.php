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
class Migration_create_table_vendors extends CI_Migration {


	protected $table = 'vendors';

	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'cocd' => [
				'type' => 'INT(11)',
			],
			'code'=> [
				'type' => 'VARCHAR(20)'
			],
			'name'=> [
				'type' => 'VARCHAR(50)'
			],
			'street'=> [
				'type' => 'VARCHAR(100)',
				'null' => TRUE
			],
			'account_group'=> [
				'type' => 'VARCHAR(10)',
				'null' => TRUE
			],
			'city'=> [
				'type' => 'VARCHAR(10)',
				'null' => TRUE
			],
			'postal_code'=> [
				'type' => 'VARCHAR(10)',
				'null' => TRUE
			],
			'country_id'=> [
				'type' => 'VARCHAR(10)',
				'null' => TRUE
			],
			'currency'=> [
				'type' => 'VARCHAR(10)',
				'null' => TRUE
			],
			'vat'=> [
				'type' => 'VARCHAR(50)',
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

	}


	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}

}
