<?php __('Greetings from Boxmeup!'); ?>


<?php __('You have made a password recovery request, and we are happy to oblige.'); ?>


<?php echo __('Your recovery key is: ', true) . $password; ?>


<?php __('Follow this link to sign in and reset your password:'); ?>


<?php echo 'http://boxmeupapp.com/forgot_login/' . $api_key . '/' . $dynamic_key . '/' . $hash; ?>


<?php __('Please note: this login is only valid for 1 hour.'); ?>


<?php __('Thanks,'); ?>


<?php __('Boxmeup Team'); ?>