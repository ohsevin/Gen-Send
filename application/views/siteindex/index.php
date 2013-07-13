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
            <strong>Is this site's encryption trustworthy? <a href="https://www.ssllabs.com/ssltest/analyze.html?d=<?php echo SITE_DOMAIN; ?>">Test it!</a></strong>
        </p>
    </div>
</div>