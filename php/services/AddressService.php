<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/22
 * Time: 20:59
 */

/**
 * 地址服务
 */
namespace app\services;

use app\base\Service;
use app\models\UserAddress;

class AddressService extends Service
{

    /**
     * 获取用户地址列表
     *
     * @param $userId
     * @return array
     */
    public function getAddressList($userId)
    {
        if (empty($addressId)) {
            $this->fail('用户ID不能为空');
        }

        $list = (new UserAddress())->getList(['user_id' => $userId]);
        empty($list) && $list = [];

        return $this->format(['list' => $list]);
    }

    /**
     * 通过address_id获取地址信息
     *
     * @param $addressId
     * @return array
     */
    public function getAddress($addressId)
    {
        if (empty($addressId)) {
            $this->fail('地址ID不存在');
        }

        $addressInfo = (new UserAddress())->getOne(['address_id' => $addressId]);
        if (empty($addressInfo)) {
            $this->fail('地址不存在');
        }

        return $this->format($addressInfo);
    }

    /**
     * 填加地址
     *
     * @param array $data
     * @return array
     */
    public function addAddress($data = [])
    {
        if (empty($data['user_id'])) {
            return $this->fail('用户ID不可为空');
        }
        //一个用户最多添加5个地址
        $userAddressModel = new UserAddress();
        if (($addressCount = $userAddressModel->getOne(['user_id' => $data['user_id'], 'select' => 'count(1) as count'])) && $addressCount['count'] >= 5) {
            return $this->fail('一个用户最多添加5个地址');
        }
        unset($data['address_id']);
        $result = $userAddressModel->saveData($data, true);

        return $this->format($result);
    }

    /**
     * 编辑用户地址
     *
     * @param $addressId
     * @param array $data
     * @return array
     */
    public function editAddress($addressId, $data = [])
    {
        if (empty($addressId)) {
            return $this->fail('地址ID不存在');
        }

        $addressInfo = (new UserAddress())->getOne(['address_id' => $addressId, 'select' => 'address_id']);
        if (empty($addressInfo)) {
            $this->fail('地址不存在');
        }

        $data['address_id'] = $addressId;
        $result = (new UserAddress())->saveData($data);

        return $this->format($result);
    }

}