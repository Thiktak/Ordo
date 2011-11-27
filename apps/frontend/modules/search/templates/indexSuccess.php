<article class="page-search page-search-index">
	<header>
		<h1>Recherche</h1>
	</header>

	<form action="<?php echo url_for('@search'); ?>" method="GET">
		<p style="text-align: center;">
			<input type="text"   name="search" value="<?php echo $sf_request->getParameter('search'); ?>" />
			<input type="submit" value="?" />
		</p>
	</form>

	<?php foreach( $sf_data->getRaw('results') as $sKey => $aDatas ): ?>
	<article style="margin: 1%; width: 48%; float: left; background-color: #fafafa;">
		<header>
			<h1><?php echo ucfirst($sKey); ?> (<?php echo count($aDatas); ?>)</h1>
		</header>
		<div style="max-height: 300px; overflow: auto;">
			<table>
				<?php foreach( $aDatas as $aValues ): ?>
				<tr>
					<td style="width: 30%; text-align: center; font-weight: bold;">
						<?php echo link_to($aValues['name'], '@' . $sKey . '?action=show&id=' . $aValues['id'], array('class' => 'search')); ?>
					</td>
					<td class="search">
						<?php echo $aValues['details']; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</article>
	<?php endforeach; ?>
</article>

<script type="text/javascript">
	$(function() {
		function highlight($el, word) {
			
			var text = $el.html();
			text = text.replace(new RegExp('(' + word + ')','gi'), '<span class="hl" style="background-color: rgba(255, 215, 0, .2);">$1</span>');
			$el.html(text);
		}
		
		$.fn.highlight = function(word) {
			return this.each(function() {
				highlight($(this), word);
			});
		};
		
		word = ('<?php echo $sf_request->getParameter('search'); ?>').split(' ');
		for( _w in word )
			$('.search').highlight(word[_w].replace('\.', '\\.'));
	}).jQuery();
</script>
