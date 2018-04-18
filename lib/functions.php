<?php
/**
 * Subgroups Hierarchy Functions
 */

namespace HLV\SubGroupsHierarchy;

/**
 * Get all group hierarchy list (HTML)
 */
function group_hierarchy($group, $actual = null){

	if($group){

		if(!$actual){
			$actual = $group;
		}

		if( $parentgroup = \AU\SubGroups\get_parent_group($group) ){

			return group_hierarchy($parentgroup,$actual);
		}

		if(has_access_to_entity ($group)){

			$maingroup = html_group_data($group,$actual);
		}

		$subgroups = subgroups_hierarchy($group,$actual);

		if($actual == null && !$subgroups){

			return false;
		}

		return '<ul id="maingroup_hierarchy"><li>'.$maingroup.$subgroups.'</li></ul>';
	}

	return false;
}

/**
 * Get only subgroup hierarchy list (HTML)
 */
function subgroups_hierarchy($group,$actual){

	$data = '';

	if($group && $group->subgroups_enable == 'yes'){

		if( $subgroups = \AU\SubGroups\get_subgroups($group, 0) ){

			$has_data = false;
			$has_access = false;
			$has_subgroup = false;

			foreach ($subgroups as $subgroup) {
				
				$temp = '<li>';

				if( has_access_to_entity ($subgroup) ){

					$temp .= html_group_data($subgroup,$actual);
					$has_access = true;
				}

				if( $is_group = subgroups_hierarchy($subgroup,$actual) ){

					$temp .= $is_group;
					$has_subgroup = true;
				}

				$temp .= '</li>';

				if(has_access_to_entity ($subgroup) || $is_group){

					$has_data = true;
					$data .= $temp;
				}
			}

			if($has_data){

				if(!$has_access && $has_subgroup){

					return $data;
				}

				return '<ul>'.$data.'</ul>';
			}
		}
	}

	return false;
}

/**
 * Get html group data
 */
function html_group_data($group,$actual){

	$plugin_settings = elgg_get_plugin_from_id(PLUGIN_ID);

	if($actual == $group){

		$id = 'id="actualgroup"'; 
	}

	if($plugin_settings->enable_hierarchyavatar == 'true'){
		
		$avatar = '<img src="'.$group->getIconURL("tiny").'" alt="" />';
	}
	
	$url = '<a '.$id.' href="'.$group->getURL().'">'.$group->name.'</a>';

	return '<div class="hierarchydata">'.$avatar.$url.'</div>';
}

?>
