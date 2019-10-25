<?php

namespace App\Backend\Modules\Post;

use Entity\Post;
use FormBuilder\PostFormBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use OpenFram\Application;
use OpenFram\BackController;
use OpenFram\FileUploader;
use OpenFram\Form\FormHandler;
use OpenFram\RedirectException;

class PostController extends BackController
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
            $app->getRequest()->getServerParams()['DOCUMENT_ROOT'] . '/images/post/',
            'post'
        );
    }

    public function executePreview(Request $request)
    {
        $id = $request->getQueryParams('GET')['id'];
        $post = $this->getEntityById('post', $id);

        $this->requireSelfAccess($post->getUser()->getId());


        $url = '/post-' . urlencode($id) . '.html';
        $redirectionResponse = (new Response())
            ->withStatus(301, 'redirection')
            ->withHeader('Location', $url);
        throw new RedirectException($redirectionResponse, 'Redirection');


    }


    public function executeShow(Request $request)
    {
        $id = $request->getQueryParams()['id'];

        $post = $this->getEntityById('post', $id);


        if (empty($post)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse, 'L\'article n\'existe pas');
        }


        $this->requireSelfAccess($post->getUser()->getId());


        $imageUrl = $this->fileUploader->getFile($post->getId());
        $post->setFeaturedImage($imageUrl);


        $comments = $this->managers->getManagerOf('Comment')->getListOF($post);

        $this->page->addVar('title', $post->getTitle());
        $this->page->addVar('post', $post);
        $this->page->addVar('commentsList', $comments);
    }

    public function executeIndex(Request $request)
    {

        $this->page->addVar('title', 'Articles');


        $manager = $this->managers->getManagerOf('Post');

        if ($this->app->getCurrentUser()->getAttribute('user')->getRole()->getId() != 1) {
            $postsList = $manager->getList(['userId' => $this->app->getCurrentUser()->getAttribute('user')->getId()]);
            $postsNumber = $manager->count(['userId' => $this->app->getCurrentUser()->getAttribute('user')->getId()]);
        } else {
            $postsList = $manager->getList();
            $postsNumber = $manager->count();
        }

        $dataTable = [];
        foreach ($postsList as $post) {
            $dataTable[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'author' => $post->getUser()->getUserName(),
                'visible' => $post->isVisible(),
                'lastUpdate' => $post->getModificationDate()->format('Y-m-d H:i:s'),
                'viewLink' => '/admin/post-' . $post->getId() . '.html',
                'editLink' => '/admin/post-edit-' . $post->getId() . '.html',
                'deleteLink' => '/admin/post-delete-' . $post->getId() . '.html',
            ];
        }


        $this->page->addVar('dataTable', $dataTable);
        $this->page->addVar('postsList', $postsList);
        $this->page->addVar('postsNumber', $postsNumber);
    }


    public function executeDelete(Request $request)
    {
        $this->page->addVar('title', 'Supprimer un article');

        $id = $request->getQueryParams()['id'];

        $post = $this->getEntityById('post', $id);

        if (empty($post)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse, 'L\'Article n\'existe pas','error');
        }

        $this->requireSelfAccess($post->getUser()->getId());


        $imageUrl = $this->fileUploader->getFile($post->getId());
        $post->setFeaturedImage($imageUrl);


        $this->page->addVar('post', $post);


        if ($request->getMethod() == 'POST') {

            $this->managers->getManagerOf('post')->delete($id);

            $this->fileUploader->deleteFile($id);


            $url = '/admin/posts';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            $message = 'L\'article a bien été supprimé';
            throw new RedirectException($redirectionResponse, $message,'success');

        }
    }

    public function executeInsert(Request $request)
    {
        $this->processForm($request);
        $this->page->addVar('title', 'Ajouter un article');
    }

    public function executeEdit(Request $request)
    {

        $this->processForm($request);
        $this->page->addVar('title', 'Modifier un article');
    }

    private function processForm(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $file = $request->getUploadedFiles()["featuredImage"];
            if ($file->getError() === 4) {
                $file = null;
            }

            $post = new Post([
                'title' => $request->getParsedBody()['title'],
                'subtitle' => $request->getParsedBody()['subtitle'],
                'user' => $this->app->getCurrentUser()->getAttribute('user'),
                'content' => $request->getParsedBody()['content'],
                'visible' => $request->getParsedBody()['save'],
                'featuredImage' => $file
            ]);


            if (isset($request->getQueryParams()['id'])) {
                $post->setId($request->getQueryParams()['id']);
            }

        } else {
            if (isset($request->getQueryParams()['id'])) {

                $post = $this->managers->getManagerOf('post')->getById($request->getQueryParams()['id']);

                if (empty($post)) {
                    $redirectionResponse = (new Response())
                        ->withStatus(404, 'Not found');
                    throw new RedirectException($redirectionResponse, 'l\'Article demandé n\'existe pas');
                }

                $this->requireSelfAccess($post->getUser()->getId());


                $imageUrl = $this->fileUploader->getFile($post->getId());
                $post->setFeaturedImage($imageUrl);

            } else {
                $post = new Post;
            }
        }


        $formBuilder = new PostFormBuilder($this->app, $post);
        $formBuilder->build();
        $form = $formBuilder->getFrom();
        $formHandler = new FormHandler($form, $this->managers->getManagerOf('post'), $request);

        if (false !== $id = $formHandler->process()) {

            if ($post->getFeaturedImage() !== null) {
                $this->fileUploader->uploadFile($post->getFeaturedImage(), $id);
            }

            $message = $post->isNew() ? 'L\'article a bien été ajouté' : 'L\'article a bien été mis à jour';
            $url = '/admin/posts';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, $message,'success');
        }

        $this->page->addVar('form', $form->createView());
    }

    private function getEntityById(string $entity, int $id)
    {
        return $this->managers->getManagerOf($entity)->getById($id);
    }


}
