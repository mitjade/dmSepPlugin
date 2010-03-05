<?php
if($dm_sep_result->getPositionChange() > 0)
{
	echo '<span class="sep16 sep16_position_up"></span>+';
}
else if($dm_sep_result->getPositionChange() < 0)
{
	echo '<span class="sep16 sep16_position_down"></span>';
}
else 
{
	echo '<span class="sep16 sep16_position_no_change"></span>';
}
echo $dm_sep_result->getPositionChange();
?>