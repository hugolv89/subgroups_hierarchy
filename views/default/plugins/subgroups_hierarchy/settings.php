<label><?php echo elgg_echo('subgroupshierarchy:settings:avatar:enable'); ?></label> 
<select name="params[enable_hierarchyavatar]">
	<option value="true"  <?php if  ($vars['entity']->enable_hierarchyavatar == 'true') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes')?></option>
	<option value="false" <?php if (($vars['entity']->enable_hierarchyavatar == 'false') || (!isset($vars['entity']->enable_hierarchyavatar))) echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no')?></option>
</select>
<br/><br/>
