<?php
namespace Ayre\Core\Controller;

use Ayre,
	Coast\Controller\Action,
	Coast\Request,
	Coast\Response;

class User extends Ayre\Controller\Basic
{
	protected $_entityClass = 'Ayre\Core\User';
}