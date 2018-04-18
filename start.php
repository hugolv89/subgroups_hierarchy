<?php
/**
 * Subgroups Hierarchy Plugin
 */

namespace HLV\SubGroupsHierarchy;

const PLUGIN_ID = 'subgroups_hierarchy';

require_once(__DIR__.'/lib/functions.php');

//register the plugin hook handler
elgg_register_event_handler('init', 'system', __NAMESPACE__.'\\init');

/**
 * plugin init function
 */
function init() {

	// add css
	elgg_extend_view('css/elgg', 'css/subgroups_hierarchy/style.css');

	add_group_tool_option('subgroups_hierarchy', elgg_echo('subgroupshierarchy:group:tool:text'));

	elgg_register_plugin_hook_handler('view', 'groups/profile/summary',  __NAMESPACE__.'\\alter_group_summary');

}

function alter_group_summary($hook, $type, $returnvalue, $params) {
    // we only want to alter when viewtype is "default"
    if ($params['viewtype'] !== 'default') {
        return $returnvalue;
    }
	
	//echo "<pre>"; print_r($params['vars']); echo "</pre>";
	$group = $params['vars']['entity'];

	if($group->subgroups_hierarchy_enable != 'no'){

		// save access status
		$access_setting = elgg_get_ignore_access();	
		// Ignore access
		elgg_set_ignore_access(true);

		if($body = \HLV\SubGroupsHierarchy\group_hierarchy($group)){
	
			$hierarchy_view = elgg_view('groups/profile/module', [
				'content' => $body,
				'title' => elgg_echo('subgroupshierarchy:title:hierarchy'),
			]);
			
			// <li></li> temporal fix;
			if(strpos($hierarchy_view, "<li>") === 0){
				
				$hierarchy_view = substr($hierarchy_view, 4, -5);
			}	

		}

		// Restore access
		elgg_set_ignore_access($access_setting);

		return $returnvalue.$hierarchy_view;
	}
	
	// returning nothing means "don't alter the returnvalue"
}