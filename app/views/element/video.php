<?php
$mp4Src = isset($mp4Src)
	? $this->url($mp4Src)
	: null;
$webmSrc = isset($webmSrc)
	? $this->url($webmSrc)
	: null;
?>
<div
	<?= isset($id) ? "id=\"{$id}\"" : null ?>
	class="video <?= isset($class) ? "{$class}" : null ?>">
	<?php $this->start() ?>
		<div class="video-message"><span>
			Sorry, video playback is not supported by your browser.<br>
			<?php if (isset($mp4Src)) { ?>
				&nbsp;<a href="<?= $mp4Src ?>">Download Video (MP4)</a>
			<?php } ?>
			<?php if (isset($webmSrc)) { ?>
				&nbsp;<a href="<?= $webmSrc ?>">Download Video (WebM)</a>
			<?php } ?>
		</span></div>
	<?php $message = $this->end() ?>
	<?php $this->start() ?>
		<?php if (isset($mp4Src)) { ?>
			<object type="application/x-shockwave-flash"
				data="<?= $swf = $this->url('public/flash/player.swf') ?>"
				width="<?= $width ?>"
				height="<?= $height ?>">
				<param name="movie" value="<?= $swf ?>">
				<param name="allowFullScreen" value="true">
				<param name="wmode" value="transparent">
				<param name="flashVars" value="<?= $this->escape(http_build_query([
					'file'			=> (string) $mp4Src,
					'autostart'		=> isset($autoplay) && $autoplay ? 'true' : 'false',
					'repeat'		=> isset($loop) && $loop ? 'always' : 'none',
					'mute'			=> isset($muted) && $muted ? 'true' : 'false',
					'controlbar'	=> !isset($controls) || $controls ? 'over' : 'none',
					'image'			=> isset($poster) ? $poster : '',
					'backcolor'		=> '#000000',
					'frontcolor'	=> '#ffffff',
				])) ?>">
				<?= $message ?>
			</object>
		<?php } else { ?>
			<?= $message ?>
		<?php } ?>
	<?php $flash = $this->end() ?>
	<?php if (isset($webmSrc)) { ?>
		<video
			width="<?= $width ?>"
			height="<?= $height ?>"
			<?= isset($autoplay) && $autoplay ? "autoplay" : null ?>
			<?= isset($preload) ? "preload=\"{$preload}\"" : "preload=\"none\"" ?>
			<?= isset($loop) && $loop ? "loop" : null ?>
			<?= isset($muted) && $muted ? "muted" : null ?>
			<?= !isset($controls) || $controls ? "controls" : null ?>
			<?= isset($poster) ? "poster=\"{$poster}\"" : null ?>>
			<source type="video/webm" src="<?= $webmSrc ?>"></source>
			<?php if (isset($mp4Src)) { ?>
				<source type="video/mp4" src="<?= $mp4Src ?>"></source>
			<?php } ?>
			<?php if (isset($tracks)) { ?>
				<?php foreach ($tracks as $track) { ?>
					<track
						<?= isset($track['default']) && $track['default'] ? "default" : null ?>
						<?= isset($track['kind']) ? "kind=\"{$track['kind']}\"" : null ?>
						src="<?= $track['src'] ?>"
						<?= isset($track['srclang']) ? "srclang=\"{$track['srclang']}\"" : null ?>
						<?= isset($track['label']) ? "label=\"{$track['label']}\"" : null ?>></track>
				<?php } ?>
			<?php } ?>
			<?= $flash ?>
		</video>
	<?php } else { ?>
		<?= $flash ?>
	<?php } ?>
	<div class="ratio" style="<?= $this->html->ratio($width, $height) ?> display: none;"></div>
</div>