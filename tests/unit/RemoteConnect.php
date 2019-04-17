<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 17/04/2019
 * Time: 15:03
 */

namespace App\Tests\unit;


class RemoteConnect
{
    public function connectToServer($serverName=null)
    {
        if($serverName==null){
            throw new Exception('Thats not a server name!');
    }
    $fp = fsockopen($serverName,80);
    return ($fp) ? true : false;
  }

  public function returnSampleObject()
  {
    return $this;
  }
}
?>