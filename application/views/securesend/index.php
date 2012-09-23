<div id="maffyoo">
	<div id="headlines">
		<h3>Send your password securely</h3>
		
		<?php if(isset($errors)): ?>
			<div class="errors">
				<?php foreach($errors as $key => $error): ?>
					<p><?php echo $error; ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
			
		<?php if(isset($success) && $success): ?>
		<h2>URL Generated:</h2>
		<p>&nbsp;</p>
		<p>
			The following URL will link to your password securely:
		</p>
		<p>
			<?php echo site_url() . 'securesend/v/' . $url ;?>
		</p>
		<?php else: ?>
		<form action="<?=site_url();?>securesend/" method="post">
			
		<div class="securesend-password">
			<input type="text" autocomplete="off" name="password" id="password" value="<?php echo isset($post['password']) ? $post['password'] : $default_password ; ?>" placeholder="Paste your password here:" />
		</div>
			
		<div class="col1">
			<ul>
				<li><label for="expire">Expire after:</label> <input type="text" autocomplete="off" maxlength="2" name="expire[length]" id="expire" value="<?php echo isset($post['expire']['length']) ? $post['expire']['length'] : $default_expire_time ; ?>" /></li>
				<li>
					<label for="expire_days">Days:</label>
					<input id="expire_days" type="radio" name="expire[time]" value="days"
					<?php if(isset($post['submit'])): ?>
						<?php if(isset($post['expire']['time']) && $post['expire']['time'] == 'days'): ?>
							checked="checked"
						<?php endif; ?>
					<?php endif; ?>
					/>
				</li>
				
				<li>
					<label for="expire_weeks">Weeks:</label>
					<input id="expire_weeks" type="radio" name="expire[time]" value="weeks"
					<?php if(isset($post['submit'])): ?>
						<?php if(isset($post['expire']['time']) && $post['expire']['time'] == 'weeks'): ?>
							checked="checked"
						<?php endif; ?>
					<?php endif; ?>
					/>
				</li>
				
				<li>
					<label for="expire_months">Months:</label>
					<input id="expire_months" type="radio" name="expire[time]" value="months"
					<?php if(isset($post['submit'])): ?>
						<?php if(isset($post['expire']['time']) && $post['expire']['time'] == 'months'): ?>
							checked="checked"
						<?php endif; ?>
					<?php endif; ?>
					/>
				</li>
				
				<li>
					<label for="expire_weeks">Views:</label>
					<input id="expire_weeks" type="radio" name="expire[time]" value="views"
					<?php if(isset($post['submit'])): ?>
						<?php if(isset($post['expire']['time']) && $post['expire']['time'] == 'views'): ?>
							checked="checked"
						<?php endif; ?>
					<?php endif; ?>
					/>
				</li>
			</ul>
		</div>
		<div class="col2">
			<div class="notes">
				<p>
					Use these options to determine when the password will expire.
				</p>
				<p>
					Once the expiration date or total number of allowed views has passed, the password will be deleted from the database and no record of it will be left.
				</p>
				<p>
					No information about you is stored in the database. Only what you enter here and the URL to identify this password which is a randomly generated string.
				</p>
			</div>
		</div>
		<input type="submit" value="Generate secure link..." name="submit" />
		</form>
		<?php endif; ?>
		<div class="footnotes">
			<?php if($is_ssl): ?>
			<p>
				<strong>This page is secure.</strong>
			</p>
			<p>
				All data on this page is encrypted and sent over SSL.
			</p>
			<p>
				All passwords are deleted from the database when they expire. <br />(either through views or time)
			</p>
			<?php endif; ?>
            <p>
                Source code for these tools can be found on <a href="<?php echo GEN_SEND_GITHUB_URL; ?>">Github</a>
            </p>
		</div>
	</div>
</div>