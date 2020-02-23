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
class Migration_create_table_process_flow_request_detail extends CI_Migration {


	protected $table = 'process_flow_request_detail';


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
			'field_1'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_2'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_3'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_4'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_5'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_6'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_7'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_8'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_9'=> [
				'type' => 'VARCHAR(200)'
			],
			'field_10'=> [
				'type' => 'VARCHAR(200)'
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

		//create view
		$this->db->query(
			"CREATE 
				OR REPLACE VIEW `v_request` AS 
			SELECT
				`a`.`id` AS `id`,
				`a`.`flow_node_id` AS `flow_node_id`,
				`a`.`flow_ticket_id` AS `flow_ticket_id`,
				`c`.`name` AS `flow_node_name`,
				`c`.`type` AS `flow_node_type`,
				`c`.`privileges` AS `privileges`,
				`e`.`fullname` AS `requester_name`,
				`a`.`requested_by` AS `requested_by`,
				`b`.`field_1` AS `field_1`,
				`b`.`field_2` AS `field_2`,
				`b`.`field_3` AS `field_3`,
				`b`.`field_4` AS `field_4`,
				`b`.`field_5` AS `field_5`,
				`b`.`field_6` AS `field_6`,
				`b`.`field_7` AS `field_7`,
				`b`.`field_8` AS `field_8`,
				`b`.`field_9` AS `field_9`,
				`b`.`field_10` AS `field_10`,
				`a`.`updated_by` AS `updated_by`,
				`a`.`created_at` AS `created_at`,
				`a`.`updated_at` AS `updated_at` 
			FROM
				`process_flow_request` AS `a`
			LEFT JOIN `process_flow_request_detail` AS `b` ON `a`.`id` = `b`.`flow_request_id`
			LEFT JOIN `process_flow_nodes` AS `c` ON `c`.`id` = `a`.`flow_node_id`
			LEFT JOIN `users` AS `d` ON `d`.`id` = `a`.`requested_by` 
			LEFT JOIN `persons` AS `e` ON `e`.`id` = `d`.`person_id`"
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
