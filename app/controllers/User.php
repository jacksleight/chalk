<?php
namespace Chalk\Core\Controller;

use Chalk,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class User extends Chalk\Controller\Basic
{
	protected $_entityClass = 'Chalk\Core\User';
}