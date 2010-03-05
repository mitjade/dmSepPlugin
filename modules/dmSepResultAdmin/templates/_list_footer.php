<?php

$shellUser = dmConfig::canSystemCall() ? exec('whoami') : 'www-data';
$phpCli = sfToolkit::getPhpCli();
$rootDir = sfConfig::get('sf_root_dir');
//$domainName = $sf_request->getHost();

if (isset($phpCli))
{
  echo _open('div.dm_box.big.search_engine');

  echo _tag('h1.title', __('Set up a cron to update the search engine position'));
  
  echo _open('div.dm_box_inner.documentation');
  
    echo _tag('p', __('Most UNIX and GNU/Linux systems allows for task planning through a mechanism known as cron. The cron checks a configuration file (a crontab) for commands to run at a certain time.'));
  
    echo _tag('p.mt10.mb10', __('Open %1% and add the line:', array('%1%' => '/etc/crontab')));
    
    echo _tag('code', _tag('pre', sprintf('@weekly %s %s %s/symfony dm:sep-update',
      $shellUser,
      $phpCli,
      $rootDir
      //$domainName
    )));
    
		echo _tag('p.mt10', __('!!! ATTENTION !!! Some search engines like google do not permit parsing their result pages and may block your IP. Use aol instead, it uses google search engine. Be careful not to run too many requests in one day!'));

		echo _tag('p.mt10', __('If you are running this task on multiple sites, try to set them up, so they don\'t all run on the same day of the week.'));
		
    echo _tag('p.mt10', __('For more information on the crontab configuration file format, type man 5 crontab in a terminal.'));

  echo _close('div');
  
  echo _close('div');
}

?>