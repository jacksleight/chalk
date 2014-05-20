<?php
namespace Ayre\Controller;

use Ayre,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class User extends Ayre\Controller\Basic
{
	protected $_entityClass = 'Ayre\Core\User';
}