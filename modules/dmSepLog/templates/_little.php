<?php

use_stylesheet('admin.dataTable');
use_stylesheet('admin.log');
use_stylesheet('dmSepPlugin.log');
use_javascript('dmSepPlugin.log');

echo _tag('div.dm_box.log.'.$logKey.'_log',
  _tag('div.title',
    _link('@dm_sep_result')->set('.s16block.s16_arrow_up_right')->textTitle(__('Expanded view')).
    _tag('h2', __('Search Engine Position'))
  ).
  _tag('div.dm_box_inner.dm_data', '<table><tbody></tbody></table>')
);