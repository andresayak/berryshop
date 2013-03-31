<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form;
use Application\Model;

class AuthController extends AbstractActionController
{
    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        if($authService->getUserRow())
            return $this->redirect()->toRoute('home');
        $form = new Form\Login();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($authService->isAuthenticate($form->get('email')->getValue(), $form->get('password')->getValue())) {
                    return $this->redirect()->toRoute('home');
                }else{
                     $this->flashMessenger()->addErrorMessage('login or password invalid');
                }
                return $this->redirect()->toRoute('login');
            }
        }
        return array(
            'form' => $form,
        );
    }
    public function signupAction()
    {
        $form = new Form\Signup();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userTable = $this->getServiceLocator()->get('User_Table');
                $userRow = new Model\User\Row();
                $userRow->exchangeArray($form->getData());
                $userRow->setPassword($form->get('password')->getValue());
                $userTable->saveRow($userRow);
                $this->flashMessenger()->addSuccessMessage('Success');
                return $this->redirect()->toRoute('login');
            }else $form->clearPassword();
        }
        return array(
            'form' => $form,
        );
    }
    
    public function forgotAction()
    {
        $form = new Form\Forgot();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userTable = $this->getServiceLocator()->get('User_Table');
                if($userRow = $userTable->getRowByEmail($form->get('email')->getValue())){
                    $this->flashMessenger()->addSuccessMessage('Success');
                }else $this->flashMessenger()->addErrorMessage('User not found');
                return $this->redirect()->toRoute('forgot');
            }
        }
        return array(
            'form' => $form,
        );
    }
    
    public function logoutAction()
    {
        $this->getServiceLocator()->get('AuthService')->logout();
         
        $this->flashmessenger()->addSuccessMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
}
