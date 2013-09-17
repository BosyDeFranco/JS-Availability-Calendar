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

class listit2fd_Availability extends ListIt2FielddefBase
{
	public function __construct(&$db_info)
	{
		parent::__construct($db_info);
		$this->SetFriendlyType($this->ModLang('fielddef_'.$this->GetType()));
	}

	public function GetHeaderHTML()
	{	
		$tmpl = <<<EOT
<link type="text/css" rel="stylesheet" href="{$this->GetURLPath()}/listit2fd-availability.css" />
<script type="text/javascript" src="{$this->GetURLPath()}/listit2fd-availability.js"></script>
EOT;
		return $tmpl;
	}

	public function RenderInput($id, $returnid)
	{
		$smarty = cmsms()->GetSmarty();
		$lang = CmsNlsOperations::get_language_info(CmsNlsOperations::get_current_language());
		$smarty->assign('cms_lang', $lang->isocode());
		$smarty->assign('events', $this->GetValue(self::TYPE_ARRAY));
		return parent::RenderInput($id, $returnid);
	}

	public function RenderForAdminListing($id, $returnid)
	{
		return $this->ModLang('occupancies', count($this->GetValue(self::TYPE_ARRAY)));
	}
}
?>