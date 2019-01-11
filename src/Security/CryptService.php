<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 14.10.2014
 * Time: 0:55
 */

namespace App\Security;

use App\Security\AES128;
use App\Service\ParamService;

class CryptService {

    private $key;
    private $handler;

    public function __construct(ParamService $paramService)
    {
        $this->handler=new AES128();
        $this->key=$paramService->get('aeskey');
    }

    public function ec($block)
    {
        $key = $this->handler->makeKey($this->key);
        $newBlock=str_replace('^','~',$block);
        $len=strlen($newBlock);
        $blockPart='';
        $fullCipherBlock='';
        for ($i=0;$i<$len;$i++)
        {
            $blockPart.=$newBlock[$i];
            if (strlen($blockPart)==16)
            {
                $fullCipherBlock.=$this->handler->blockEncrypt($blockPart,$key);
                $blockPart='';
            }
        }
        if (strlen($blockPart)<16)
        {
            $lenLeft=16-strlen($blockPart);
            $addStr='';
            for ($i=0;$i<$lenLeft;$i++){$addStr.='^';}
            $blockPart.=$addStr;
            $fullCipherBlock.=$this->handler->blockEncrypt($blockPart,$key);
        }
        return $this->handler->toHexString($fullCipherBlock);
    }

    public function dc($block)
    {
        $key = $this->handler->makeKey($this->key);
        $newBlock=$this->handler->fromHexString($block);
        $len=strlen($newBlock);
        $blockPart='';
        $fullCipherBlock='';
        for ($i=0;$i<$len;$i++)
        {
            $blockPart.=$newBlock[$i];
            if (strlen($blockPart)==16)
            {
                $fullCipherBlock.=$this->handler->blockDecrypt($blockPart,$key);
                $blockPart='';
            }
        }
        $fullCipherBlock=str_replace('^','',$fullCipherBlock);

        return $fullCipherBlock;
    }

    public function generateRandomString(){
        $arr = array('a','b','c','d','e','f',

            'g','h','i','j','k','l',

            'm','n','o','p','r','s',

            't','u','v','x','y','z',

            '1','2','3','4','5','6',

            '7','8','9','0');
        $result = "";
        for($i = 0; $i < $this->shortlinkLength; $i++)
        {
            // Вычисляем случайный индекс массива
            $index = rand(0, count($arr) - 1);
            $result .= $arr[$index];
        }
        return $result;
    }

} 