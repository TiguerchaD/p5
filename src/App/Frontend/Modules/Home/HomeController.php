<?php
namespace App\Frontend\Modules\Home;

use Entity\Contact;
use FormBuilder\ContactFormBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use OpenFram\BackController;
use OpenFram\Form\FormHandler;
use OpenFram\Managers;
use OpenFram\RedirectException;

class HomeController extends BackController
{
    public function executeShow(Request $request)
    {
        $this->page->addVar('title', $this->getModule());
        $manager = $this->managers->getManagerOf('post');
        $postsList = $manager->getList(['limit'=> 4, 'visible'=> 1]);
        $this->page->addVar('postsList', $postsList);
        $this->page->addVar('pageType', 'profile-page');

        $this->executeContact($request);
    }

    private function executeContact(Request $request)
    {
        $this->managers = new Managers('', '');
        $manager = $this->managers->getManagerOf('contact');

        if ($request->getMethod()=='POST') {
            $contact = new Contact(
                [
                    'firstName'=>$this->app->getRequest()->getParsedBody()['firstName'],
                    'email'=>$this->app->getRequest()->getParsedBody()['email'],
                    'object'=>$this->app->getRequest()->getParsedBody()['object'],
                    'message'=>$this->app->getRequest()->getParsedBody()['message']
                ]
            );
        } else {
            $contact = new Contact();
        }


        $formBuilder = new ContactFormBuilder($this->app, $contact);
        $formBuilder->build();
        $form = $formBuilder->getFrom();
        $formHandler = new FormHandler($form, $manager, $request);

        if ($formHandler->process() !== false) {
            $url = '/#contactSection';
            $redirectionResponse = (new Response())
                ->withStatus(301, 'redirection')
                ->withHeader('Location', $url);
            throw new RedirectException($redirectionResponse, 'Votre Message a bien été envoyé , merci!', 'success');

        }

        $this->page->addVar('form', $form->createView());
    }
}
