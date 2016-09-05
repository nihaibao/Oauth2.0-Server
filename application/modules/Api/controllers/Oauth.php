<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/18
 * Time: 上午10:17
 */
class OauthController extends \Core\Api\MyCon
{
    protected $_login = false;

    /**
     * return {"access_token":"7d7a0d03588281c3e00d821602d8e058c9e928c8","expires_in":3600,"token_type":"Bearer","scope":null}
     */
    public function tokenAction()
    {
        $this->_oauthServer->addGrantType(new \OAuth2\GrantType\ClientCredentials($this->_oauthStorage));

        $this->_oauthServer->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    /**
     * 获取用户的token
     * @param response_type = code
     * @param client_id
     * @param redirect_uri
     * @param state
     * @param access_token
     * @param userid
     */
    public function authorizeAction()
    {
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        if (!$this->_oauthServer->verifyResourceRequest($request)) {
            $this->_oauthServer->getResponse()->send();
            die;
        }

        if (!$this->_oauthServer->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        //登陆后获取用户id
        $userid = $_REQUEST['uid'];

        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();
        $this->_oauthServer->handleAuthorizeRequest($request, $response, true ,$userid);
        $this->redirect($response->getHttpHeader('Location'));
    }

    /**
     * code换取用户access_token,refresh_token
     * @param  grant_type=authorization_code
     * @param  code
     * @return {"access_token":"90ffa624c6dadabfdd161998fb1c9d5ba258d60c","expires_in":3600,"token_type":"Bearer","scope":null,"refresh_token":"e5f1a7b0c7f6c841b3a71229eaf3efc9da3c291c"}
     */
    public function accessTokenAction()
    {
        $this->_oauthServer->addGrantType(new \OAuth2\GrantType\AuthorizationCode($this->_oauthStorage));

        $this->_oauthServer->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    /**
     * refresh_token换取access_token
     * @param grant_type=refresh_token
     * @param refresh_token
     * @return {"access_token":"90ffa624c6dadabfdd161998fb1c9d5ba258d60c","expires_in":3600,"token_type":"Bearer","scope":null,"refresh_token":"e5f1a7b0c7f6c841b3a71229eaf3efc9da3c291c"}
     */
    public function refreshTokenAction()
    {
        $this->_oauthServer->addGrantType(new \OAuth2\GrantType\RefreshToken($this->_oauthStorage,['always_issue_new_refresh_token' => true]));

        $this->_oauthServer->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

}