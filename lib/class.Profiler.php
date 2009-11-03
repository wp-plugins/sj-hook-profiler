<?php

    final class SjProfilerHelper
    {
        public $profile = array();

        public static function instance()
        {
            static $self = false;
            if (false === $self) {
                $self = new SjProfilerHelper();
            }

            return $self;
        }

        private function __construct()
        {
        }

        private static function microtime()
        {
            $mtime = explode(' ', microtime() );
            $mtime = $mtime[1] + $mtime[0];
            return $mtime;
        }

        public function start($tag)
        {
            $this->profile[$tag][] = self::microtime();
        }

        public function end($tag)
        {
            $cnt = count($this->profile[$tag])-1;
            $this->profile[$tag][$cnt] = self::microtime() - $this->profile[$tag][$cnt];
        }
    }

    class SjProfiler
    {
        public static function instance()
        {
            static $self = false;
            if (false === $self) {
                $self = new SjProfiler();
            }

            return $self;
        }

        private function __construct()
        {
            add_action('all', array(&$this, 'start_profile'), -10000000);
        }

        public function start_profile($what)
        {
            global $wp_filter;
            $tag = (true == empty($what)) ? current_filter() : $what;

            if (false == empty($wp_filter[$tag])) {
                if (true == empty($wp_filter[$tag][10000000]['sj_profile_hook'])) {
                    $wp_filter[$tag][10000000]['sj_profile_hook'] = array(
                        'function'      => array(&$this, 'end_profile'),
                        'accepted_args' => 1,
                    );
                }

                SjProfilerHelper::instance()->start($tag);
            }
        }

        public function end_profile($arg, $what = null)
        {
            $tag = (true == empty($what)) ? current_filter() : $what;
            SjProfilerHelper::instance()->end($tag);
            return $arg;
        }
    }

?>