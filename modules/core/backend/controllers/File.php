<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core;
use Coast\File as CoastFile;
use Coast\Request;
use Coast\Response;
use FileUpload\FileSystem;
use FileUpload\FileUpload;
use FileUpload\PathResolver;

class File extends Content
{
	protected $_entityClass = 'Chalk\Core\File';

    protected function _actions(Request $req)
    {
        return [
            'create' => null,
            'upload' => 'uploaded',
        ] + parent::_actions($req);
    }

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
                $entity = isset($req->route['params']['id'])
                    ? $this->em($req->info)->id($req->route['params']['id'])
                    : $this->em($req->info)->create();      
                if ($entity->isNew()) {
                    $this->_create($req, $res, $entity);
                } else {
                    $this->_update($req, $res, $entity);
                }
                $view = $entity->isNew() ? 'file/upload' : 'element/card-upload';    
                if (!$this->em->isPersisted($entity)) {
                    $entity->newFile = new CoastFile($upload->path);
                    $this->em->persist($entity);
                } else {
                    $entity->move(new CoastFile($upload->path));
                }
                $this->em->flush();
                unset($upload->path);
                $upload->html = $this->view->render($view, [ 
                    'entity'  => $entity,
                    'covered' => true,
                ] + (array) $req->view, 'core')->toString();
            }
        }

        return $res
            ->headers($headers)
            ->json(['files' => $uploads]);
    }
}