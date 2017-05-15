<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller\Setting;

use Chalk\Chalk;
use Chalk\Controller\Basic;
use Coast\Request;
use Coast\Response;
use Chalk\Core\Model\Tag\Merge;

class Tag extends Basic
{
	protected $_entityClass = 'Chalk\Core\Tag';

    public function merge(Request $req, Response $res)
    {       
        $merge = new Merge();
        $req->view->merge = $wrap = $this->em->wrap($merge);

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->graphIsValid()) {
            $this->notify("{$req->info->plural} could not be merged, please check the messages below", 'negative');
            return;
        }

        $sourceTag = $this->em('core_tag')->id($merge->sourceTag->id);
        $targetTag = $this->em('core_tag')->id($merge->targetTag->id);
        $entities  = $this->em('core_content')->all(['tags' => [$sourceTag]]);
        foreach ($entities as $entity) {
            $entity->tags->removeElement($sourceTag);
            if (!$entity->tags->contains($targetTag)) {
                $entity->tags->add($targetTag);
            }
        }
        $this->em->remove($sourceTag);
        $this->em->flush();

        $this->notify("{$req->info->plural} were merged successfully", 'positive');
        return $res->redirect($this->url(array(
            'action'    => null,
            'id'        => null,
        )));
    }
}