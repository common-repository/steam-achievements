<p>
  <label>Title</label> 
  <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
	Total Badges
	<?php echo count($obj_achievements); ?> 
</p>
<p>
  <label>How many of your most recent badges would you you like to display?</label> 
  <input size="4" name="<?php echo $this->get_field_name('num_achievements'); ?>" type="text" value="<?php echo $num_achievements; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_name('achievement_width'); ?>">How many achievements to show in a row?</label>
	<select name="<?php echo $this->get_field_name('achievement_width'); ?>" id="<?php echo $this->get_field_name('achievement_width'); ?>">
		<option value="wpsteam-one-wide"<?php echo ($achievement_width=='wpsteam-one-wide')?'selected':''; ?>>One</option>
		<option value="wpsteam-two-wide"<?php echo ($achievement_width=='wpsteam-two-wide')?'selected':''; ?>>Two</option>
		<option value="wpsteam-three-wide"<?php echo ($achievement_width=='wpsteam-three-wide')?'selected':''; ?>>Three</option>
		<option value="wpsteam-four-wide"<?php echo ($achievement_width=='wpsteam-four-wide')?'selected':''; ?>>Four</option>
		<option value="wpsteam-five-wide"<?php echo ($achievement_width=='wpsteam-five-wide')?'selected':''; ?>>Five</option>
	</select>
</p>
<p>
  <label>Display tooltips?</label> 
  <input type="checkbox" name="<?php echo $this->get_field_name('display_tooltip'); ?>" value="1" <?php checked( $display_tooltip, 1 ); ?> />
</p>