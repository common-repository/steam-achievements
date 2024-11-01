<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>Steam Achievements Plugin</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
				
				<div class="meta-box-sortables ui-sortable">

					<?php if( !isset( $wpsteam_name ) || $wpsteam_name == '' ): ?>

					<div class="postbox">
					
						<h3><span>Let's Get Started!</span></h3>
						<div>
							
							<form name="wpsteam_name_form" method="post" action="">							

							<input type="hidden" name="wpsteam_name_form_submitted" value="Y">

							<table class="form-table">
								<tr>
									<td>
										<label for="wpsteam_name">Steam Profile Name</label>
									</td>
									<td>
										<input name="wpsteam_name" id="wpsteam_name" type="text" value="" class="regular-text" />
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<label for="wpsteam_name">Enter your unique URL you selected for your steam account, or your 64bit Steam ID.<br />Example: Lunch_Meat or 76561197963973896</label>
									</td>
								</tr>
								<tr>
									<td>
										<input class="button-primary" type="submit" name="wpsteam_name_submit" value="Save" /> 
									</td>
								</tr>								
							</table>

							</form>

						</div> <!-- .inside -->
					
					</div> <!-- .postbox -->

					<?php else: ?>

					<div class="postbox">
					
						<h3><span>Most Recent Badges</span></h3>
						<div class="inside clearfix">

							<!-- This uses the GetPlayerAchievements API -->
							<p>
								You have completed <?php echo $achievement_count; ?> Team Fortress 2 Achievements.
							</p>
							<table class="backend-wpsteam-achievements">
								<tr>
									<th>Icon</th>
									<th>Name</th>
									<th>Description</th>
									<th>Unlock Date</th>
								</tr>

							<?php 
								foreach($achievements as $achievement) {
							?>
								<tr>
									<td>
										<img src="<?php echo $achievement['iconClosed']?>" alt="<?php echo $achievement['name']?>">
									</td>
									<td>										
										<h3><?php echo $achievement['name']; ?></h3>
									</td>
									<td>
										<p><?php echo $achievement['description']; ?></p>
									</td>
									<td>
										<p class="backend-wpsteam-unlocked"><?php echo date( 'F jS, Y',$achievement['unlockTimestamp']); ?></p>
									</td>
								</tr>
								
								
							<?php } ?>
							</table> <!-- end .steam-achievements -->
						</div> <!-- end .inside clearfix -->
					</div> <!-- end .postbox -->

					<?php endif; ?>

				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- post-body-content -->
			
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				
				<div class="meta-box-sortables">
					
					<?php if( isset( $wpsteam_name ) && $wpsteam_name != '' ): ?>

					<div class="postbox">
					
						<h3><span><?php echo $wpsteam_profile['steamID']; ?></span></h3>
						<div class="inside">
							
							<p><img width="100%" class="steam-gravatar" src="<?php echo $wpsteam_profile['avatarFull']; ?>" alt="<?php echo $wpsteam_profile['steamID'] . 's Avatar' ?>"></p>

							<ul class="wpsteam-total-achievements">							

									<li>Achievements: <strong><?php echo $achievement_count; ?></strong></li>

							</ul>

							<form name="wpsteam_name_form" method="post" action="">							

							<input type="hidden" name="wpsteam_name_form_submitted" value="Y">

							<p>
								<label for="wpsteam_name">Steam URL Name</label>
							</p>
							<p>
								<input name="wpsteam_name" id="wpsteam_name" type="text" value="<?php echo $wpsteam_name; ?>" />
								<input class="button-primary" type="submit" name="wpsteam_name_usename_submit" value="Update" /> 
							</p>

							</form>

						</div> <!-- .inside -->
						
					</div> <!-- .postbox -->
					
					<?php endif; ?>

				</div> <!-- .meta-box-sortables -->
				
			</div> <!-- #postbox-container-1 .postbox-container -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->