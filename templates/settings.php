<div id="shibboleth" class="section">
	<h2>Shibboleth</h2>
	<form id="user_shib" action="" method="post">
		<fieldset class="personalblock">
			<legend><strong><?php p($l->t("Backend")); ?></strong></legend>
			<label for="shib_handler"><?php p($l->t("Handler URL")); ?>:</label>
			<input type="text" id="shib_handler" name="shib_handler" value="<?php p($_["shib_handler"]); ?>" title="<?php p($l->t("Handler URL")); ?>">
			<br/>
			<label for="shib_login"><?php p($l->t("Login URL")); ?>:</label>
			<input type="text" id="shib_login" name="shib_login" value="<?php p($_["shib_login"]); ?>" title="<?php p($l->t("Login URL")); ?>">
			<br/>
			<label for="shib_logout"><?php p($l->t("Logout URL")); ?>:</label>
			<input type="text" id="shib_logout" name="shib_logout" value="<?php p($_["shib_logout"]); ?>" title="<?php p($l->t("Logout URL")); ?>">
			<br/>
			<label for="shib_group"><?php p($l->t("Group")); ?>:</label>
			<input type="text" id="shib_group" name="shib_group" value="<?php p($_["shib_group"]); ?>" title="<?php p($l->t("Group")); ?>">
			<br/>
			<label for="shib_quota"><?php p($l->t("Quota")); ?>:</label>
			<input type="text" id="shib_quota" name="shib_quota" value="<?php p($_["shib_quota"]); ?>" title="<?php p($l->t("Quota")); ?>">
			<br/>
			<br/>

			<legend><strong><?php p($l->t("Variables")); ?></strong></legend>
			<label for="shib_idp"><?php p($l->t("Identity Provider")); ?>:</label>
			<input type="text" id="shib_idp" name="shib_idp" value="<?php p($_["shib_idp"]); ?>" title="<?php p($l->t("Identity Provider")); ?>">
			<br/>
			<br/>

			<legend><strong><?php p($l->t("Attributes")); ?></strong></legend>
			<label for="shib_mail_attr"><?php p($l->t("Mail")); ?>:</label>
			<input type="text" id="shib_mail_attr" name="shib_mail_attr" value="<?php p($_["shib_mail_attr"]); ?>" title="<?php p($l->t("Mail")); ?>">
			<br/>
			<label for="shib_pid_attr"><?php p($l->t("Persistent ID")); ?>:</label>
			<input type="text" id="shib_pid_attr" name="shib_pid_attr" value="<?php p($_["shib_pid_attr"]); ?>" title="<?php p($l->t("Persistent ID")); ?>">
			<br/>
			<label for="shib_group_attr"><?php p($l->t("Group")); ?>:</label>
			<input type="text" id="shib_group_attr" name="shib_group_attr" value="<?php p($_["shib_group_attr"]); ?>" title="<?php p($l->t("Group")); ?>">
			<br/>
			<label for="shib_fn_attr"><?php p($l->t("Full Name")); ?>:</label>
			<input type="text" id="shib_fn_attr" name="shib_fn_attr" value="<?php p($_["shib_fn_attr"]); ?>" title="<?php p($l->t("Full Name")); ?>">
			<br/>
			<br/>

			<input type="text" name="requesttoken" value="<?php p(\OCP\Util::callRegister()); ?>" title="Request Token" hidden="">
			<input type="submit" value="Save">
		</fieldset>
	</form>
</div>
