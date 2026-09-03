<?php
/**
 * SMTP settings used to send "forgot password" reset emails.
 * Fill these in with your own email account details.
 *
 * For Gmail: use a 16-character "App Password", NOT your normal Gmail
 * password. Create one at https://myaccount.google.com/apppasswords
 * (requires 2-Step Verification to be turned on for your Google account).
 */

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'YOUR_EMAIL@gmail.com');
define('SMTP_PASSWORD', 'YOUR_16_CHARACTER_APP_PASSWORD');
define('SMTP_FROM_EMAIL', 'YOUR_EMAIL@gmail.com');
define('SMTP_FROM_NAME', 'City Care Hospital');
