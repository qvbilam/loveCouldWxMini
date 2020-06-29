<?php
/**
 * Created by PhpStorm.
 * User: qvbilam
 * Date: 2020-06-09
 * Time: 13:19
 */

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;
use EasySwoole\Component\Di;
use App\Common\Lib\CodeStatus;

class MysqlBase
{
    public $db;
    public $table;

    public function __construct()
    {
        /*判断有没有安装mysqli拓展*/
        if (!extension_loaded('mysqli')) {
            throw new \Exception(CodeStatus::getReasonPhrase(CodeStatus::MYSQL_LOADED_ERROR));
        }
        $db = Di::getInstance()->get("MYSQL");
        if ($db instanceof \MysqliDb) {
            $this->db = $db;
        } else {
            $this->db = new \MysqliDb(\EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL"));
        }
        if (!$this->db) {
            throw new \Exception(CodeStatus::getReasonPhrase(CodeStatus::MYSQL_CONNECT_ERROR));
        }
    }

    /*
     * 添加数据
     * */
    public function insert(array $data)
    {
        try {
            $data['create_time'] = time();
            $data['update_time'] = time();
            $res = $this->db->insert($this->table, $data);
        } catch (\Exception $e) {
            return false;
        }
        return $res;
    }

    /*
     * 批量添加数据
     * */
    public function insertMulti(array $data)
    {
        try {
            $res = $this->db->insertMulti($this->table, $data);
        } catch (\Exception $e) {
            return false;
        }
        return $res;
    }

    /*
     * 获取值
     * */
    public function getValue($conditon, $field, $limit = null, array $order = ['id', 'desc'])
    {
        $obj = $this->handleConditon($conditon);
        if ($obj == false) {
            return false;
        }
        if (!empty($order)) {
            $obj->orderBy(...$order);
        }
        try {
            if (empty($limit)) {
                $res = $obj->getValue($this->table, $field);
            } else {
                $res = $obj->getValue($this->table, $field, $limit);
            }
        } catch (\Exception $e) {
            return false;
        }
        return $res;
    }

    /*
     * 获取分页数据
     * */
    public function getPaginationByConditon($conditon, $field = '*', $pageLimit, $page = 1, array $order = ['id', 'desc'])
    {
        $obj = $this->handleConditon($conditon);
        if ($obj == false) {
            return false;
        }
        if (!empty($order)) {
            $obj->orderBy(...$order);
        }
        try {
            $obj->pageLimit = $pageLimit;
            $data = $obj->arrayBuilder()->paginate($this->table, $page, $field);
        } catch (\Exception $e) {
            echo $obj->getLastQuery() . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            throw new \Exception('获取数据失败');
        }
        return [
            'total_page' => $obj->totalPages,
            'page_size' => $pageLimit,
            'count' => count($data),
            'page' => $page,
            'list' => $data
        ];;
    }

    /*
     * 通过条件获取数据
     * */
    public function getByConditon($conditon, $field = '*', $num = null, array $order = ['id', 'desc'])
    {
        $obj = $this->handleConditon($conditon);
        if ($obj == false) {
            return false;
        }
        if (!empty($order)) {
            $obj->orderBy(...$order);
        }
        try {
            if ($num == 1) {
                $data = $obj->getOne($this->table, $field);
            } else {
                $data = $obj->get($this->table, $num, $field);
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return false;
        }
        return $data;
    }

    /*
     * 通过条件修改数据
     * */
    public function updateByConditon($conditon, $updateData)
    {
        $obj = $this->handleConditon($conditon);
        if ($obj == false) {
            return false;
        }
        try {
            $updateData['update_time'] = time();
            $res = $obj->update($this->table, $updateData);
        } catch (\Exception $e) {
            return false;
        }
        return $res;
    }

    /*
     * 处理条件
     * */
    protected function handleConditon($conditon)
    {
        $res = $this->db;
        if (empty($conditon)) {
            return $res;
        }
        foreach ($conditon as $k => $v) {
            if (!is_array($v)) {
                $res->where($k, $v);
            } else {
                $res->where($k, ...$v);
            }
        }
        return $res;
    }

}