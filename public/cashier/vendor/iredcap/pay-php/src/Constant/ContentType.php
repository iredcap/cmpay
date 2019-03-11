<?php
/**
 *  +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2018 http://www.iredcap.cn All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 *  +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 *  +----------------------------------------------------------------------
 */
namespace IredCap\Pay\Constant;


/**
 * 常用HTTP Content-Type常量
 */
class ContentType {
    //表单类型Content-Type
	const CONTENT_TYPE_FORM = "application/x-www-form-urlencoded; charset=UTF-8";
    // 流类型Content-Type
    const CONTENT_TYPE_STREAM = "application/octet-stream; charset=UTF-8";
    //JSON类型Content-Type
    const CONTENT_TYPE_JSON = "application/json; charset=UTF-8";
    //XML类型Content-Type
    const CONTENT_TYPE_XML = "application/xml; charset=UTF-8";
    //文本类型Content-Type
    const CONTENT_TYPE_TEXT = "application/text; charset=UTF-8";
}
