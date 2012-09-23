<div id="maffyoo">
	<div id="headlines">
		<?php if(isset($errors)): ?>
		<br />
			<?php foreach($errors as $key => $error): ?>
				<h3><?php echo $error; ?></h3>
			<?php endforeach; ?>
		
		<?php else: ?>
		<h3>Password:</h3>
		<p>&nbsp;</p>
		<p>
			<?php echo $password; ?>
		</p>
		<p>&nbsp;</p>
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
		<?php endif; ?>
	</div>
</div>