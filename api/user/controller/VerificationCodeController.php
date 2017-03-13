<?php
namespace api\user\controller;

use cmf\controller\RestBaseController;
use think\Validate;

class VerificationCodeController extends RestBaseController
{
    public function send()
    {
        $validate = new Validate([
            'username' => 'require',
        ]);

        $validate->message([
            'username.require' => '请输入手机号或邮箱!',
        ]);

        $data = $this->request->param();
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        $accountType = '';

        if (Validate::is($data['username'], 'email')) {
            $accountType = 'email';
        } else if (preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $data['username'])) {
            $accountType = 'mobile';
        } else {
            $this->error("请输入正确的手机或者邮箱格式!");
        }

        //TODO 限制 每个ip 的发送次数

        $code = cmf_get_verification_code($data['username']);
        if (empty($code)) {
            $this->error("验证码发送过多,请明天再试!");
        }

        if ($accountType == 'email') {

            //TODO 实现邮箱验证码发送
            cmf_verification_code_log($data['username'],'666666');

        } else if ($accountType == 'mobile') {

            //TODO 实现手机验证码发送
            cmf_verification_code_log($data['username'],'666666');
        }

        $this->success("验证码已经发送成功!您的验证码默认是666666");
    }

}
