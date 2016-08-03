<?php
    set_time_limit(0);
    /** USER NAME AND PASSWORD */
    /** MYSQL */
    define('DB_HOST', 'localhost');
    define('DB_USER', 'getmezej_tcyayc');    // DB username
    define('DB_PASSWORD', 'themeforest853');    // DB password
    define('DB_NAME', 'getmezej_getmecvdb');      // DB name
    $DEBUG_TAG = "API debug";
    
    /*
     * Error logging function
     */
    $CURR_PATH=dirname(__FILE__);
    $CURR_DATE=date("Ymd");
    $LOG_FILE=$CURR_PATH . DIRECTORY_SEPARATOR . "log/{$CURR_DATE}.log";
    function msg_log($msg) {
    global $LOG_FILE;
    if (!is_dir(dirname($LOG_FILE))) {
        // dir doesn't exist, make it
        mkdir(dirname($LOG_FILE));
        }
        file_put_contents($LOG_FILE, (date("Y-m-d H:i:s")."\t\t".$msg."\r\n"), FILE_APPEND);       
    }

    /*
     * hack logging function
     */
    $CURR_PATH=dirname(__FILE__);
    $CURR_DATE=date("Ymd");
    $HACK_FILE=$CURR_PATH . DIRECTORY_SEPARATOR . "hack/{$CURR_DATE}.log";

    function getForwardIp()
    {
        if (preg_match( "/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", getenv('HTTP_X_FORWARDED_FOR'))){
            return getenv('HTTP_X_FORWARDED_FOR');
        }
        return getenv('REMOTE_ADDR');
    }
    function getRemoteIp()
    {return getenv('REMOTE_ADDR');}

     
?>