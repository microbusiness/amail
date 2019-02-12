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
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn=$conn;
        $this->params=new ParamList();
        $sql = "select data from system_params where code=:code ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('code','current');
        $stmt->execute();
        $data=false;
        while ($row = $stmt->fetch()) {
            try {
                $data=json_decode($row['data']);
            } catch (\Exception $e) {
                $data=[];
            }
        }
        if ($data) {
            foreach ($data as $key=>$val) {
                $this->params->setParam(['code'=>$key,'val'=>$val]);
            }
        }
    }

    public function get($code)
    {
        return $this->params->getParam($code);
    }

    public function set($key,$val) :void
    {
        $sql = "select id, data from system_params where code=:code ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('code','current');
        $stmt->execute();
        $dataJson=false;
        $id=false;
        while ($row = $stmt->fetch()) {
            $dataJson=$row['data'];
            $id=$row['id'];
        }
        if (false===$dataJson) {
            $paramList=[$key=>$val];
            $sql = "insert into system_params (code,data) values (:code,:data) ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue('code','current');
            $stmt->bindValue('data',json_encode($paramList));
            $stmt->execute();
        }
        else
        {
            try {
                $paramList=json_decode($dataJson,true);
                $paramList[$key]=$val;
            } catch (\Exception $e) {
                $paramList=[$key=>$val];
            }
            $sql = "update system_params set data=:data where id=:id ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue('id',$id);
            $stmt->bindValue('data',json_encode($paramList));
            $stmt->execute();
        }
    }
}