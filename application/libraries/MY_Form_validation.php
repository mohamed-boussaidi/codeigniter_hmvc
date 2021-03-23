<?php

class MY_Form_validation extends CI_Form_validation
{
	/**
	 * fix the form validation callback
	 * @var
	 */
	public $CI;
    /**
     * Is Unique
     *
     * Check if the input value doesn't already exist
     * in the specified database field.
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     */
    public function is_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0);
    }
}