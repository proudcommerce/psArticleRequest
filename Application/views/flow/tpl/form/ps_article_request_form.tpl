[{oxscript include="js/libs/jqBootstrapValidation.min.js" priority=10}]
[{oxscript add="$('input,select,textarea').not('[type=submit]').jqBootstrapValidation();"}]

<p>[{oxmultilang ident="PS_ARTICLEREQUEST_WHEN_INFORM_TITLE" }]</p>
<form name="articlerequest" action="[{$oViewConf->getSelfActionLink() }]" method="post">
    <div>
        [{$oViewConf->getHiddenSid() }]
        [{$oViewConf->getNavFormParams() }]
        <input type="hidden" name="cl" value="details">
        [{if $oDetailsProduct}]
    <input type="hidden" name="anid" value="[{$oDetailsProduct->oxarticles__oxid->value}]">
        [{/if}]
        <input type="hidden" name="fnc" value="request_product">
        [{assign var="oCaptcha" value=$oView->getCaptcha() }]
        <input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>
    </div>

    <div class="">
        <div class="form-group">
            <label class="req col-xs-4">[{oxmultilang ident="PS_ARTICLEREQUEST_EMAIL" }]:</label>
            <div class="col-xs-8">
                <input class="form-control" required type="email" name="pa[email]" value="[{if $oxcmp_user }][{$oxcmp_user->oxuser__oxusername->value }][{/if}]" size="20" maxlength="128">
                <div class="help-block"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="req col-xs-4">[{oxmultilang ident="PS_ARTICLEREQUEST_VERIFICATIONCODE" }]:</label>
            <div class="col-xs-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        [{if $oCaptcha->isImageVisible()}]
                            <img class="verificationCode" src="[{$oCaptcha->getImageUrl()}]" alt="[{oxmultilang ident="PS_ARTICLEREQUEST_VERIFICATIONCODE" }]">
                        [{else}]
                            <span class="verificationCode" id="verifyTextCode">[{$oCaptcha->getText()}]</span>
                        [{/if}]
                    </div>
                    <br>
                    <input class="js-oxValidate js-oxValidate_notEmpty form-control" required type="text" data-fieldsize="verify" name="c_mac" value="">
                </div>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="note">
                <br>
                [{oxmultilang ident="PS_ARTICLE_REQUEST_DSGVO" }]
                <br><br>
            </div>

            <button class="submitButton largeButton btn btn-primary" type="submit">[{oxmultilang ident="PS_ARTICLEREQUEST_SEND" }]</button>
        </div>
    </div>
</form>
