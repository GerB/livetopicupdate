<?php

/**
 *
 * Live topic update
 *
 * @copyright (c) 2016 Ger Bruinsma
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\livetopicupdate\migrations;

use phpbb\db\migration\container_aware_migration;

class install_ltu extends container_aware_migration
{

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

    public function effectively_installed()
    {
       return isset($this->config['ger_livetopicupdate_interval']);
    }


	public function update_data()
	{
		return array(
			array('config.add', array('ger_livetopicupdate_interval', 30)),
		);
	}


}
