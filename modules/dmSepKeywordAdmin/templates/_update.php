<?php

echo _link('dmSepResultAdmin/updateKeyword?id='.$dm_sep_keyword->getId())
		 ->set('.sf_admin_action_new.sf_admin_action.s16.s16_clear')
		 ->style('padding-bottom:2px')
		 ->text(__('Update'))
		 ->title(__('Update keyword'));

?>