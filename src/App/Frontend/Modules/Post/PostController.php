<?php

namespace App\Frontend\Modules\Post;

use Entity\Comment;
use FormBuilder\CommentFormBuilder;
use GuzzleHttp\Psr7\Request;
use Model\UserManagerPDO;
use Model\PostManagerPDO;
use OpenFram\BackController;
use OpenFram\Form\Form;
use OpenFram\Form\FormHandler;
use GuzzleHttp\Psr7\Response;
use OpenFram\RedirectException;
use function OpenFram\u;

class PostController extends BackController
{
    public function executeIndex(Request $request)
    {

        $limit = 4;

        $page = $request->getQueryPArams()['page'];
        $offset = (($page - 1) * $limit);


        $this->page->addVar('title', 'Liste des  articles');
        $manager = $this->managers->getManagerOf('Post');

        $postsList = $manager->getList(['offset' => $offset, 'limit' => $limit, 'visible' => 1]);
        $postsNumber = $manager->count(['visible' => 1]);

        $this->page->addVar('limit', $limit);
        $this->page->addVar('activePage', $page);
        $this->page->addVar('postsNumber', $postsNumber);
        $this->page->addVar('postsList', $postsList);
        $this->page->addVar('pageType', 'index-page small-header');
    }


    public function executeShow(Request $request)
    {
        $manager = $this->managers->getManagerOf('Post');

        if ($this->app->getCurrentUser()->isAuthenticated()) {
            $permissions = $this->app->getCurrentUser()->getAttribute('user')->getRole()->getPermissions();
            $couple = [];
            foreach ($permissions as $permission) {
                $couple [] = [$permission->getModule(), $permission->getAction()];
            }
            $control = ['Post', 'Preview'];


            if (in_array($control, $couple)) {
                $post = $manager->getById( $request->getQueryParams('GET')['id']);
            } else {
                $post = $manager->getById( $request->getQueryParams('GET')['id'], ['visible' => 1]);
            }
        } else {
            $post = $manager->getById( $request->getQueryParams('GET')['id'], ['visible' => 1]);
        }



        if (empty($post)) {
            $redirectionResponse = (new Response())
                ->withStatus(404, 'Not found');
            throw new RedirectException($redirectionResponse,'Not Found','danger');

        }



        $this->page->addVar('title', $post->getTitle());
        $this->page->addVar('post', $post);


        $comments = $this->managers->getManagerOf('Comment')->getListOF($post);

        $this->page->addVar('comments', $comments);
        $this->executeInsertComment($request);
    }

    private function executeInsertComment(Request $request)
    {

        if ($request->getMethod() == 'POST') {
            if (isset($request->getParsedBody()['loginForComment'])) {
                $this->app->getCurrentUser()->setAttribute('lastUrl', $this->app->getRequest()->getUri()->getPath());
                $this->app->getCurrentUser()->setAttribute('commentContent', $request->getParsedBody()['content']);
                $url = '/connection';
                $redirectionResponse = (new Response())
                    ->withStatus(301, 'redirection')
                    ->withHeader('Location', $url);
                throw new RedirectException($redirectionResponse,'Redirection','success');
            }


            $comment = new Comment(
                [
                    'content' => $request->getParsedBody()['content'],
                    'post' => $this->managers->getManagerOf('Post')->getById($request->getQueryParams('GET')['id']),
                    'user' => $this->app->getCurrentUser()->getAttribute('user')
                ]
            );
        } else {
            $comment = new Comment;

            if ($this->app->getCurrentUser()->hasAttribute('commentContent')) {
                $comment->setContent($this->app->getCurrentUser()->getAttribute('commentContent'));
                $this->app->getCurrentUser()->setAttribute('commentContent', null);
            }
        }

        $formBuilder = new CommentFormBuilder($this->app, $comment);
        $formBuilder->build();

        $form = $formBuilder->getFrom();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('comment'), $request);

        if ($formHandler->process() != false) {
            $url = '/post-' . u($request->getQueryParams()['id']) . '.html#commentsList';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse,'Votre commentaitre a bien été ajouté, merci!','success');
        }

        $this->page->addVar('comment', $comment);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('pageType', 'small-header');
    }
}
