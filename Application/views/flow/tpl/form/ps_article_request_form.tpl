[{oxscript include="js/libs/jqBootstrapValidation.min.js" priority=10}]
[{oxscript add="$('input,select,textarea').not('[type=submit]').jqBootstrapValidation();"}]
[{assign var=variants value=$oDetailsProduct->getFullVariants(false)}]

<p>[{oxmultilang ident="PS_ARTICLEREQUEST_WHEN_INFORM_TITLE" }]</p>
<form name="articlerequest" action="[{$oViewConf->getSelfActionLink() }]" method="post" class="psArticleRequest">
    <div>
        [{$oViewConf->getHiddenSid() }]
        [{$oViewConf->getNavFormParams() }]
        <input type="hidden" name="cl" value="details">
        [{if $oDetailsProduct && $variants|@count == 0}]
            <input type="hidden" name="anid" value="[{$oDetailsProduct->oxarticles__oxid->value}]">
        [{/if}]
        <input type="hidden" name="fnc" value="request_product">
        [{assign var="oCaptcha" value=$oView->getCaptcha() }]
        <input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <div class="row">
                    <label class="req col-12 col-xs-12 col-sm-4">[{oxmultilang ident="PS_ARTICLEREQUEST_EMAIL" }]:</label>
                    <div class="col-12 col-xs-12 col-sm-8">
                        <input class="form-control" required type="email" name="pa[email]" value="[{if $oxcmp_user }][{$oxcmp_user->oxuser__oxusername->value }][{/if}]" size="20" maxlength="128">
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div>
        [{if $variants|@count}]
        <div class="col-12">
            <div class="form-group">
                <div class="row">
                    <label class="req col-12 col-xs-12 col-sm-4">[{oxmultilang ident="PS_ARTICLEREQUEST_VARIANT" }]:</label>
                    <div class="col-12 col-xs-12 col-sm-8">
                        <select class="form-control" required name="anid">
                            [{foreach from=$variants item=variant}]
                            [{if $variant->getStockStatus() == -1}]
                                <option value="[{$variant->oxarticles__oxid->value}]">[{$variant->oxarticles__oxvarselect->value}]</option>
                            [{/if}]
                            [{/foreach}]
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div>
        [{/if}]

        <div class="col-12">
            <div class="form-group">
                <div class="row">
                    <label class="req col-12 col-xs-12 col-sm-4">[{oxmultilang ident="PS_ARTICLEREQUEST_VERIFICATIONCODE" }]:</label>
                    <div class="col-4 col-xs-4 col-sm-2 captchaCol">
                        [{if $oCaptcha->isImageVisible()}]
                            <img class="verificationCode" src="[{$oCaptcha->getImageUrl()}]" alt="[{oxmultilang ident="PS_ARTICLEREQUEST_VERIFICATIONCODE" }]">
                        [{else}]
                            <span class="verificationCode" id="verifyTextCode">[{$oCaptcha->getText()}]</span>
                        [{/if}]
                    </div>
                    <div class="col-8 col-xs-8 col-sm-6 inputCol">
                        <div class="input-group">
                            <br>
                            <input class="js-oxValidate js-oxValidate_notEmpty form-control" required type="text" data-fieldsize="verify" name="c_mac" value="">
                        </div>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="note">
                <br>
                [{oxmultilang ident="PS_ARTICLE_REQUEST_DSGVO" }]
                <br><br>
            </div>

            <button class="submitButton largeButton btn btn-primary" type="submit">[{oxmultilang ident="PS_ARTICLEREQUEST_SEND" }]</button>
        </div>
    </div>
</form>
