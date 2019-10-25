<?php

namespace App\Backend\Modules\User;

use Entity\User;
use FormBuilder\UserFormBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use OpenFram\Application;
use OpenFram\BackController;
use OpenFram\Form\FormHandler;
use OpenFram\RedirectException;
use OpenFram\FileUploader;

/**
 * Class UserController
 *
 * @package App\Backend\Modules\User
 */
class UserController extends BackController
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
     */
    public function executeIndex(Request $request)
    {
        $this->page->addVar('title', 'Utilisateurs');


        $manager = $this->managers->getManagerOf('User');


        $dataTable = [];
        foreach ($manager->getList() as $user) {
            $dataTable[] = [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'userName' => $user->getUserName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()->getName(),
                'viewLink' => '/admin/user-' . $user->getId() . '.html',
                'editLink' => '/admin/user-edit-' . $user->getId() . '.html',
                'deleteLink' => '/admin/user-delete-' . $user->getId() . '.html',
            ];
        }

        $this->page->addVar('dataTable', $dataTable);

        $this->page->addVar('usersList', $manager->getList());
        $this->page->addVar('usersNumber', $manager->count());
    }

    /**
     * @param Request $request
     * @throws RedirectException
     */
    public function executeEdit(Request $request)
    {
        $manager = $this->managers->getManagerOf('User');

        $user = $manager->getById($request->getQueryParams()['id']);

        if (empty($user)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');

            $message = 'L\'utilisateur n\'existe pas';
            throw new RedirectException($redirectionResponse, $message, 'error');
        }

        $this->requireSelfAccess($user->getId());

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
    public function executeShow(Request $request)
    {
        $manager = $this->managers->getManagerOf('User');

        $user = $manager->getById($request->getQueryParams('GET')['id']);


        if (empty($user)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse, 'L\'utilisateur demandé n\'existe pas', 'error');
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

        $this->page->addVar('title', 'Ajouter un utilisateur');
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
                    'role' => $roleManager->getById($request->getParsedBody()["role"]),
                    'description' => $request->getParsedBody()["description"],
                    'profileImage' => $file,
                ]
            );

            $user->setHashedPassword();


            if (isset($request->getQueryParams()['id'])) {
                $user->setId($request->getQueryParams()['id']);
                if ($request->getParsedBody()["password"] == '') {
                    $user->setPasswordRequired(false);
                }
            }
        } else {
            if (isset($request->getQueryParams()['id'])) {
                $user = $this->managers->getManagerOf('user')->getById($request->getQueryParams()['id']);
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

            $message = $user->isNew() ? 'L\'utlisateur a bien été ajouté' : 'L\'utlisateur a bien été mis à jour';
            $url = '/admin/user-' . urlencode($id) . '.html';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, $message, 'success');
        }

        $this->page->addVar('form', $form->createView());
    }

    /**
     * @param Request $request
     * @throws RedirectException
     */
    public function executeDelete(Request $request)
    {

        $user = $this->managers->getManagerOf('user')->getById($request->getQueryParams()['id']);
        if (empty($user)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse, 'L\'utilisateur n\'existe pas', 'error');
        }

        $this->requireSelfAccess($user->getId());


        $imageUrl = $this->fileUploader->getFile($user->getId());

        $user->setProfileImage($imageUrl);

        if ($request->getMethod() == 'POST') {
            $id = $request->getQueryParams()['id'];

            $this->managers->getManagerOf('user')->delete($id);

            $this->fileUploader->deleteFile($id);

            $url = '/admin/users';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, 'L\'utlisateur a bien été supprimé', 'success');

        }
        $this->page->addVar('title', 'Supprimer un utlisateur');
        $this->page->addVar('user', $user);
    }

}

