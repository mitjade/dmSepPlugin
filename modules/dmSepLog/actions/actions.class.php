<?php

class dmSepLogActions extends dmAdminBaseActions
{
	public function executeRefresh(dmWebRequest $request)
  {
    $data = array();
    
		$q = Doctrine_Query::create()
	    ->from('dmSepKeyword k')
			->leftJoin('k.Translation t')
	    ->andWhere('t.is_active = 1');
		$keywords = $q->execute();
		
		$html = '<tr><td>'.$this->getContext()->getI18N()->__('Serach engine').':</td><td align="right">'.$this->getContext()->getI18N()->__('Position').':</td><td align="right">'.$this->getContext()->getI18N()->__('Change').':</td><td align="right">'.$this->getContext()->getI18N()->__('Date').':</td></tr>';
		
		if(count($keywords) > 0)
		{
			$ii = 0;
			foreach($keywords as $keyword)
			{
				$style = $ii++%2 == 0 ? 'odd' : 'eaven';
				$html .= '<tr class="'.$style.'"><th colspan="4">'.$this->getContext()->getI18N()->__('Keyword').': '.$keyword->getName().'</th></tr>';
				foreach(sfConfig::get('app_dmSepPlugin_engine') as $key => $name)
				{
					$style = $ii++%2 == 0 ? 'odd' : 'eaven';
					$html .= '<tr class="'.$style.'">';
					$q = Doctrine_Query::create()
				    ->from('dmSepResult r')
						->where('r.search_engine = ?', $name)
				    ->andWhere('r.keyword_id = ?', $keyword->getId())
						->orderBy('r.created_at DESC');
					$results = $q->execute();

					if(count($results) > 0)
					{
						$result = $results[0];

						if($result->getPositionCurrent() != -1) $position_current = $result->getPositionCurrent(); else $position_current = '-';

						if($result->getPositionChange() > 0)
						{
							$position_change = '<span class="sep16 sep16_position_up"></span>+';
						}
						else if($result->getPositionChange() < 0)
						{
							$position_change = '<span class="sep16 sep16_position_down"></span>';
						}
						else 
						{
							$position_change = '<span class="sep16 sep16_position_no_change"></span>';
						}
						$position_change .= $result->getPositionChange();

			      $date = explode(' ', $result->getCreatedAt());
			      $date = explode('-', $date[0]);
			      $update_date  = date("d/m/Y", mktime(0, 0, 0, $date[1], $date[2], $date[0]));

						$html .= '<td>'.dm::getHelper()->media('/dmSepPlugin/images/engine/'.$name.'.png').' '.$name.'</td><td align="right">'.$position_current.'</td><td align="right">'.$position_change.'</td><td align="right">'.$update_date.'</td>';
					}
					else
					{
						$html .= '<tr class="'.$style.'"><td>'.dm::getHelper()->media('/dmSepPlugin/images/engine/'.$name.'.png').' '.$name.'</td><td colspan="3">'.$this->getContext()->getI18N()->__('no results').'</td></tr>';
					}

					$html .= '</tr>';
				}
			}
		}
		else
		{
			$html .= '<tr class="odd"><td colspan="4">'.$this->getContext()->getI18N()->__('no results').'</td></tr>';
		}

		$data['sep'] = array('html' => $html);

    return $this->renderJson($data);
  }
}