<div id="gensend">
    <div id="headlines">
        <h3>Useful things:</h3>
        <div class="col1">
            <ul>
                <li><a href="<?php echo site_url();?>gen/">Password: Generator</a></li>
            </ul>
        </div>
        <div class="col2">
            <ul>
                <li><a href="<?php echo site_url();?>send/">Password: Secure Send</a></li>
            </ul>
        </div>
        <div class="footnotes">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
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
</div>