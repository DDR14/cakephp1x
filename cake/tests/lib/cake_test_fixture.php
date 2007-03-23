<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake
 * @subpackage		cake.cake.tests.libs
 * @since			CakePHP(tm) v 1.2.0.4667
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package    cake
 * @subpackage cake.cake.tests.lib
 */
class CakeTestFixture extends Object {
	var $db = null;
/**
 * Instantiate the fixture.
 *
 * @param object	Cake's DBO driver (e.g: DboMysql).
 *
 * @access public
 */
	function __construct(&$db) {
		$this->db =& $db;

		if (!isset($this->table)) {
			$this->table = Inflector::underscore(Inflector::pluralize($this->name));
		}

		if (!isset($this->primaryKey) && isset($this->fields['id'])) {
			$this->primaryKey = 'id';
		}

		if (isset($this->primaryKey) && !is_array($this->primaryKey)) {
			$this->primaryKey = array( $this->primaryKey );
		}
	}
/**
 * Run before all tests execute, should return SQL statement to create table for this fixture.
 *
 * @return string	SQL CREATE TABLE statement, false if not applicable.
 *
 * @access public
 */
	function create() {
		if (!isset($this->_create)) {
			if (!isset($this->fields) || empty($this->fields)) {
				return null;
			}

			$create = 'CREATE TABLE ' . $this->db->name($this->db->config['prefix'] . $this->table) . ' (' . "\n";

			foreach($this->fields as $field => $attributes) {
				if (!is_array($attributes)) {
					$attributes = array('type' => $attributes);
				} else if (isset($attributes['key']) && low($attributes['key']) == 'primary' && !isset($this->primaryKey)) {
					$this->primaryKey = array ( $field );
				}

				$column = array($field, $attributes['type']);
				unset($attributes['type']);

				if (!empty($attributes)) {
					$column = array_merge($column, $attributes);
				}

				$create .= $this->db->generateColumnSchema($column) . ',' . "\n";
			}

			if (isset($this->primaryKey)) {
				foreach($this->primaryKey as $index => $field) {
					$this->primaryKey[$index] = $this->db->name($field);
				}
			}

			if (!isset($this->primaryKey)) {
				$create = substr($create, 0, -1);
			} else {
				$create .= 'PRIMARY KEY(' . implode(', ', $this->primaryKey) . ')' . "\n";
			}

			$create .= ')';

			$this->_create = $create;
		}

		return $this->_create;
	}
/**
 * Run after all tests executed, should return SQL statement to drop table for this fixture.
 *
 * @return string	SQL DROP TABLE statement, false if not applicable.
 *
 * @access public
 */
	function drop() {
		if (!isset($this->_drop)) {
			$this->_drop = 'DROP TABLE ' . $this->db->name($this->db->config['prefix'] . $this->table);
		}

		return $this->_drop;
	}
/**
 * Run after each tests is executed, should return SQL statement to empty of records the table for this fixture.
 *
 * @return string	SQL TRUNCATE TABLE statement, false if not applicable.
 *
 * @access public
 */
	function truncate() {
		if (!isset($this->_truncate)) {
			$this->_truncate = 'TRUNCATE ' . $this->db->name($this->db->config['prefix'] . $this->table);
		}

		return $this->_truncate;
	}
/**
 * Run before each tests is executed, should return a set of SQL statements to insert records for the table of this fixture.
 *
 * @return array	SQL INSERT statements, empty array if not applicable.
 *
 * @access public
 */
	function insert() {
		if (!isset($this->_insert)) {
			$inserts = array();

			if (isset($this->records) && !empty($this->records)) {
				foreach($this->records as $record) {
					$fields = array_keys($record);
					$values = array_values($record);

					$insert = 'INSERT INTO ' . $this->db->name($this->db->config['prefix'] . $this->table) . '(';

					foreach($fields as $field) {
						$insert .= $this->db->name($field) . ',';
					}
					$insert = substr($insert, 0, -1);

					$insert .= ') VALUES (';

					foreach($values as $values) {
						$insert .= $this->db->value($values) . ',';
					}
					$insert = substr($insert, 0, -1);

					$insert .= ')';

					$inserts[] = $insert;
				}
			}

			$this->_insert = $inserts;
		}

		return $this->_insert;
	}
}
?>