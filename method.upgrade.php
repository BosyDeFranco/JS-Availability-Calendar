<?php
#-------------------------------------------------------------------------
#
# Jonathan Schmid, <hi@jonathanschmid.de>
# Web: www.jonathanschmid.de
#
#-------------------------------------------------------------------------
#
# JSAvailability is a CMS Made Simple Dummy module that adds field definitions to ListIt2 module.
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------

if (!is_object(cmsms())) exit;

if( version_compare($oldversion, '0.10.2') < 0 )
{
	foreach(cmsms()->GetModuleInstance('ListIt2')->ListModules() as $li)
	{
		$alias = cmsms()->GetModuleInstance($li->module_name)->_GetModuleAlias();
		// remove duplicate events
		$sql = 'SELECT b.item_id, b.fielddef_id, b.value
			FROM  '.cms_db_prefix().'module_'.$alias.'_fielddef a, '.cms_db_prefix().'module_'.$alias.'_fieldval b
			WHERE a.fielddef_id = b.fielddef_id AND a.type = \'Availability\'';
		$result = $db->GetArray($sql);
		$sql = 'UPDATE '.cms_db_prefix().'module_'.$alias.'_fieldval SET value = ? WHERE item_id = ? AND fielddef_id = ?';
		if(!is_array($result))
		{
			continue;
		}
		foreach($result as $dataset)
		{
			$events = json_decode($dataset['value']);
			usort($events, function ($a, $b) {
				if (strtotime($a->date) == strtotime($b->date))
				{
					return 0;
				}
				return (strtotime($a->date) < strtotime($b->date)) ? -1 : 1;
			});
			foreach($events as $n => $event)
			{
				if(!isset($last))
				{
					$last = $n;
					continue;
				}
				if($events[$n]->date == $events[$last]->date)
				{
					unset($events[$last]);
					$events[$n]->isStart = true;
					$events[$n]->isEnd = true;
				}
				$last = $n;
			}
			$db->Execute($sql, array(json_encode(array_values($events)), $dataset['item_id'], $dataset['fielddef_id']));
		}
		// split data into one dataset per event
		$sql = 'SELECT b.item_id, b.fielddef_id, b.value
			FROM  '.cms_db_prefix().'module_'.$alias.'_fielddef a, '.cms_db_prefix().'module_'.$alias.'_fieldval b
			WHERE a.fielddef_id = b.fielddef_id AND a.type = \'Availability\'';
		$result = $db->GetArray($sql);
		$db->Execute('DELETE FROM '.cms_db_prefix().'module_'.$alias.'_fieldval WHERE item_id = ? AND fielddef_id = ?',
			array($result[0]['item_id'], $result[0]['fielddef_id']));
		$sql = 'INSERT INTO '.cms_db_prefix().'module_'.$alias.'_fieldval (item_id, fielddef_id, value_index, value) VALUES (?, ?, ?, ?)';
		foreach($result as $dataset)
		{
			$events = array_values(json_decode($dataset['value']));
			foreach($events as $n => $event)
			{
				$db->Execute($sql, array($dataset['item_id'], $dataset['fielddef_id'], $n, json_encode($event)));
			}
		}
	}
} // end of 0.10.1 -> 0.10.2 upgrade*/