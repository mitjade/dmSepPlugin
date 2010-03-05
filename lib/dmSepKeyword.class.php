<?php

class dmSepKeywords extends dmConfigurable
{
  protected $serviceContainer;

  public function __construct(dmBaseServiceContainer $serviceContainer)
  {
    $this->serviceContainer = $serviceContainer;
  }
	
	public function updateAll()
	{
		$q = Doctrine_Query::create()
	    ->from('DmSepKeyword k')
			->leftJoin('k.Translation t')
	    ->andWhere('t.is_active = 1');
		$keywords = $q->execute();
		
		foreach($keywords as $keyword)
		{
			$this->updateKeyword($keyword);
		}
		
		return 1;
	}
	public function updateKeyword($keyword)
	{
		$settings = sfConfig::get('app_dmSepPlugin_settings');
		
		foreach(sfConfig::get('app_dmSepPlugin_engine') as $key => $name)
		{
			$position = $this->getPosition( $settings[$key]['search_engine'], 
																			$settings[$key]['reg_exp'],
																 			sfConfig::get('app_dmSepPlugin_domain'),
																			$keyword->getName(),
																			$settings[$key]['first_count'],
																			$settings[$key]['increment'],
																			$settings[$key]['prefix'],
																			$settings[$key]['suffix'],
																			$settings[$key]['num_or_pages']
																		);

			$this->saveResult($position, $keyword, $name);
		}
		
		return 1;
	}
	protected function getPosition($search_engine, $reg_exp, $url, $keywords, $first_count, $increment, $prefix, $suffix, $num_or_pages)
	{
		$url = str_replace('http://www.', '', $url);
		if($num_or_pages < 1) $num_or_pages = 1;
		if(isset($url) && isset($keywords)) 
		{
			$make_url = sprintf($search_engine, 
	                    urlencode($keywords));
			
			$position = 0; // counting start from here
			$found = false; // set this flag to true when position found
			$index_count = '';
			
			for($page = $index_count; $page < $num_or_pages; $page += $increment) 
			{
				if($found == true) // break the loop when position found
				break;
				
				$first_count_tmp = '';
				$prefix_tmp = $prefix;
				$suffix_tmp = $suffix;
				if($page == $index_count)
				{
					$prefix_tmp = '';
					$suffix_tmp = '';
					$first_count_tmp = $first_count;	
				}
				//var_dump($make_url . $prefix_tmp . $page  . $first_count_tmp . $suffix_tmp);
				$readPage = fopen($make_url . $prefix_tmp . $page  . $first_count_tmp . $suffix_tmp,'r');
				
				$contains = '';
				if ($readPage) 
				{
					while (!feof($readPage)) 
					{
						$buffer = fgets($readPage, 4096);
						$contains .= $buffer;
					}
					fclose($readPage);
				}
				
				$results = array();
				preg_match_all($reg_exp, $contains, $results);
				
				foreach ($results[1] as $link) 
				{
					//var_dump($link);
					$link = preg_replace('(^http://|/$)', '', $link);
					$position = $position + 1;
					if (strlen(stristr($link,$url)) > 0) 
					{
						$found = true;
						break;
					}
				}
			}
			if($found == true)
				return $position;
			else
				return -1;
		}
		return -1;
	}
	
	protected function saveResult($position, $keyword, $search_engine)
	{
		$q = Doctrine_Query::create()
	    ->from('DmSepResult r')
			->where('r.search_engine = ?', $search_engine)
	    ->andWhere('r.keyword_id = ?', $keyword->getId())
			->orderBy('r.created_at DESC');
		$results = $q->execute();
		
		if(count($results) > 0)
		{
			$result = $results[0];
			if($result->getPositionCurrent() != $position)
			{
				$sep_result = new dmSepResult();
				$sep_result->setKeywordId($keyword->getId());
				$sep_result->setSearchEngine($search_engine);
				$sep_result->setPositionCurrent($position);
				if($position != -1 && $result->getPositionCurrent() != -1)
				{
					$sep_result->setPositionChange($result->getPositionCurrent()-$position);
				}
				else $sep_result->setPositionChange(0);

				$sep_result->save();
			}
		}
		else
		{
			$sep_result = new dmSepResult();
			$sep_result->setKeywordId($keyword->getId());
			$sep_result->setSearchEngine($search_engine);
			$sep_result->setPositionCurrent($position);
			$sep_result->setPositionChange(0);

			$sep_result->save();
		}
		
		return 1;
	}
}