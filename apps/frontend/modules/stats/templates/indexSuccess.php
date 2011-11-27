<?php

function select_tag($request, $name, $optValues, $options = null)
{
	$output  = '<select name="' . $name . '">';
	
	if( is_array($optValues) || is_object($optValues) )
		foreach( $optValues as $value => $text )
			$output .= '<option value="' . urlencode($value) . '"' . ($request->getParameter($name) == $value ? ' selected="selected"' : null) . '>' . $text . '</option>';
	
	$output .= '</select>';
	return $output;
}

use_helper('Tag'); ?>
<article class="page-stats page-stats-index">
	<script src="/js/highcharts/highcharts.js" type="text/javascript"></script>
	<style>
		.page-stats-index > aside { float: right; width: 200px; }
		.page-stats-index > aside li { margin-left: 30px; }
	</style>
	
	<script type="text/javascript">
	var chart;
	$(document).ready(function() {
		chart = new Highcharts.Chart({
		  chart: {
			 renderTo: 'container',
			 //defaultSeriesType: 'scatter',
			 plotBackgroundColor: null,
			 plotBorderWidth: null,
			 plotShadow: true,
			 reflow: false,
		  },
		  title: {
			 text: <?php echo json_encode($sf_data->getRaw('currentStat')->title); ?>
		  },
		  tooltip: {
			 formatter: function() {
				return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
			 }
		  },
		  plotOptions: {
			column: {
				 <?php echo $sf_request->getParameter('stack') ? 'stacking: "normal",' : null; ?>
			},
			line: {
				 <?php echo $sf_request->getParameter('stack') ? 'stacking: "normal",' : null; ?>
			},
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
				   enabled: false
				},
				showInLegend: true
			}
		  },
		xAxis: {
			//type: 'datetime',
			/*
			dateTimeLabelFormats: { // don't display the dummy year
				month: '%e. %b',
				year: '%b'
			}
			//*/
           categories: <?php echo json_encode($sf_data->getRaw('currentStat')->xAxis['categories']); ?>,
		},
		yAxis: {
			title: {
				text: ''
			},
			stackLabels: {
				enabled: true,
				style: {
					fontWeight: 'bold',
					color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
				}
			}
		  },
		  series: <?php echo json_encode($sf_data->getRaw('currentStat')->graphs); ?>
		});
	});
	</script>


	<header>
		<h1>Statistiques</h1>
	</header>
	
	<aside>
		<ul>
			<?php foreach( $stats as $id => $stat ): ?>
			<li><?php echo link_to($stat, '@stats?stat=' . $id); ?></li>
			<?php endforeach; ?>
		</ul>
		
		<hr />
		
		<form action="" method="GET" style="text-align: center;">
			<?php foreach( $sf_data->getRaw('currentStat')->params as $name => $param ): ?>
			<p>
				<?php echo call_user_func_array(array_shift($param) . '_tag', array_merge(array($sf_request), $param)); ?>
			</p>
			<?php endforeach; ?>
			<br />
			
			<p>
				<input type="checkbox" name="stack" value="1" <?php echo $sf_request->getParameter('stack') ? ' checked="checked"' : null; ?>/> Stack
			</p>
			
			<br />
			
			<p>
				<input type="submit" value="Filtrer" />
			</p>
		</form>
	</aside>
	
	<div id="container" style="margin-right: 200px;"></div>
	
<!--

@TODO : revoir le code de génération de tableau

<?php
$lines = array();
foreach( $currentStat->getX() as $type => $datas1 )
{
	foreach( $datas1 as $year => $datas2 )
	{
		$lines[$year][$type] = $datas2;
	}
}
?>

	<div style="margin-right: 200px;">
		<table id="datatable" style="text-align: center;">
			<caption>Titre du graphique</caption>
			<thead>
				<tr>
					<th>/</th>
					<?php foreach( $currentStat->getX() as $type => $values ): ?>	
					<th scope="col">@<?php echo $type; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $lines as $year => $datas1 ): ?>
				<tr>
					<th scope="row"><?php echo $year; ?></th>
					<?php foreach( $currentStat->getX() as $y0 => $values ): 
							if( !isset($datas1[$y0]) ): ?>
					<td>0</td>
						<?php else: ?>
					<td><?php echo $currentStat->getX($y0, $year); ?></td>
						<?php endif;
					endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
-->
</article>
