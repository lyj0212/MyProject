<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 * @todo 2013-05-21, constructed
 * @keyword description
 *
 * @author Shin dong-uk <uks@plani.co.kr>
 * @copyright Copyright (c) 2009, Plani
**/

class Migration_Board extends MY_Migration {

	public function up()
	{
		$this->execute_sql(dirname(__FILE__).'/schema/board.sql');
	}

	public function down()
	{
		$this->dbforge->drop_table('bbs');
		$this->dbforge->drop_table('bbs_category');
		$this->dbforge->drop_table('bbs_setup');
	}

}
