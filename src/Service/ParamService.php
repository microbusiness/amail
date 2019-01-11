<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.01.2019
 * Time: 11:52
 */

namespace App\Service;


use App\Document\ParamList;
use Doctrine\DBAL\Connection;

class ParamService
{
    /**
     * @var \App\Document\ParamList
     */
    protected $params;

    public function __construct(Connection $conn)
    {
        $this->params=new ParamList();
        $sql = "select * from system_params ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $this->params->setParam($row);
        }
    }

    public function get($code)
    {
        return $this->params->getParam($code);
    }
}