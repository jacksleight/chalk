<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Coast\Request;
use Coast\Response;

class File extends Content
{
	protected $_entityClass = 'Chalk\Core\File';

    public function upload(Request $req, Response $res)
    {
        if (!$req->isPost()) {
            throw new \Chalk\Exception("Upload action only accepts POST requests");
        }

        $dir      = $this->chalk->config->dataDir->dir('upload', true);
        $uploader = new FileUpload($_FILES['files'], $req->servers());
        $uploader->setPathResolver(new PathResolver\Simple($dir->name()));
        $uploader->setFileSystem(new FileSystem\Simple());

        list($uploads, $headers) = $uploader->processAll();
        foreach ($uploads as $upload) {
            if (isset($upload->path)) {
                $content = isset($req->route['params']['id'])
                    ? $this->em($req->info)->id($req->route['params']['id'])
                    : $this->em($req->info)->create();      
                $view = $content->isNew() ? 'content/thumb' : 'content/card-upload';    
                if (!$this->em->isPersisted($content)) {
                    $content->newFile = new \Coast\File($upload->path);
                    $this->em->persist($content);
                } else {
                    $content->move(new \Coast\File($upload->path));
                }
                $this->em->flush();
                unset($upload->path);
                $upload->html = $this->view->render($view, [
                    'content'       => $content,
                    'covered'       => true,
                    'isEditAllowed' => (bool) $req->isEditAllowed,
                ] + (array) $req->view, 'core')->toString();
            }
        }

        return $res
            ->headers($headers)
            ->json(['files' => $uploads]);
    }
}