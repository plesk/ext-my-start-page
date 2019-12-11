<?php
// Copyright 1999-2019. Plesk International GmbH. All Rights Reserved.

use PleskExt\MyStartPage\Helper;

class IndexController extends pm_Controller_Action
{
    protected $_accessLevel = 'admin';

    public function init()
    {
        parent::init();

        $this->view->pageTitle = $this->lmsg('pageTitle');
    }

    public function indexAction()
    {
        $redirectUrl = pm_Settings::get(Helper::SETTING_KEY, '');

        if ($redirectUrl === '') {
            $redirectUrl = pm_Config::get(Helper::SETTING_KEY, '');
        }

        $form = new pm_Form_Simple();
        $form->addElement('text', 'myStartPageLink', [
            'label'        => $this->lmsg('formMyStartPageLink'),
            'value'        => $redirectUrl,
            'style'        => 'width: 50%;',
            'validators'   => [
                new Zend_Validate_Callback([
                    Helper::class,
                    'isValid',
                ]),
            ],
            'autocomplete' => 'off',
        ]);
        $form->addControlButtons(['cancelLink' => pm_Context::getModulesListUrl(),]);

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $myStartPageLink = filter_var($form->getValue('myStartPageLink') ?? '', FILTER_SANITIZE_URL);
            pm_Settings::set('myStartPageLink', $myStartPageLink);

            if (!empty($myStartPageLink)) {
                $_SESSION['myStartPageRedirect'] = true;
            }

            $this->_status->addMessage('info', $this->lmsg('messageSuccess'));
            $this->_helper->json(['redirect' => pm_Context::getBaseUrl()]);
        }

        $this->view->form = $form;
        $this->view->outputDescription = $this->lmsg('pageTitleDescription');
    }
}
