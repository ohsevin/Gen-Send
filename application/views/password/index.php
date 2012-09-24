<div id="maffyoo">
    <div id="headlines">
        <h3>Generate a random password:</h3>
        
        <?php if(isset($errors)): ?>
        <br />
            <?php foreach($errors as $key => $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
            
        <?php if(isset($password) && trim($password) != ''): ?>
        <h2><?php echo htmlentities($password); ?></h2>
        <h2 class="sub"><?php echo $phonetic; ?></h2>
        
        <form action="<?=site_url();?>send/" method="post">
            <input type="hidden" value="<?php echo $password; ?>" name="password" />
            <input type="hidden" value="1" name="password_transfer" />
            <input type="submit" value="Transfer to secure send tool..." name="submit" />
        </form>
        
        <?php endif; ?>
        <form action="<?=site_url();?>gen/" method="post">
        <div class="col1">
            <ul>
                <li><label for="length">Length:</label> <input type="text" maxlength="2" name="input[length]" id="length" value="<?php echo isset($post['input']['length']) ? $post['input']['length'] : $default_password_length ; ?>" /></li>
                <li>
                    <label for="non_similar_lowercase">Letters:</label>
                    <input id="non_similar_lowercase" type="checkbox" name="options[non_similar_lowercase]" value="true"
                    <?php if(isset($post['submit'])): ?>
                        <?php if(isset($post['options']['non_similar_lowercase'])): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php else: ?>
                        checked="checked"
                    <?php endif; ?>
                    />
                </li>
                <li>
                    <label for="non_similar_uppercase">Mixed Case:</label>
                    <input id="non_similar_uppercase" type="checkbox" name="options[non_similar_uppercase]" value="true"
                    <?php if(isset($post['submit'])): ?>
                        <?php if(isset($post['options']['non_similar_uppercase'])): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php else: ?>
                        checked="checked"
                    <?php endif; ?>
                    />
                </li>
            </ul>
        </div>
        <div class="col2">
            <ul>
                <li>
                    <label for="standard_numbers">Numbers:</label>
                    <input id="standard_numbers" type="checkbox" name="options[standard_numbers]" value="true"
                    <?php if(isset($post['submit'])): ?>
                        <?php if(isset($post['options']['standard_numbers'])): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php else: ?>
                        checked="checked"
                    <?php endif; ?>
                    />
                </li>
                <li>
                    <label for="punctuation">Punctuation:</label>
                    <input id="punctuation" type="checkbox" name="options[punctuation]" value="true"
                    <?php if(isset($post['submit'])): ?>
                        <?php if(isset($post['options']['punctuation'])): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php endif; ?>
                    />
                </li>
                <li>
                    <label for="similar">Similar Chars:</label>
                    <input id="similar" type="checkbox" name="options[similar]" value="true"
                    <?php if(isset($post['submit'])): ?>
                        <?php if(isset($post['options']['similar'])): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php endif; ?>
                    />
                </li>
            </ul>
        </div>
        <input type="submit" value="Generate password..." name="submit" />
        </form>
        <div class="footnotes">
            <?php if($is_ssl): ?>
            <p>
                <strong>This page is secure.</strong>
            </p>
            <p>
                All data on this page is encrypted and sent over SSL.
            </p>
            <p>
                No passwords are stored when generated <br />
                <strong>
                    unless you send them securely to the <a href="securesend/">Secure-send tool.</a><br />
                    this is not done unless you click the "Transfer to secure send tool..." button and complete the form.
                </strong>
                
            </p>
            <?php endif; ?>
            <p>
                Source code for these tools can be found on <a href="<?php echo GEN_SEND_GITHUB_URL; ?>">Github</a>
            </p>
        </div>
    </div>
</div>