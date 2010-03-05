<?php
 
class dmSepPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('dm.admin_homepage.filter_windows', array($this, 'listenToFilterWindowsEvent'));
  }

	public function listenToFilterWindowsEvent(sfEvent $event, array $windows)  
	{
		$windows[1]['sepWindow'] = array($this, 'renderSepWindow');
		
	  return $windows;  
	}  

	public function renderSepWindow(dmHelper $helper)  
	{
		return $helper->renderComponent('dmSepLog', 'little');
	}
}