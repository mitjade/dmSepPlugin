<?php

require_once dirname(__FILE__).'/../lib/dmSepResultAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/dmSepResultAdminGeneratorHelper.class.php';

/**
 * dmSepResultAdmin actions.
 *
 * @package    www
 * @subpackage dmSepResultAdmin
 * @author     Mitja Debeljak
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class dmSepResultAdminActions extends autoDmSepResultAdminActions
{
	public function executeUpdateAll(sfWebRequest $request)
  {
		$result = $this->getService('sep_keywords')->updateAll();
		
		return $this->redirect('@dm_sep_result');
	}
	
	public function executeUpdateKeyword(sfWebRequest $request)
  {
		$keyword = Doctrine::getTable('dmSepKeyword')->find($request->getParameter('id'));
		
		$result = $this->getService('sep_keywords')->updateKeyword($keyword);
		
		return $this->redirect('@dm_sep_result');
	}
}
