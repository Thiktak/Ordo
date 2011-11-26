<?php

/*
 * TODO
 *  - A améliorer -> graphiques
 */

foreach( $stats as $type => $datas1 )
	foreach( $datas1 as $year => $datas2 )
	{
		$lines[$year][$type] = $datas2;
	}

$_years = array(); foreach( $years as $y ) $_years[$y] = $y; ?>
<article>
	<header>
		<h1>Statistiques</h1>
	</header>


	<table style="text-align: center;">
		<thead>
			<tr>
				<th>\</th>
				<?php foreach( $stats as $type => $values ): ?>	
				<th><?php echo $type; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<?php foreach( $lines as $year => $datas1 ): ?>
		<tr>
			<th><?php echo $year; ?></th>
			
			<?php foreach( $stats as $type => $values ): ?>
				<?php if( !isset($datas1[$type]) ): ?>
			<td>
				/
			</td>
				<?php else: ?>
			<td>
				<?php echo isset($datas1[$type][1]) ? $datas1[$type][1] : 0; ?> €
				<span style="font-size: .6em; display: block;">
					<?php echo isset($datas1[$type][0]) ? $datas1[$type][0] : 0; ?> projets
					=
					<?php echo (isset($datas1[$type][0], $datas1[$type][1]) && $datas1[$type][0] != 0) ? $datas1[$type][1]/$datas1[$type][0] : 0; ?> €/p
				</span>
			</td>
				<?php endif; ?>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</table>
</article>
