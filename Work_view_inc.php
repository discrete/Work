<?php
	collapse_open( 'Work' );
?>
<br/>
<a name="works"/></a>
<table class="width100" cellspacing="1">

<tr>
	<td class="form-title">
<?php 
	collapse_icon( 'Work' );
	echo 'Related Works';
?>

	</td>
</tr>
<tr>
	<td class="category" width="25%">
		Research
	</td>
	
	<td>
		Median research man-day: 0.5
	</td>
	<td>
		actual research man-day on this issue: 1.0	
	</td>
	<td>
		done by Joohong Kim	
	</td>
</tr>
<tr>
	<td class="category" width="25%">
		Total Work
	</td>
	
	<td>
		Median man-day: 1
	</td>
	<td>
		actual man-day on this issue: 1.0	
	</td>
	<td>
		involve 1 resource	
	</td>
</tr>

		<!-- ?php Source_View_Changesets( $t_changesets ); ?-->
</table>
<?php
			collapse_closed( 'Work' );
?>
<br/>
<table class="width100" cellspacing="1">

<tr>
	<td class="form-title">
<?php
			collapse_icon( 'Work' );
			echo 'Related Works';
?>
	</td>
</tr>

</table>
<?php
	collapse_end('Work');
?>