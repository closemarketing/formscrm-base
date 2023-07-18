
# Plugin Base for integrating CRM to FormsCRM

## Installation

1. Make an installation of WordPress
2. Download the base plugin FormsCRM from WordPress repository: https://wordpress.org/plugins/formscrm/
3. Download this repository to /wp-content/plugins
4. Search and replace CRMNAME to the CRM in proper name, and crmname to the name in lowercase.
5. Edit the class included in includes/class-crm-base.php to connect to the CRM.

## Explain Methods of class

In the CRM Class there are four methods to connect to the CRM. You will find it in includes/class-crm-crmname.php

### API

The ones that makes the connection with the API of the CRM. It has to be with the native function of WordPress wp_remote_request if it's API Rest, use others if it's different.

Return:
Array of data.

### Login

With the credentials needed, you have to login to the CRM and return true or false depends of the response.

### List modules

List of modules that we can connect and send to the CRM. Ideally would be to have dynamic load of this function.

### List Fields

List of fields of the module selected that we can connect and send to the CRM. Ideally would be to have dynamic load of this function.
Return: Array of fields.

### Create entry

Create entry with values to the CRM merged. Take care of fields that need special format.
Return: id or message error.

## Use of test.php

In order to be more productive, you can use in your installation a test file to directly testing the connection to the CRM. Steps:

1. Copy file of credentials-sample.json to credentials.json.
2. Execute in your browser with the url: http://domain.local/wp-content/plugins/formscrm-base/test/test.php
