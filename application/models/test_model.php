<?php

class test_model extends CI_Model
{
    private $db;
    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default',true);
    }

    /**
     * @name 订单信息
     * @param string $orderId
     * @date 2016-11-18
     * @return array $data
     */
    function orders ($start,$end) {
        $sql = <<<EOF
	SELECT
        a.`order_id`,
        a.`user_id`,
        a.`consignee`,
        a.`address`,
        a.`shipping_name`,
        a.`referer`,
        b.`goods_name`
    FROM
        `ecs_order_info` AS a
    LEFT JOIN `ecs_order_goods` AS b ON b.`order_id` = a.`order_id`
    ORDER BY
        a.`order_id` DESC
    LIMIT $start,$end
EOF;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }
}
