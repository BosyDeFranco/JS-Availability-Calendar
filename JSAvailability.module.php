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

class JSAvailability extends CMSModule {

	public function GetName()
	{
		return get_class($this);
	}

	public function GetFriendlyName()
	{
		return $this->Lang('module_friendlyname');
	}

	public function GetAdminDescription()
	{
		return $this->Lang('module_description');
	}

	public function GetDependencies()
	{
		return array('ListIt2' => '1.4');
	}

	public function GetVersion()
	{
		return '0.10.1';
	}

	public function MinimumCMSVersion()
	{
		return '1.11';
	}

	public function GetAuthor()
	{
		return 'Jonathan Schmid';
	}

	public function GetAuthorEmail()
	{
		return 'hi@jonathanschmid.de';
	}

	public function GetHelp()
	{
		$smarty = cmsms()->GetSmarty();
		$smarty->assign('mod', $this);

		return $this->ProcessTemplate('help.tpl');
	}

	public function GetChangeLog()
	{
		return @file_get_contents(dirname(__FILE__).'/changelog.html');
	}

	public function VisibleToAdminUser()
	{
		return false;
	}

	public function IsPluginModule()
	{
		return true;
	}

	public function LazyLoadFrontend() 
	{
		return true;
	}

	public function InitializeAdmin() {
		$this->CreateParameter('field', '', $this->Lang('help_param_field'));
	}

	public function InitializeFrontend()
	{
		$this->RegisterModulePlugin();
		$this->RestrictUnknownParams();

		$this->SetParameterType('field', CLEAN_NONE);
	}
}
?>