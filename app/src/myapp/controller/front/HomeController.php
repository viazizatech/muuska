<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use muuska\util\App;

class HomeController extends AbstractController
{	
    // default function
	protected function processDefault()
    {
        $user = $this->input->getCurrentUser();
		$page = App::htmls()->createHtmlCustomElement();
        $page->addExtra('user',$user);
        $page->setRenderer($this->input->getSubProject()->createTemplate('welcome'));
        $this->result->setContent($page);
    }
}