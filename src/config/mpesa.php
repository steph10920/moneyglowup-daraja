<?php
define("MPESA_CONSUMER_KEY", getenv("MPESA_CONSUMER_KEY"));
define("MPESA_CONSUMER_SECRET", getenv("MPESA_CONSUMER_SECRET"));
define("MPESA_SHORTCODE", "174379"); // Replace with your shortcode
define("MPESA_PASSKEY", "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919"); // Replace with your MPESA passkey
define("MPESA_CALLBACK_URL", "https://your-backend-domain.com/api/callback"); // Update this URL
define("MPESA_ACCESS_TOKEN_URL", "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials");
define("MPESA_STK_PUSH_URL", "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest");
