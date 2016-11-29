[{assign var="shop"      value=$oEmailView->getShop()}]
[{assign var="oViewConf" value=$oEmailView->getViewConfig()}]
[{assign var="user"      value=$oEmailView->getUser()}]


[{include file="email/html/header.tpl" title="PS_ARTICLE_REQUEST_MAIL1"|oxmultilangassign}]

[{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL1" }]
<br><br>

[{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL2" }] <b>[{$product->oxarticles__oxtitle->value}]</b> [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL3" }]
<br><br>

[{include file="email/html/footer.tpl"}]
