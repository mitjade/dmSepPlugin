<?php

class dmSepUpdateTask extends dmContextTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', 'admin', sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'admin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod')
    ));
    
    $this->namespace = 'dm';
    $this->name = 'sep-update';
    $this->briefDescription = 'Update search engine keyword position index';

    $this->detailedDescription = $this->briefDescription;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $this->withDatabase();
    
    $this->log('Search engine keyword position index update');

		$this->log('For domain: '.sfConfig::get('app_dmSepPlugin_domain'));
    
    $this->get('service_container')->setService('logger', new sfConsoleLogger($this->dispatcher));
    
		$result = $this->get('sep_keywords')->updateAll();
		
		if($result = 1)
		{
			$this->log('Update successful');
		}
		else
		{
			$this->log('Update failed');
		}
  }
}