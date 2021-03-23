<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_code
 * Create or delete the code table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 * @company http://mind.engineering
 */
class Migration_Countries extends CI_Migration 
{
	public function up() 
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
		));
				$this->dbforge->add_field(array(
			'code' => array(
				'type' => 'varchar(2)',

			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('countries', true);
		$data = array(
			array(
				'code' => "AF",
			),
			array(
				'code' => "AL",
			),
			array(
				'code' => "DZ",
			),
			array(
				'code' => "DS",
			),
			array(
				'code' => "AD",
			),
			array(
				'code' => "AO",
			),
			array(
				'code' => "AI",
			),
			array(
				'code' => "AQ",
			),
			array(
				'code' => "AG",
			),
			array(
				'code' => "AR",
			),
			array(
				'code' => "AM",
			),
			array(
				'code' => "AW",
			),
			array(
				'code' => "AU",
			),
			array(
				'code' => "AT",
			),
			array(
				'code' => "AZ",
			),
			array(
				'code' => "BS",
			),
			array(
				'code' => "BH",
			),
			array(
				'code' => "BD",
			),
			array(
				'code' => "BB",
			),
			array(
				'code' => "BY",
			),
			array(
				'code' => "BE",
			),
			array(
				'code' => "BZ",
			),
			array(
				'code' => "BJ",
			),
			array(
				'code' => "BM",
			),
			array(
				'code' => "BT",
			),
			array(
				'code' => "BO",
			),
			array(
				'code' => "BA",
			),
			array(
				'code' => "BW",
			),
			array(
				'code' => "BV",
			),
			array(
				'code' => "BR",
			),
			array(
				'code' => "IO",
			),
			array(
				'code' => "BN",
			),
			array(
				'code' => "BG",
			),
			array(
				'code' => "BF",
			),
			array(
				'code' => "BI",
			),
			array(
				'code' => "KH",
			),
			array(
				'code' => "CM",
			),
			array(
				'code' => "CA",
			),
			array(
				'code' => "CV",
			),
			array(
				'code' => "KY",
			),
			array(
				'code' => "CF",
			),
			array(
				'code' => "TD",
			),
			array(
				'code' => "CL",
			),
			array(
				'code' => "CN",
			),
			array(
				'code' => "CX",
			),
			array(
				'code' => "CC",
			),
			array(
				'code' => "CO",
			),
			array(
				'code' => "KM",
			),
			array(
				'code' => "CG",
			),
			array(
				'code' => "CK",
			),
			array(
				'code' => "CR",
			),
			array(
				'code' => "HR",
			),
			array(
				'code' => "CU",
			),
			array(
				'code' => "CY",
			),
			array(
				'code' => "CZ",
			),
			array(
				'code' => "DK",
			),
			array(
				'code' => "DJ",
			),
			array(
				'code' => "DM",
			),
			array(
				'code' => "DO",
			),
			array(
				'code' => "TP",
			),
			array(
				'code' => "EC",
			),
			array(
				'code' => "EG",
			),
			array(
				'code' => "SV",
			),
			array(
				'code' => "GQ",
			),
			array(
				'code' => "ER",
			),
			array(
				'code' => "EE",
			),
			array(
				'code' => "ET",
			),
			array(
				'code' => "FK",
			),
			array(
				'code' => "FO",
			),
			array(
				'code' => "FJ",
			),
			array(
				'code' => "FI",
			),
			array(
				'code' => "FR",
			),
			array(
				'code' => "FX",
			),
			array(
				'code' => "GF",
			),
			array(
				'code' => "PF",
			),
			array(
				'code' => "TF",
			),
			array(
				'code' => "GA",
			),
			array(
				'code' => "GM",
			),
			array(
				'code' => "GE",
			),
			array(
				'code' => "DE",
			),
			array(
				'code' => "GH",
			),
			array(
				'code' => "GI",
			),
			array(
				'code' => "GK",
			),
			array(
				'code' => "GR",
			),
			array(
				'code' => "GL",
			),
			array(
				'code' => "GD",
			),
			array(
				'code' => "GP",
			),
			array(
				'code' => "GU",
			),
			array(
				'code' => "GT",
			),
			array(
				'code' => "GN",
			),
			array(
				'code' => "GW",
			),
			array(
				'code' => "GY",
			),
			array(
				'code' => "HT",
			),
			array(
				'code' => "HM",
			),
			array(
				'code' => "HN",
			),
			array(
				'code' => "HK",
			),
			array(
				'code' => "HU",
			),
			array(
				'code' => "IS",
			),
			array(
				'code' => "IN",
			),
			array(
				'code' => "IM",
			),
			array(
				'code' => "ID",
			),
			array(
				'code' => "IR",
			),
			array(
				'code' => "IQ",
			),
			array(
				'code' => "IE",
			),
			array(
				'code' => "IT",
			),
			array(
				'code' => "CI",
			),
			array(
				'code' => "JE",
			),
			array(
				'code' => "JM",
			),
			array(
				'code' => "JP",
			),
			array(
				'code' => "JO",
			),
			array(
				'code' => "KZ",
			),
			array(
				'code' => "KE",
			),
			array(
				'code' => "KI",
			),
			array(
				'code' => "KP",
			),
			array(
				'code' => "KR",
			),
			array(
				'code' => "XK",
			),
			array(
				'code' => "KW",
			),
			array(
				'code' => "KG",
			),
			array(
				'code' => "LA",
			),
			array(
				'code' => "LV",
			),
			array(
				'code' => "LB",
			),
			array(
				'code' => "LS",
			),
			array(
				'code' => "LR",
			),
			array(
				'code' => "LY",
			),
			array(
				'code' => "LI",
			),
			array(
				'code' => "LT",
			),
			array(
				'code' => "LU",
			),
			array(
				'code' => "MO",
			),
			array(
				'code' => "MK",
			),
			array(
				'code' => "MG",
			),
			array(
				'code' => "MW",
			),
			array(
				'code' => "MY",
			),
			array(
				'code' => "MV",
			),
			array(
				'code' => "ML",
			),
			array(
				'code' => "MT",
			),
			array(
				'code' => "MH",
			),
			array(
				'code' => "MQ",
			),
			array(
				'code' => "MR",
			),
			array(
				'code' => "MU",
			),
			array(
				'code' => "TY",
			),
			array(
				'code' => "MX",
			),
			array(
				'code' => "FM",
			),
			array(
				'code' => "MD",
			),
			array(
				'code' => "MC",
			),
			array(
				'code' => "MN",
			),
			array(
				'code' => "ME",
			),
			array(
				'code' => "MS",
			),
			array(
				'code' => "MA",
			),
			array(
				'code' => "MZ",
			),
			array(
				'code' => "MM",
			),
			array(
				'code' => "NA",
			),
			array(
				'code' => "NR",
			),
			array(
				'code' => "NP",
			),
			array(
				'code' => "NL",
			),
			array(
				'code' => "AN",
			),
			array(
				'code' => "NC",
			),
			array(
				'code' => "NZ",
			),
			array(
				'code' => "NI",
			),
			array(
				'code' => "NE",
			),
			array(
				'code' => "NG",
			),
			array(
				'code' => "NU",
			),
			array(
				'code' => "NF",
			),
			array(
				'code' => "MP",
			),
			array(
				'code' => "NO",
			),
			array(
				'code' => "OM",
			),
			array(
				'code' => "PK",
			),
			array(
				'code' => "PW",
			),
			array(
				'code' => "PS",
			),
			array(
				'code' => "PA",
			),
			array(
				'code' => "PG",
			),
			array(
				'code' => "PY",
			),
			array(
				'code' => "PE",
			),
			array(
				'code' => "PH",
			),
			array(
				'code' => "PN",
			),
			array(
				'code' => "PL",
			),
			array(
				'code' => "PT",
			),
			array(
				'code' => "PR",
			),
			array(
				'code' => "QA",
			),
			array(
				'code' => "RE",
			),
			array(
				'code' => "RO",
			),
			array(
				'code' => "RU",
			),
			array(
				'code' => "RW",
			),
			array(
				'code' => "KN",
			),
			array(
				'code' => "LC",
			),
			array(
				'code' => "VC",
			),
			array(
				'code' => "WS",
			),
			array(
				'code' => "SM",
			),
			array(
				'code' => "ST",
			),
			array(
				'code' => "SA",
			),
			array(
				'code' => "SN",
			),
			array(
				'code' => "RS",
			),
			array(
				'code' => "SC",
			),
			array(
				'code' => "SL",
			),
			array(
				'code' => "SG",
			),
			array(
				'code' => "SK",
			),
			array(
				'code' => "SI",
			),
			array(
				'code' => "SB",
			),
			array(
				'code' => "SO",
			),
			array(
				'code' => "ZA",
			),
			array(
				'code' => "GS",
			),
			array(
				'code' => "ES",
			),
			array(
				'code' => "LK",
			),
			array(
				'code' => "SH",
			),
			array(
				'code' => "PM",
			),
			array(
				'code' => "SD",
			),
			array(
				'code' => "SR",
			),
			array(
				'code' => "SJ",
			),
			array(
				'code' => "SZ",
			),
			array(
				'code' => "SE",
			),
			array(
				'code' => "CH",
			),
			array(
				'code' => "SY",
			),
			array(
				'code' => "TW",
			),
			array(
				'code' => "TJ",
			),
			array(
				'code' => "TZ",
			),
			array(
				'code' => "TH",
			),
			array(
				'code' => "TG",
			),
			array(
				'code' => "TK",
			),
			array(
				'code' => "TO",
			),
			array(
				'code' => "TT",
			),
			array(
				'code' => "TN",
			),
			array(
				'code' => "TR",
			),
			array(
				'code' => "TM",
			),
			array(
				'code' => "TC",
			),
			array(
				'code' => "TV",
			),
			array(
				'code' => "UG",
			),
			array(
				'code' => "UA",
			),
			array(
				'code' => "AE",
			),
			array(
				'code' => "GB",
			),
			array(
				'code' => "US",
			),
			array(
				'code' => "UM",
			),
			array(
				'code' => "UY",
			),
			array(
				'code' => "UZ",
			),
			array(
				'code' => "VU",
			),
			array(
				'code' => "VA",
			),
			array(
				'code' => "VE",
			),
			array(
				'code' => "VN",
			),
			array(
				'code' => "VG",
			),
			array(
				'code' => "VI",
			),
			array(
				'code' => "WF",
			),
			array(
				'code' => "EH",
			),
			array(
				'code' => "YE",
			),
			array(
				'code' => "ZR",
			),
			array(
				'code' => "ZM",
			),
			array(
				'code' => "ZW",
			),
		); 
		$this->db->insert_batch('countries', $data);
	}

	public function down() 
	{
		$this->dbforge->drop_table('countries', true);
	}
}