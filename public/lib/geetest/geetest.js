/**
 * Created by Benson on 16/6/13.
 * 极验验证码 创之平台封装
 * 调用: libGeetest.start(成功回调)
 */

(function () {
    var self = this,success,init = false;

    this.start = function (s) {
        success = s;
        jQuery("#gt-btn").click();
    };
    this.load = function () {
        if(init)return false;
        jQuery(document.body).append('<div id="geetest" class="geetest"></div><div id="gt-btn"></div>');
        request.get('/geetest/start',function (data) {
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                product: "popup",
                offline: !data.success
            }, function (captchaObj) {
                jQuery("#gt-btn").click(function () {
                    var validate = captchaObj.getValidate();
                    if (!validate) {
                        pop.error('请完成图片验证','确定').one();
                        return;
                    }
                    request.post("/geetest/verify",function (ret) {
                        if(ret.hasOwnProperty('code') && ret.code == '0'){
                            success();
                        }
                    },{
                        geetest_challenge: validate.geetest_challenge,
                        geetest_validate: validate.geetest_validate,
                        geetest_seccode: validate.geetest_seccode
                    });
                });
                captchaObj.bindOn("#gt-btn");
                captchaObj.appendTo("#geetest");
                init = true;
            });
        });
    };
    this.timer = setTimeout(self.load,2000);
}).call(define('libGeetest'));