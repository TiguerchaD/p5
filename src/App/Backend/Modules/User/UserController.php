<?php


namespace App\Backend\Modules\User;

use Entity\User;
use FormBuilder\PostFormBuilder;
use FormBuilder\UserFormBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use OpenFram\Application;
use OpenFram\BackController;
use OpenFram\Form\FormHandler;
use OpenFram\RedirectException;
use OpenFram\FileUploader;
use function OpenFram\escape_to_html as h;
use function OpenFram\u;

class UserController extends BackController
{
    private $fileUploader;

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $this->fileUploader = new FileUploader($app->getRequest()->getServerParams()['DOCUMENT_ROOT'] . '/images/user/', 'user');
    }

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

    public function executeEdit(Request $request)
    {
        $manager = $this->managers->getManagerOf('User');

        $user = $manager->getById($request->getQueryParams()['id']);

        $currentUser = $this->app->getCurrentUser()->getAttribute('user');

        if ($currentUser->getRole()->getId() != 1 && $currentUser->getId() !== $user->getId()) {
            $this->app->getCurrentUser()->setFlash('Accès refusé');
            throw new RedirectException('/admin/user-edit-' . htmlspecialchars(urlencode($currentUser->getId())) . '.html', 301, 'Redirection');

        }


        if (empty($user)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse, 'Redirection');
        }


        $imageUrl = $this->fileUploader->getFile($user->getId());
        $user->setProfileImage($imageUrl);


        $this->page->addVar('title', $user->getUserName());
        $this->page->addVar('user', $user);

        $this->processForm($request);
    }

    public function executeShow(Request $request)
    {
        $manager = $this->managers->getManagerOf('User');

        $user = $manager->getById($request->getQueryParams('GET')['id']);

        if (empty($user)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse, 'Redirection');
        }


        $imageUrl = $this->fileUploader->getFile($user->getId());
        $user->setProfileImage($imageUrl);



        $this->page->addVar('title', $user->getUserName());
        $this->page->addVar('user', $user);



        $this->processForm($request);
    }


    public function executeInsert(Request $request)
    {
        $this->processForm($request);


        $this->page->addVar('title', 'Ajouter un utilisateur');
    }


    private function processForm(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $file = $request->getUploadedFiles()["profileImage"];
            if ($file->getError() === 4) {
                $file = null;
            }
            $roleManager = $this->managers->getManagerOf('role');

            $user = new User([
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
            ]);
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
                $this->fileUploader->uploadFile($user->getProfileImage() ,$id);
            }


            $this->app->getCurrentUser()->setFlash($user->isNew() ? 'L\'utlisateur a bien été ajouté' : 'L\'utlisateur a bien été mis à jour');
            $url = '/admin/user-' . htmlspecialchars(urlencode($user->getId())) . '.html';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, 'Redirection');
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeDelete(Request $request)
    {



        $user = $this->managers->getManagerOf('user')->getById($request->getQueryParams()['id']);
        $imageUrl = $this->fileUploader->getFile($user->getId());
        $user->setProfileImage($imageUrl);



        if ($request->getMethod() == 'POST') {
            $id = $request->getQueryParams('GET')['id'];

            $this->managers->getManagerOf('user')->delete($id);

            $this->fileUploader->deleteFile($id);



            $this->app->getCurrentUser()->setFlash('L\'utlisateur a bien été supprimé');
            $url = '/admin/users';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, 'Redirection');

        }

        $this->page->addVar('title', 'Supprimer un utlisateur');
        $this->page->addVar('user', $user);
    }
}

