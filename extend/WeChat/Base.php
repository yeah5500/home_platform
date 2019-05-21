<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/8/18
 * Time: 11:29
 */
namespace WeChat;
abstract class Base{
    protected $WeChat;

    public function __construct($WeChat)
    {
        $this->WeChat = $WeChat;
    }

}