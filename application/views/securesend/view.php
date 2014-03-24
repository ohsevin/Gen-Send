<div id="gensend">
    <div id="headlines">
        <?php if(isset($errors)): ?>
        <br />
            <?php foreach($errors as $key => $error): ?>
                <h3><?php echo $error; ?></h3>
            <?php endforeach; ?>
        
        <?php else: ?>
        <h3 class="password_title">Your password is:</h3>
        <form action="" method="post">
	        <textarea name="password" class="generated_url password_display password"><?php echo ($password); ?></textarea>
        </form>
        <p>This password expires on <?php echo $expiry_formatted; ?> or <?php echo $remaining_views; ?> more view<?php echo ($remaining_views == 1) ? '' : 's'; ?>. </p>
        <div class="footnotes">
            <?php if($is_ssl): ?>
            <p>
                <strong>This page is secure.</strong>
            </p>
            <p>
                Your connection to this site is encrypted.
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