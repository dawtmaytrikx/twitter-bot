# CLI Twitter status update bot
## Original bash version
* Author: [lukapusic](https://github.com/lukapusic) <luka@pusic.si>
* URI: http://360percents.com/posts/command-line-twitter-status-update-for-linux-and-mac/
* Github: https://github.com/lukapusic/twitter-bot

## This PHP version
* Author: [dawtmaytrikx](https://github.com/dawtmaytrikx)
* Github: https://github.com/dawtmaytrikx/twitter-bot

## Description
This script can log into your Twitter account and post a new tweet, all without the official API.

## System requirements
* PHP
* cURL

## Instructions
### Under UNIXoid system:
1. apply executable permissions ```chmod +x ./tweet.php```
2. usage: ```./tweet.php 'Status'``` or ```php tweet.php 'Status'```

### Under Windows:
1. usage ```php.exe tweet.php 'Status'``` (NOT tested!)

## Known issues
* Will not throw any errors if it's unsuccessful, e.g. when it hits a CAPTCHA at login, or if the text has been tweeted before.
* Ignores that URLs are shortened to 20 or 21 chars by twitter.

## License
[CC-BY-NC](https://creativecommons.org/licenses/by-nc/2.0/), dawtmaytrikx
