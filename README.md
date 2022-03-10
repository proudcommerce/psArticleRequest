psArticleRequest
=========

Notification tool if non-deliverable item is back in stock.
Free module for OXID eshop 6.x

Features

	If an product is out auf stock, new tab will be displayed on product details page.
	User can leave his email adress. If Product is available again, shop owner can send an
	email (using shop admin) with product availability information. This email can be send
	manually or with cronjob to the first adopters or all saved article requests.

Installation

```
	composer require proudcommerce/psarticlerequest
```

Notice

	This module requires oxid captcha module (oxid-projects/captcha-module) and must
	be acticated before using psArticleRequest. It´s required by composer.json.
	
	
Changelog
	
	2022-03-07	3.1.2	Fix: Smarty rendering oxmail
	2020-03-06	3.1.1	Fix: Only send automatic e-mails for the article where the stock was changed
	2019-10-06	3.1.0	Feature: Limit article request to specified categories
    2019-09-10	3.0.0	Upgrade Module to fit OXID 6.x
	2018-08-27	2.1.0	Add cronjob for sending emails to registered users
	2017-02-02	2.0.1	Fix email validation for older versions
	2016-11-29	2.0.0	Ready for OXID 4.10/5.3, New feature: auto notification for registered users
	2015-04-02	1.1.0	Ready for OXID 4.9/5.2
	2014-06-03	1.0.0	Module release (OXID 4.7/4.8)

Screenshot

![psArticleRequest](https://raw.github.com/proudcommerce/psArticleRequest/master/psArticleRequest_screenshot.png)
![psArticleRequest](https://raw.github.com/proudcommerce/psArticleRequest/master/psArticleRequest_screenshot_admin.png)


License

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    

Copyright

	Proud Sourcing GmbH 2019
	www.proudcommerce.com / www.proudsourcing.de
