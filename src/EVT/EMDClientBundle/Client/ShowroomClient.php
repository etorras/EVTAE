<?php
namespace EVT\EMDClientBundle\Client;

/**
 * Class ShowroomClient
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick*/
class ShowroomClient
{
    public function getById($id)
    {
        return ['id' => $id, 'name' => 'name', 'description' => 'desc'];
    }
}
