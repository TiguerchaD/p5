<?php

namespace App\Frontend\Modules\Profile;

use Entity\User;
use FormBuilder\UserFormBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use OpenFram\Application;
use OpenFram\BackController;
use OpenFram\FileUploader;
use OpenFram\Form\FormHandler;
use OpenFram\RedirectException;
use function OpenFram\u;

class ProfileController extends BackController
{
    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * UserController constructor.
     *
     * @param Application $app
     * @param $module
     * @param $action
     */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $this->fileUploader = new FileUploader(
            $app->getRequest()->getServerParams()['DOCUMENT_ROOT'] . '/images/user/',
            'user'
        );
    }

    /**
     * @param Request $request
     * @throws RedirectException
     */
    public function executeShow(Request $request)
    {

        if ($this->app->getCurrentUser()->hasAttribute('user') !== true) {
            $redirectionResponse = (new Response())
                ->withStatus(301, 'Redirection')
                ->withHeader('Location', '/connection');
            throw new RedirectException($redirectionResponse, 'Vous n\'êtes pas connecté', 'info');
        }

        $user = $this->app->getCurrentUser()->getAttribute('user');


        if ($user->getRole()->getid() != 3) {
            $redirectionResponse = (new Response())
                ->withStatus(301, 'Redirection')
                ->withHeader('Location', '/admin/user-' . u($user->getId()) . '.html');
            throw new RedirectException($redirectionResponse, 'Bienvenue sur votre espace administration',
                'info');
        }


        $imageUrl = $this->fileUploader->getFile($user->getId());

        $user->setProfileImage($imageUrl);




        $this->page->addVar('title', $user->getUserName());
        $this->page->addVar('user', $user);


        $this->processForm($request);
    }


    /**
     * @param Request $request
     * @throws RedirectException
     */
    public function executeInsert(Request $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Inscription');
    }

    /**
     * @param Request $request
     * @throws RedirectException
     */
    public function executeEdit(Request $request)
    {
        if ($this->app->getCurrentUser()->hasAttribute('user') !== true) {
            $redirectionResponse = (new Response())
                ->withStatus(301, 'Redirection')
                ->withHeader('Location', '/connection');
            throw new RedirectException($redirectionResponse, 'Vous n\'êtes pas connecté', 'info');
        }

        $user = $this->app->getCurrentUser()->getAttribute('user');

        $imageUrl = $this->fileUploader->getFile($user->getId());
        $user->setProfileImage($imageUrl);

        $this->page->addVar('title', $user->getUserName());
        $this->page->addVar('user', $user);

        $this->processForm($request);
    }

    /**
     * @param Request $request
     * @throws RedirectException
     */
    private function processForm(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $file = $request->getUploadedFiles()["profileImage"];
            if ($file->getError() === 4) {
                $file = null;
            }
            $roleManager = $this->managers->getManagerOf('role');
            $user = new User(
                [
                    'firstName' => $request->getParsedBody()["firstName"],
                    'lastName' => $request->getParsedBody()["lastName"],
                    'userName' => $request->getParsedBody()["userName"],
                    'email' => $request->getParsedBody()["email"],
                    'confirmEmail' => $request->getParsedBody()["confirmEmail"],
                    'password' => $request->getParsedBody()["password"],
                    'confirmPassword' => $request->getParsedBody()["confirmPassword"],
                    'role' => $roleManager->getById('3'),
                    'description' => $request->getParsedBody()["description"],
                    'profileImage' => $file,
                ]
            );

            $user->setHashedPassword();


            if ($this->app->getCurrentUser()->hasAttribute('user')) {
                $user->setId($this->app->getCurrentUser()->getAttribute('user')->getId());
                if ($request->getParsedBody()["password"] == '') {
                    $user->setPasswordRequired(false);
                }
            }
        } else {

            if ($this->app->getCurrentUser()->hasAttribute('user')) {
                $user = $this->managers->getManagerOf('user')->getById($this->app->getCurrentUser()->getAttribute('user')->getId());
                $imageUrl = $this->fileUploader->getFile($user->getId());
                $user->setProfileImage($imageUrl);
            } else {
                $user = new User;
            }
        }

        $formBuilder = new UserFormBuilder($this->app, $user);
        $formBuilder->build();
        $form = $formBuilder->getFrom();
        $formHandler = new FormHandler($form, $this->managers->getManagerOf('user'), $request);

        if (false !== $id = $formHandler->process()) {

            if ($user->getProfileImage() !== null) {
                $this->fileUploader->uploadFile($user->getProfileImage(), $id);
            }
            $user->getId() === null ? $user->setId($id) : null;

            $this->app->getCurrentUser()->setAttribute('user', $user);


            $message = $user->isNew() ? 'Votre compte a bien été créé' : 'Votre compte bien été mis à jour';
            $url = '/profile';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, $message, 'success');
        }

        $this->page->addVar('form', $form->createView());
        $this->page->addVar('pageType', 'small-header');

    }


    /**
     * @param Request $request
     * @throws RedirectException
     */
    public function executeDelete(Request $request)
    {

        $user = $this->app->getCurrentUser()->getAttribute('user');

        if ($request->getMethod() == 'POST') {
            $id = $user->getId();

            $this->managers->getManagerOf('user')->delete($id);

            $this->fileUploader->deleteFile($id);

            $url = '/logout';

            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, 'L\'utlisateur a bien été supprimé', 'success');

        }

        $this->page->addVar('title', 'Supprimer un utlisateur');
        $this->page->addVar('user', $user);
    }





}
