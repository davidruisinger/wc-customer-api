WooCommerce Customer API Plugin
=======================================

## About
A WooCommerce API wrapper to serve an API that only offers customer features (browsing products and creating orders).

## Required
- PHP 5.2.x
- cURL

- Wordpress Plugins:
 - WooCommerce >= 2.2 (https://wordpress.org/plugins/woocommerce/)
 - WP REST API (https://wordpress.org/plugins/json-rest-api/)

## Getting started
Activate the WooCommerce API and generate API credentials under WP Admin > Your Profile.
Paste those credentials into WooCommerce > Settings > WooCommerce Customer REST API.

## Methods
* GET `/wcc-api`- index of the API

* GET `/wcc-api/products`- get a list of products

more to come soon...

## Credit
This plugin uses the [kloon's WooCommerce API PHP wrapper](github.com/kloon/WooCommerce-REST-API-Client-Library)

## License
Released under the [GPL3 license](http://www.gnu.org/licenses/gpl-3.0.html)