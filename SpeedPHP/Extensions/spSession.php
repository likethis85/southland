<?php
/**
 * Session扩展类
 *
 */
class spSession {

        function  __construct() {
        	if(empty($_SESSION['user'])) {
                $_SESSION['user'] = spClass('spUser');
            }
        }

        function getUser() {
            return $_SESSION['user'];
        }

        /**
         * 支持以path形式访问Session值
         * @param string $key
         * @return mixed
         */
        function get($key = NULL) {
        		return $_SESSION[$key];
                //return $this->path_array($this->session, $key);
        }

        /**
         * 将数据存入SESSION, 支持path形式访问
         * @param string $key
         * @param mixed $value
         * @return bool
         */
        function put($key, $value) {
        		$_SESSION[$key] = $value;
        		/*
                $array =& $this->path_array($this->session, $key);
                $array = $value;
                return TRUE;
            */
        }

        /**
         * Path形式访问数组
         * @param minxed &$array
         * @param string $path
         * @return mixed
         */
        private function &path_array(&$array, $path = NULL) {
                if(empty($path) || !is_array($array)) {
                        return $array;
                }else{
                        $arr_path = explode('/', $path);
                        $path = NULL;
                        foreach($arr_path as $v){
                                $path .= '[\''.addslashes($v).'\']';
                        }
                        eval('$value =& $array'.$path.';');
                        return $value;
                }
        }
}
/* End of this file */
