<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/22
 * Time: 17:21
 */
namespace app\common ;

/**
 * Class We7
 * @package app\common
 * @property-read string $role 角色
 * @property-read string $siteroot 网站根路径
 * @property-read array $config 配置文件
 * @property-read array $account 公众号
 */

class We7 implements \ArrayAccess {
    protected $arr;
    public function __construct($we7)
    {
        $this->arr=$we7;
    }

    public function __get($name)
    {
        return $this->arr[$name];
    }
    public function get($name, $default = null)
    {
        $parts = explode('.', $name);
        $cur=&$this->arr;
        foreach ($parts as $name){
            if(!isset($cur[$name])) return $default;
            $cur=&$cur[$name];
        }

        return $cur;
    }
    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->arr[$offset];
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}