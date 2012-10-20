<div id="gensend">
    <div id="buttons">
        <p>
            <a href="<?php echo site_url();?>gen/"><span>Generate</span> a password</a>
        </p>
        <p class="or">
            or
        </p>
        <p>
            <a href="<?php echo site_url();?>send/"><span>Send</span> a password</a>
        </p>
    </div>
    <div class="footnotes">
        <?php if($is_ssl): ?>
        <p>
            <strong>This page is secure.</strong>
        </p>
        <p>
            All data on this page is encrypted and sent over SSL.
        </p>
        <?php endif; ?>
        <p>
            Source code for these tools can be found on <a href="<?php echo GEN_SEND_GITHUB_URL; ?>">Github</a>
        </p>
    </div>
</div>