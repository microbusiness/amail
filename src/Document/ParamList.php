<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.01.2019
 * Time: 11:53
 */

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;

class ParamList
{
    protected $templateList;

    protected $list;

    public function __construct()
    {
        $this->templateList=new ArrayCollection();
        $this->list=new ArrayCollection();

        $this->templateList->set('aeskey','');
    }

    public function getParam($code)
    {
        $result=false;
        if ($this->list->containsKey($code))
        {
            $result=$this->list->get($code);
        }
        return $result;
    }

    public function setParam($row)
    {
        if ($this->templateList->containsKey($row['code'])) {
            $this->list->set($row['code'],$row['val']);
        }
    }
}