<?php
$mp3Src = isset($mp3Src)
	? $this->url($mp3Src)
	: null;
$oggSrc = isset($oggSrc)
	? $this->url($oggSrc)
	: null;
?>
<div
	<?= isset($id) ? "id=\"{$id}\"" : null ?>
	class="audio <?= isset($class) ? "{$class}" : null ?>">
	<?php $this->start() ?>
		<div class="audio-message"><span>
			Sorry, audio playback is not supported by your browser.<br>
			<?php if (isset($mp3Src)) { ?>
				&nbsp;<a href="<?= $mp3Src ?>">Download Audio (MP3)</a>
			<?php } ?>
			<?php if (isset($oggSrc)) { ?>
				&nbsp;<a href="<?= $oggSrc ?>">Download Audio (Ogg Vorbis)</a>
			<?php } ?>
		</span></div>
	<?php $message = $this->end() ?>
	<?php $this->start() ?>
		<?php if (isset($mp3Src)) { ?>
			<object type="application/x-shockwave-flash"
				data="<?= $swf = $this->url('public/flash/player.swf') ?>"
				width="300"
				height="24">
				<param name="movie" value="<?= $swf ?>">
				<param name="allowFullScreen" value="true">
				<param name="wmode" value="transparent">
				<param name="flashVars" value="<?= $this->escape(http_build_query([
					'file'			=> (string) $mp3Src,
					'autostart'		=> isset($autoplay) && $autoplay ? 'true' : 'false',
					'repeat'		=> isset($loop) && $loop ? 'always' : 'none',
					'mute'			=> isset($muted) && $muted ? 'true' : 'false',
					'controlbar'	=> !isset($controls) || $controls ? 'bottom' : 'none',
					'backcolor'		=> '#000000',
					'frontcolor'	=> '#ffffff',
				])) ?>">
				<?= $message ?>
			</object>
		<?php } else { ?>
			<?= $message ?>
		<?php } ?>
	<?php $flash = $this->end() ?>
	<?php if (isset($oggSrc)) { ?>
		<audio
			<?= isset($autoplay) && $autoplay ? "autoplay" : null ?>
			<?= isset($preload) ? "preload=\"{$preload}\"" : "preload=\"none\"" ?>
			<?= isset($loop) && $loop ? "loop" : null ?>
			<?= isset($muted) && $muted ? "muted" : null ?>
			<?= !isset($controls) || $controls ? "controls" : null ?>>
			<source type="audio/ogg" src="<?= $oggSrc ?>"></source>
			<?php if (isset($mp3Src)) { ?>
				<source type="audio/mp3" src="<?= $mp3Src ?>"></source>
			<?php } ?>
			<?= $flash ?>
		</audio>
	<?php } else { ?>
		<?= $flash ?>
	<?php } ?>
</div>