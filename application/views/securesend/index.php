<div id="gensend">
    <div class="narrow">
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
        <p class="generated_url">
            <?php echo site_url() . 'v/' . $url ;?>
        </p>
        <?php else: ?>
        <form action="<?=site_url();?>send/" method="post" id="secure_send">
            
        <div class="securesend-password">
            <textarea name="password" id="password" placeholder="Enter your password to send here"><?php echo isset($post['password']) ? htmlentities($post['password'], ENT_QUOTES) : $default_password ; ?></textarea>
        </div>
            
        <div class="col1">
            <ul>
                <li>Expire after: </li>
                <li><input type="text" autocomplete="off" maxlength="2" name="expire[days]" id="expire_days" value="<?php echo isset($post['expire']['days']) ? htmlentities($post['expire']['days'], ENT_QUOTES) : $default_expire_days ; ?>" /><label for="expire_days">days</label></li>
                <li>
                    OR
                </li>
                <li><input type="text" autocomplete="off" maxlength="2" name="expire[views]" id="expire_views" value="<?php echo isset($post['expire']['days']) ? htmlentities($post['expire']['views'], ENT_QUOTES) : $default_expire_views ; ?>" /><label for="expire_views">views</label></li>
                <li>Whichever one comes first.</li>
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
    </div>
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