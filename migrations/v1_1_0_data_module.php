<?php
/**
 * 
 * User Online Time
 * 
 * @copyright (c) 2014 Wolfsblvt ( www.pinkes-forum.de )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Clemens Husung (Wolfsblvt)
 */

namespace wolfsblvt\onlinetime\migrations;

class v1_1_0_data_module extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array('\wolfsblvt\onlinetime\migrations\v1_0_0_schema');
	}

	public function update_data()
	{
		return array(
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'PBTG_TITLE_ACP'
			)),
			array('module.add', array(
				'acp',
				'PBTG_TITLE_ACP',
				array(
					'module_basename'	=> '\wolfsblvt\primebantogroup\acp\primebantogroup_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'PBTG_TITLE_ACP'
			)),
			array('module.remove', array(
				'acp',
				'PBTG_TITLE_ACP',
				array(
					'module_basename'	=> '\wolfsblvt\primebantogroup\acp\primebantogroup_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
