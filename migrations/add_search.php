<?php

/**
 *
 * Live topic update - add search option
 *
 * @copyright (c) 2016 Ger Bruinsma
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\livetopicupdate\migrations;

use phpbb\db\migration\container_aware_migration;

class add_search extends container_aware_migration
{

	static public function depends_on()
	{
		return array('\ger\livetopicupdate\migrations\install_ltu');
	}

    public function effectively_installed()
    {
       return isset($this->config['ger_livetopicupdate_search']);
    }


	public function update_data()
	{
		return array(
			array('config.add', array('ger_livetopicupdate_search', 0)),
		);
	}
}