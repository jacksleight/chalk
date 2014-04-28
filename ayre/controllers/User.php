<?php
namespace Ayre\Controller;

use Ayre,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class User extends Ayre\Controller\Entity
{
	protected $_entityClass = 'Ayre\Entity\User';
}