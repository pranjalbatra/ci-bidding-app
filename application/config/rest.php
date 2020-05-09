<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| HTTP protocol
|--------------------------------------------------------------------------
|
| Should the service accept only HTTPS requests or not?
|
|	Default: FALSE
|
*/
$config['force_https'] = TRUE;

/*
|--------------------------------------------------------------------------
| REST Format
|--------------------------------------------------------------------------
|
| What format should the data be returned in by default?
|
|	Default: xml
|
*/
$config['rest_default_format'] = 'json';

/*
|--------------------------------------------------------------------------
| Enable emulate request
|--------------------------------------------------------------------------
|
| Should we enable emulation of the request (e.g. used in Mootools request)?
|
|	Default: false
|
*/
$config['enable_emulate_request'] = TRUE;


/*
|--------------------------------------------------------------------------
| REST Realm
|--------------------------------------------------------------------------
|
| Name for the password protected REST API displayed on login dialogs
|
|	E.g: My Secret REST API
|
*/
$config['rest_realm'] = 'REST API';

/*
|--------------------------------------------------------------------------
| REST Login
|--------------------------------------------------------------------------
|
| Is login required and if so, which type of login?
|
|	'' = no login required, 'basic' = unsecure login, 'digest' = more secure login
|
*/
$config['rest_auth'] = '';

/*
|--------------------------------------------------------------------------
| REST Login
|--------------------------------------------------------------------------
|
| Is login required and if so, which user store do we use?
|
| '' = use config based users, 'ldap' = use LDAP authencation
|
*/
$config['auth_source'] = '';

/*
|--------------------------------------------------------------------------
| Override auth types for specific class/method
|--------------------------------------------------------------------------
|
| Set specific authentication types for methods within a class (controller)
|
| Set as many config entries as needed.  Any methods not set will use the default 'rest_auth' config value.
|
| example:
|
|			$config['auth_override_class_method']['deals']['view'] = 'none';
|			$config['auth_override_class_method']['deals']['insert'] = 'digest';
|			$config['auth_override_class_method']['accounts']['user'] = 'basic';
|
| Here 'deals' and 'accounts' are controller names, 'view', 'insert' and 'user' are methods within. (NOTE: leave off the '_get' or '_post' from the end of the method name)
| Acceptable values are; 'none', 'digest' and 'basic'.
|
*/
// $config['auth_override_class_method']['deals']['view'] = 'none';
// $config['auth_override_class_method']['deals']['insert'] = 'digest';
// $config['auth_override_class_method']['accounts']['user'] = 'basic';

/*
|--------------------------------------------------------------------------
| REST Login usernames
|--------------------------------------------------------------------------
|
| Array of usernames and passwords for login, if ldap is configured this is ignored
|
|	array('admin' => '1234')
|
*/
$config['rest_valid_logins'] = array('admin' => 'trigon@@');

/*
|--------------------------------------------------------------------------
| Global IP Whitelisting
|--------------------------------------------------------------------------
|
| Limit connections to your REST server to whitelisted IP addresses.
|
| Usage:
| 1. Set to true *and* select an auth option for extreme security (client's IP
|	 address must be in whitelist and they must also log in)
| 2. Set to true with auth set to false to allow whitelisted IPs access with no login.
| 3. Set to false here but set 'auth_override_class_method' to 'whitelist' to
|	 restrict certain methods to IPs in your whitelist
|
*/
$config['rest_ip_whitelist_enabled'] = false;

/*
|--------------------------------------------------------------------------
| REST IP Whitelist
|--------------------------------------------------------------------------
|
| Limit connections to your REST server to a comma separated
| list of IP addresses
|
| Example: $config['rest_ip_whitelist'] = '123.456.789.0, 987.654.32.1';
|
| 127.0.0.1 and 0.0.0.0 are allowed by default.
|
*/
$config['rest_ip_whitelist'] = '';

/*
|--------------------------------------------------------------------------
| REST Database Group
|--------------------------------------------------------------------------
|
| Connect to a database group for keys, logging, etc. It will only connect
| if you have any of these features enabled
|
*/
$config['rest_database_group'] = 'default';

/*
|--------------------------------------------------------------------------
| REST API Keys Table Name
|--------------------------------------------------------------------------
|
| The table name in your database that stores API keys
|
*/
$config['rest_keys_table'] = 'keys';

/*
|--------------------------------------------------------------------------
| REST Enable Keys
|--------------------------------------------------------------------------
|
| When set to TRUE, the REST API will look for a column name called 'key'.
| If no key is provided, the request will result in an error. To override the
| column name see 'rest_key_column'
|
| Default table schema:
|   CREATE TABLE `keys` (
|       `id` INT(11) NOT NULL AUTO_INCREMENT,
|       `key` VARCHAR(40) NOT NULL,
|       `level` INT(2) NOT NULL,
|       `ignore_limits` TINYINT(1) NOT NULL DEFAULT '0',
|       `is_private_key` TINYINT(1)  NOT NULL DEFAULT '0',
|       `ip_addresses` TEXT NULL DEFAULT NULL,
|       `date_created` INT(11) NOT NULL,
|       PRIMARY KEY (`id`)
|   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
|
*/
$config['rest_enable_keys'] = FALSE;

/*
|--------------------------------------------------------------------------
| REST Table Key Column Name
|--------------------------------------------------------------------------
|
| If not using the default table schema in 'rest_enable_keys', specify the
| column name to match e.g. my_key
|
*/
$config['rest_key_column'] = 'key';

/*
|--------------------------------------------------------------------------
| REST API Limits method
|--------------------------------------------------------------------------
|
| Specify the method used to limit the API calls
|
| Available methods are :
| $config['rest_limits_method'] = 'API_KEY'; // Put a limit per api key
| $config['rest_limits_method'] = 'METHOD_NAME'; // Put a limit on method calls
| $config['rest_limits_method'] = 'ROUTED_URL';  // Put a limit on the routed URL
|
*/
$config['rest_limits_method'] = 'ROUTED_URL';

/*
|--------------------------------------------------------------------------
| REST Key Length
|--------------------------------------------------------------------------
|
| Length of the created keys. Check your default database schema on the
| maximum length allowed
|
| Note: The maximum length is 40
|
*/
$config['rest_key_length'] = 40;

/*
|--------------------------------------------------------------------------
| REST API Key Variable
|--------------------------------------------------------------------------
|
| Custom header to specify the API key

| Note: Custom headers with the X- prefix are deprecated as of
| 2012/06/12. See RFC 6648 specification for more details
|
*/
$config['rest_key_name'] = 'api_key';

/*
|--------------------------------------------------------------------------
| REST Enable Logging
|--------------------------------------------------------------------------
|
| When set to TRUE, the REST API will log actions based on the column names 'key', 'date',
| 'time' and 'ip_address'. This is a general rule that can be overridden in the
| $this->method array for each controller
|
| Default table schema:
|   CREATE TABLE `logs` (
|       `id` INT(11) NOT NULL AUTO_INCREMENT,
|       `uri` VARCHAR(255) NOT NULL,
|       `method` VARCHAR(6) NOT NULL,
|       `params` TEXT DEFAULT NULL,
|       `api_key` VARCHAR(40) NOT NULL,
|       `ip_address` VARCHAR(45) NOT NULL,
|       `time` INT(11) NOT NULL,
|       `rtime` FLOAT DEFAULT NULL,
|       `authorized` VARCHAR(1) NOT NULL,
|       `response_code` smallint(3) DEFAULT '0',
|       PRIMARY KEY (`id`)
|   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
|
*/
$config['rest_enable_logging'] = FALSE;

/*
|--------------------------------------------------------------------------
| REST API Logs Table Name
|--------------------------------------------------------------------------
|
| If not using the default table schema in 'rest_enable_logging', specify the
| table name to match e.g. my_logs
|
*/
$config['rest_logs_table'] = 'logs';

/*
|--------------------------------------------------------------------------
| REST Method Access Control
|--------------------------------------------------------------------------
| When set to TRUE, the REST API will check the access table to see if
| the API key can access that controller. 'rest_enable_keys' must be enabled
| to use this
|
| Default table schema:
|   CREATE TABLE `access` (
|       `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
|       `key` VARCHAR(40) NOT NULL DEFAULT '',
|       `controller` VARCHAR(50) NOT NULL DEFAULT '',
|       `date_created` DATETIME DEFAULT NULL,
|       `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
|       PRIMARY KEY (`id`)
|    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
|
*/
$config['rest_enable_access'] = FALSE;

/*
|--------------------------------------------------------------------------
| REST API Access Table Name
|--------------------------------------------------------------------------
|
| If not using the default table schema in 'rest_enable_access', specify the
| table name to match e.g. my_access
|
*/
$config['rest_access_table'] = 'access';

/*
|--------------------------------------------------------------------------
| REST API Param Log Format
|--------------------------------------------------------------------------
|
| When set to TRUE, the REST API log parameters will be stored in the database as JSON
| Set to FALSE to log as serialized PHP
|
*/
$config['rest_logs_json_params'] = FALSE;

/*
|--------------------------------------------------------------------------
| REST Enable Limits
|--------------------------------------------------------------------------
|
| When set to TRUE, the REST API will count the number of uses of each method
| by an API key each hour. This is a general rule that can be overridden in the
| $this->method array in each controller
|
| Default table schema:
|   CREATE TABLE `limits` (
|       `id` INT(11) NOT NULL AUTO_INCREMENT,
|       `uri` VARCHAR(255) NOT NULL,
|       `count` INT(10) NOT NULL,
|       `hour_started` INT(11) NOT NULL,
|       `api_key` VARCHAR(40) NOT NULL,
|       PRIMARY KEY (`id`)
|   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
|
| To specify the limits within the controller's __construct() method, add per-method
| limits with:
|
|       $this->method['METHOD_NAME']['limit'] = [NUM_REQUESTS_PER_HOUR];
|
| See application/controllers/api/example.php for examples
*/
$config['rest_enable_limits'] = FALSE;

/*
|--------------------------------------------------------------------------
| REST API Limits Table Name
|--------------------------------------------------------------------------
|
| If not using the default table schema in 'rest_enable_limits', specify the
| table name to match e.g. my_limits
|
*/
$config['rest_limits_table'] = 'limits';

/*
|--------------------------------------------------------------------------
| REST Ignore HTTP Accept
|--------------------------------------------------------------------------
|
| Set to TRUE to ignore the HTTP Accept and speed up each request a little.
| Only do this if you are using the $this->rest_format or /format/xml in URLs
|
*/
$config['rest_ignore_http_accept'] = FALSE;

/*
|--------------------------------------------------------------------------
| REST AJAX Only
|--------------------------------------------------------------------------
|
| Set to TRUE to allow AJAX requests only. Set to FALSE to accept HTTP requests
|
| Note: If set to TRUE and the request is not AJAX, a 505 response with the
| error message 'Only AJAX requests are accepted.' will be returned.
|
| Hint: This is good for production environments
|
*/
$config['rest_ajax_only'] = FALSE;

/*
|--------------------------------------------------------------------------
| REST Language File
|--------------------------------------------------------------------------
|
| Language file to load from the language directory
|
*/
$config['rest_language'] = 'english';
