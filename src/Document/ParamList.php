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
        // @todo remove from code
        $this->templateList->set('aeskey','');
        $this->templateList->set('mailer_transport','');
        $this->templateList->set('mailer_port','');
        $this->templateList->set('mailer_host','');
        $this->templateList->set('mailer_user','');
        $this->templateList->set('mailer_password','');
        $this->templateList->set('mailer_encryption','');
        $this->templateList->set('sender_email','');
        $this->templateList->set('copy_email','');
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