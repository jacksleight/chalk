<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core\Backend\Controller\Crud;
use Coast\Request;
use Coast\Response;
use Chalk\Core\Backend\Model\Tag\Manage;
use Chalk\Core\Backend\Model\Tag\Merge;

class Tag extends Crud
{
	protected $_entityClass = 'Chalk\Core\Tag';

    public function manage(Request $req, Response $res)
    {
        $model = new Manage();
        $req->view->model = $modelWrap = $this->em->wrap($model);

        if (!$req->isPost()) {
            $modelWrap->graphFromArray($req->queryParams());
            return;
        }

        $modelWrap->graphFromArray($req->bodyParams());
        if (!$modelWrap->graphIsValid()) {
            $this->notify("Tags could not be saved, please check the messages below", 'negative');
            return;
        }

        $tags     = $this->em('core_tag')->names($model->tagNames());
        $entities = $this->em($model->entityType)->all(['ids' => $model->selected()]);

        try {
            if ($model->type == 'add') {
                foreach ($entities as $entity) {
                    foreach ($tags as $tag) {
                        if (!$entity->tags->contains($tag)) {
                            $entity->tags->add($tag);
                        }
                    }
                }
            } else if ($model->type == 'remove') {
                foreach ($entities as $entity) {
                    foreach ($tags as $tag) {
                        if ($entity->tags->contains($tag)) {
                            $entity->tags->removeElement($tag);
                        }
                    }
                }
            }
            $this->em->flush();
        } catch (\Exception $e) {
            throw $e;
        }

        $this->notify("Tags were saved successfully", 'positive');
        $req->data->clear = true;
    }

    public function merge(Request $req, Response $res)
    {       
        $model = new Merge();
        $req->view->model = $modelWrap = $this->em->wrap($model);

        if (!$req->isPost()) {
            return;
        }

        $modelWrap->graphFromArray($req->bodyParams());
        if (!$modelWrap->graphIsValid()) {
            $this->notify("{$req->info->plural} could not be merged, please check the messages below", 'negative');
            return;
        }

        $sourceTag = $this->em('core_tag')->id($model->sourceTag->id);
        $targetTag = $this->em('core_tag')->id($model->targetTag->id);

        $mds = $this->em->getMetadataFactory()->getAllMetadata();
        $classes = array_map(function($md) {
            return $md->getName();
        }, $mds);

        foreach ($classes as $class) {
            $info = Chalk::info($class);
            if (!$info->is->tagable) {
                continue;
            }
            $entities = $this->em($class)->all(['tags' => [$sourceTag]]);
            foreach ($entities as $entity) {
                if (!$entity->tags->contains($targetTag)) {
                    $entity->tags->add($targetTag);
                }
            }
        }
        $this->em->remove($sourceTag);
        $this->em->flush();

        $this->notify("{$req->info->plural} were merged successfully", 'positive');
        return $res->redirect($this->url(array(
            'action' => null,
        )));
    }
}