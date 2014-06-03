[{ assign var="shop"      value=$oEmailView->getShop() }]
[{ assign var="oViewConf" value=$oEmailView->getViewConfig() }]

[{include file="email/html/header.tpl" title=$shop->oxshops__oxname->value}]

[{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL1" }]
<br><br>

[{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL2" }] <b>[{$product->oxarticles__oxtitle->value}]</b> [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL3" }]

[{include file="email/html/footer.tpl"}]