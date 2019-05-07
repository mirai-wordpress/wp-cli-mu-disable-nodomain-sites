# wp-cli-mu-disable-nodomain-sites

WP-CLI command to delete sites with no associated domain

## Installation
wp package install mirai-wordpress/wp-cli-mu-disable-nodomain-sites

## Commands

### List sites with no associated domain
wp nodomain_sites list [--months=n]

### Disable sites with no associated domain
wp nodomain_sites disable [--months=n]

## Parameters
### months: limits listed or disabled sites to at least n months old. Default: 3


## Changelog

### 1.0
Initial Release


