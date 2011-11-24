<header>
	<h1>Recherche</h1>
</header>

<form action="<?php echo url_for('@search'); ?>" method="GET">
	<p style="text-align: center;">
		<input type="text"   name="search" value="	"<?php echo $sf_request->getParameter('search'); ?>" />
		<input type="submit" value="?" />
	</p>
</form>

<?php foreach( $results as $sKey => $aDatas ): ?>
<article>
	<header>
		<h2><?php echo ucfirst($sKey); ?> (<?php echo count($aDatas); ?>)</h2>
	</header>
	<div style="max-height: 300px; overflow: auto;">
		<table>
			<?php foreach( $aDatas as $aValues ): ?>
			<tr>
				<td style="width: 100px; text-align: center;"><?php echo $aValues->getId(); ?></td>
				<td style="width: 20%; font-weight: bold;"><?php echo $aValues; ?></td>
				<td><?php echo $aValues->getDetails(); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</article>
<?php endforeach; ?>
