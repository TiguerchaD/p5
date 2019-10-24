<?php

namespace App\Backend\Modules\Post;

use Entity\Post;
use FormBuilder\PostFormBuilder;
use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\UploadedFile;

use OpenFram\BackController;
use OpenFram\Form\FormHandler;
use OpenFram\RedirectException;
use function GuzzleHttp\Psr7\stream_for;

class PostController extends BackController
{
    public function executePreview(Request $request)
    {
        $id = $request->getQueryParams('GET')['id'];
        $post = $this->managers->getManagerOf('Post')->getById( $id);
        $currentUser = $this->app->getCurrentUser()->getAttribute('user');

        if ($currentUser->getRole()->getId() != 1 && $currentUser->getId() !== $post->getUser()->getId() && $post->isVisible() == 0) {
            $this->app->getCurrentUser()->setFlash('Accès refusé');

            $url = '/admin/posts';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse,'Redirection');

        }

        $url = '/post-' . urlencode($id) . '.html' ;
        $redirectionResponse = (new Response())
            ->withStatus(301, 'redirection')
            ->withHeader('Location', $url);
        throw new RedirectException($redirectionResponse,'Redirection');


    }


    public function executeShow(Request $request)
    {
        $manager = $this->managers->getManagerOf('Post');

        $post = $manager->getById( $request->getQueryParams('GET')['id']);

        if (empty($post)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse,'Redirection');
        }

        $currentUser = $this->app->getCurrentUser()->getAttribute('user');

        if ($currentUser->getRole()->getId() != 1 && $currentUser->getId() !== $post->getUser()->getId()) {
            $this->app->getCurrentUser()->setFlash('Accès refusé');
            $url = '/admin/posts';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse,'Redirection');


        }

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
            $postsNumber = $manager->count(['userId' =>  $this->app->getCurrentUser()->getAttribute('user')->getId()]);
        } else {
            $postsList = $manager->getList() ;
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

        $manager =  $this->managers->getManagerOf('post');

        $post =$manager->getById($request->getQueryParams()['id']);

        if (empty($post)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse,'Redirection');
        }

        $currentUser = $this->app->getCurrentUser()->getAttribute('user');



        if ($currentUser->getRole()->getId() != 1 && $currentUser->getId() !== $post->getUser()->getId()) {
            $this->app->getCurrentUser()->setFlash('Accès refusé');

            $url = '/admin/posts';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse,'Redirection');

        }

            $this->page->addVar('post', $post);

        if ($request->getMethod() == 'POST') {
            $id = $this->app->getRequest()->getQueryParams('GET')['id'];

            $manager->delete($id);

            $this->app->getCurrentUser()->setFlash('L\'article a bien été supprimé');

            $url = '/admin/posts';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse,'Redirection');

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
                    throw new RedirectException($redirectionResponse,'Redirection');
                }
                $currentUser = $this->app->getCurrentUser()->getAttribute('user');

                if ($currentUser->getRole()->getId() != 1 && $currentUser->getId() !== $post->getUser()->getId()) {
                    $this->app->getCurrentUser()->setFlash('Accès refusé');

                    $url = '/admin/posts';
                    $redirectionResponse = (new Response())
                        ->withStatus(301, 'redirection')
                        ->withHeader('Location', $url);
                    throw new RedirectException($redirectionResponse,'Redirection');

                }
            } else {
                $post = new Post;
            }
        }


        $formBuilder = new PostFormBuilder($this->app, $post);
        $formBuilder->build();
        $form = $formBuilder->getFrom();
        $formHandler = new FormHandler($form, $this->managers->getManagerOf('post'), $request);

        if ($formHandler->process()) {
            $this->app->getCurrentUser()->setFlash($post->isNew() ? 'L\'article a bien été ajouté' : 'L\'article a bien été mis à jour');


            $url = '/admin/posts';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse,'Redirection');

        }

        $this->page->addVar('form', $form->createView());
    }
}
