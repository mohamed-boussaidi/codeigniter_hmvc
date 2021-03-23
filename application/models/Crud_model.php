<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Crud_model
 * Crud class for the database
 * all the models should extend from
 * this class
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Crud_model extends CI_Model
{
    private $table;
    private $member;

    /**
     * call the parent constructor
     * take the name of the table as parameter
     * Crud_model constructor.
     * @param null $table
     */
    function __construct($table = null)
    {
        parent::__construct();
        $this->use_table($table);
        $this->member = $this->session->member_id ? $this->session->member_id :
            $this->session->client_id ? $this->session->client_id : 0;
        return $this->table;
    }

    /**
     * take the name of the table as parameter
     * @param $table
     */
    protected function use_table($table)
    {
        $this->table = $this->db->dbprefix($table);
    }

    /**
     * check if table exists in database
     * @return bool
     */
    public function table_exists()
    {
        return $this->db->table_exists($this->table) ? true : false;
    }

    /**
     * return an empty object
     * that contain the columns
     * names as prop without
     * any values assigned to
     * theme
     * @return stdClass
     */
    function get_columns_names()
    {
        $result = new stdClass();
        $fields = $this->db->field_data($this->table);
        foreach ($fields as $field) {
            $name = $field->name;
            $result->$name = "";
        }
        return $result;
    }

    function describe() {
        $sql = "DESCRIBE `$this->table`";
        return $this->db->query($sql)->result();
    }

    /**
     * return the first row that
     * match the id passed as parameter
     * if there is no result return false
     * @param int $id
     * @param bool $columns
     * @param bool $xss_filter
     * @return stdClass
     */
    function get_one($id = 0, $columns = false, $xss_filter = true)
    {
        return $this->get_one_where(array('id' => $id), $columns, $xss_filter);
    }

    /**
     * same as get one with possible
     * where clause which will be
     * passed as parameter
     * if there is no result return false
     * @param array $where
     * @param bool $columns
     * @param bool $xss_filter
     * @return bool|stdClass
     */
    function get_one_where($where = array(), $columns = false, $xss_filter = true)
    {
        $result = $this->db->get_where($this->table, $where, 1);
        if ($result->num_rows()) {
            return $xss_filter ? $this->xss_filter(array($result->row()), true) : $result->row();
        } else {
            return $columns ? $this->get_columns_names() : false;
        }
    }

    /**
     * apply xss filter on the output
     * @param array $outputs
     * @param bool $one
     * @return array|mixed|stdClass
     */
    private function xss_filter($outputs = array(), $one = false) {
        foreach ($outputs as $key => $output) {
            foreach ($output as $index => $row) {
                $output->$index = $this->security->xss_clean($row);
            }
        }
        return $one ? isset($outputs[0]) ? $outputs[0] : $this->get_columns_names() : $outputs;
    }

    /**
     * return all the rows of the table
     * if there is no result return
     * an empty array
     * @param bool $xss_filter
     * @return mixed
     */
    function get_all($xss_filter = true)
    {
        return $this->get_all_where(array(), $xss_filter);
    }

    /**
     * same as get all with possible
     * where clause which will be
     * passed as parameter, and without
     * include deleted option
     * if there is where_in as index
     * in the where array the value should be
     * an array
     * if there is no result return
     * an empty array
     * @param array $where
     * @param bool $xss_filter
     * @param bool $target
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    function get_all_where($where = array(), $xss_filter = true, $target = false, $limit = 1000000, $offset = 0)
    {
        $where_in = get_array_value($where, "where_in");
        if ($where_in) {
            foreach ($where_in as $key => $value) {
                $this->db->where_in($key, $value);
            }
            unset($where["where_in"]);
        }
        if ($target) {
            $result = $this->db->get_where($target, $where, $limit, $offset);
        } else {
            $result = $this->db->get_where($this->table, $where, $limit, $offset);
        }
        if ($result->num_rows()) {
            return $xss_filter ? $this->xss_filter($result->result()) : $result->result();
        }
        return array();
    }

    /**
     * takes fields to input theme as an array
     * and the id in case of update query
     * return the id of the inserted or updated
     * row in case of success or false
     * in case of fails
     * @param array $data
     * @param int $id
     * @return bool
     */
    function save(&$data = array(), $id = 0)
    {
        if ($id) {
            //update query
            $where = array("id" => $id);
            return $this->update_where($data, $where);
        } else {
            //insert query
            if ($this->db->insert($this->table, $data)) {
                $this->log("INSERT", $data);
                return $this->db->insert_id();
            }
            return false;
        }
    }

    /**
     * takes data and where arrays
     * return the id of the updated
     * row in case of success or false
     * in case of fails
     * @param array $data
     * @param array $where
     * @return bool
     */
    function update_where($data = array(), $where = array())
    {
        $result = $this->db->update($this->table, $data, $where);
        if ($result) {
            $this->log("UPDATE", $data, $where);
            $id = get_array_value($where, "id");
            if ($id) {
                return $id;
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * permanently delete a row
     * this functions takes an array
     * as where clause
     * and if the undo parameter = false
     * it will set deleted column to 1
     * else it will set it to 0
     * return true or false
     * @param array $where
     * @param bool|string $target
     * @return bool
     */
    function delete($where = array(), $target = false)
    {
        $target = !$target ? $this->table : $this->db->dbprefix($target);
        if ($target) {
            $data = $this->get_all_where($where, false, $target);
        } else {
            $data = $this->get_all_where($where, false);
        }
        $data = is_array($data) ? $data : array();
        $this->db->where($where);
        $this->log("DELETE", $data, $where);
        return $this->db->delete($target);
    }

    /**
     * same as delete but takes an id
     * as parameter
     * @param $id
     * @return bool
     */
    function delete_one($id)
    {
        return $this->delete(array("id" => $id));
    }

    /**
     * delete a field with
     * all his related items
     * in a list of tables
     * passed as parameter
     * return true or false
     * @param $id
     * @param string $field
     * @param array $tables
     * @return bool
     */
    function delete_with_elements($id, $field = "", $tables = array())
    {
        $result = $this->delete(array("id" => $id));
        foreach ($tables as $table) {
            $table = $this->db->dbprefix($table);
            $result = $result && $this->delete(array($field => $id), $table);
        }
        return $result;
    }

    /**
     * takes fields as array and key as string
     * and return an array with the key as index
     * and the fields as value
     * @param array $option_fields
     * @param string $key
     * @param array $where
     * @return array
     */
    function get_dropdown_list($option_fields = array(), $key = "id", $where = array())
    {
        $list_data = $this->get_all_where($where);
        $result = array();
        foreach ($list_data as $data) {
            $text = "";
            foreach ($option_fields as $option) {
                $text .= $data->$option . " ";
            }
            $result[$data->$key] = $text;
        }
        return $this->security->xss_clean($result);
    }

    /**
     * takes fields as array and key as string
     * and return an array with the key as index
     * and the fields as value
     * @param array $option_fields
     * @param string $key
     * @param array $where
     * @return array
     */
    function get_js_dropdown_list($option_fields = array(), $key = "id", $where = array())
    {
        $list_data = $this->get_all_where($where);
        $result = array();
        foreach ($list_data as $data) {
            $text = "";
            foreach ($option_fields as $option) {
                $text .= get_property($data, $option) . " ";
            }
            $result[] = array(
                "id" => get_property($data, $key),
                "text" => $text
            );

        }
        return $this->security->xss_clean($result);
    }

    /**
     * check if a value exists
     * and return true or false
     * @param $where
     * @return bool
     */
    function exists($where)
    {
        $result = $this->get_one_where($where);
        return !$result ? false : true;
    }

    /**
     * functions return
     * @param $result
     * @return bool
     */
    private function result($result)
    {
        if ($result->num_rows()) {
            return true;
        }
        return false;
    }

	/**
	 * Get all the tables names
	 * from the information schema
	 * @return array
	 */
    public function get_tables_names() {
    	$sql = "SELECT table_name FROM information_schema.tables where table_schema='" . $this->db->database . "'";
    	$result = $this->db->query($sql);
    	$tables_names = $result->num_rows() ? $result->result() : array();
    	$result = array();
    	foreach ($tables_names as $table_name) {
			$result[] = $this->db->dbprefix($table_name->table_name);
	    }
	    return $result;
    }

    /**
     * execute log message
     * @param string $action
     * @param array $data
     * @param array $where
     */
    private function log($action = "", $data = array(), $where = array())
    {
        $where_str = "";
        foreach ($where as $item) {
            $where_str .= $item . " ";
        }
        $where_str = empty($where_str) ? "" : "WHERE $where_str";
        $data_str = "";
        foreach ($data as $datum) {
            if (is_array($datum) || is_object($datum)) {
                foreach ($datum as $item) {
                    $data_str .= $item . " ";
                }
            } else {
                $data_str .= $datum . " ";
            }
        }
        log_message("action", "table : $this->table, member : $this->member, $action : $data_str, $where_str");
    }

}
