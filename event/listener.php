<?php
/**
 * 
 * User Online Time
 * 
 * @copyright (c) 2014 Wolfsblvt ( www.pinkes-forum.de )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Clemens Husung (Wolfsblvt)
 */

namespace wolfsblvt\onlinetime\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \wolfsblvt\onlinetime\core\onlinetime */
	protected $onlinetime;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	 * Constructor of event listener
	 *
	 * @param \wolfsblvt\onlinetime\core\onlinetime	$onlinetime		Online Time
	 * @param \phpbb\db\driver\driver_interface		$db				Database
	 * @param \phpbb\path_helper					$path_helper	phpBB path helper
	 * @param \phpbb\template\template				$template		Template object
	 * @param \phpbb\user							$user			User object
	 */
	public function __construct(\wolfsblvt\onlinetime\core\onlinetime $onlinetime, \phpbb\db\driver\driver_interface $db, \phpbb\path_helper $path_helper, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->onlinetime = $onlinetime;
		$this->db = $db;
		$this->path_helper = $path_helper;
		$this->template = $template;
		$this->user = $user;

		$this->ext_root_path = 'ext/wolfsblvt/onlinetime';
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header'					=> 'page_header',
			'core.memberlist_view_profile'		=> 'add_onlinetime_to_memberlist_view_profile',
			'core.permissions'					=> 'add_permissions',
		);
	}

	/**
	 * Adds functionality to page_header
	 *
	 * @param object $event The event object
	 * @return void
	 */
	public function page_header($event)
	{
		// Assign template vars first
		$this->assign_template_vars();

		// Updates the user online time
		$this->onlinetime->update_user_online_time();
	}

	/**
	 * Add custom permissions language variables
	 *
	 * @param object $event The event object
	 * @return void
	 */
	public function add_permissions($event)
	{
		return;
		$permissions = $event['permissions'];
		$permissions['u_similar_topics'] = array('lang' => 'ACL_U_SIMILARTOPICS', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	/**
	 * Adds the online time to user profile if it can be displayed
	 * 
	 * @param object $event The event object
	 * @return void
	 */
	public function add_onlinetime_to_memberlist_view_profile($event)
	{
		$member_id = $event['member']['user_id'];
		$is_invisible = ((isset($event['session_viewonline'])) ? $event['session_viewonline'] :	0) ? false : true;

		$this->onlinetime->add_onlinetime_to_memberlist_view_profile($member_id, $is_invisible);
	}

	/**
	 * Assigns the global template vars
	 * 
	 * @return void
	 */
	protected function assign_template_vars()
	{
		$this->template->assign_vars(array(
			'T_EXT_ONLINETIME_PATH'				=> $this->path_helper->get_web_root_path() . $this->ext_root_path,
			'T_EXT_ONLINETIME_THEME_PATH'		=> $this->path_helper->get_web_root_path() . $this->ext_root_path . '/styles/' . $this->user->style['style_path'] . '/theme',
		));
	}
}
