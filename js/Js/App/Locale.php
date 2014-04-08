<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\App;

class Locale implements \Coast\App\Access, \Coast\App\Executable
{
    use \Coast\App\Access\Implementation;
    use \Coast\Options;

	protected $_locale;

    public function __construct(array $options = array())
    {
        $this->options(array_merge([
			'locales'	=> [],
			'cookie'	=> 'Js\App\Locale',
        ], $options));
    }

	public function get()
	{
		return $this->_locale;
	}

	public function execute(\Coast\App\Request $req, \Coast\App\Response $res)
	{
		$locales = &$this->_options->locales;

		$id = $req->cookie($this->_options->cookie);
		if (!isset($id) || !isset($locales[$id])) {
			$accept = \Js\Locale::acceptFromHttp($req->header('Accept-Language'));
			$name   = \Js\Locale::lookup(array_values($locales), $accept, current($locales));
			$id		= array_search($name, $locales);
		}
		$res->cookie($this->_options->cookie, $id, 3600 * 24 * 28);

		$file = new \Coast\File("locales/{$id}.php");
		$this->_locale = new \Js\Locale($locales[$id], $file->exists() ? $file : null);
	}

	public function __call($name, array $args)
	{
		if (method_exists($this->_locale, $name)) {
			return call_user_func_array([$this->_locale, $name], $args);
		}
		return $this->_app->__call($name, $args);
	}
}