<?php
/*
Plugin Name: SJ Hook Profiler
Description: Profiles how much time to execute every hook takes.
Version: 0.2
Author: Vladimir Kolesnikov
Plugin URI: http://blog.sjinks.pro/wordpress-plugins/sj-hook-profiler/
Author URI: http://blog.sjinks.pro/
*/

if (!class_exists("SjProfilerHelper")) :

	require_once(dirname(__FILE__) . '/lib/class.Profiler.php');

endif;

if (!class_exists("SjHookProfiler")) :

	final class SjHookProfiler
	{
		static $started;

		public static function instance()
		{
			static $self = false;
			if (false === $self) {
				$self = new SjHookProfiler();
			}

			return $self;
		}

		private function __construct()
		{
			self::$started = time();
			SjProfiler::instance();
			add_action('init',    array($this, 'init'));
			add_action('bb_init', array($this, 'bb_init'));
		}

		private static function unregisterHook()
		{
			remove_action('all', array(SjHookProfiler::instance(), 'start_profile'), -10000000);
		}

		public function init()
		{
			$allow = apply_filters('enable_hook_profiler', current_user_can('administrator'));
			if ($allow) {
				wp_enqueue_style('hook-profiler-css', plugins_url('sj-hook-profiler/profiler.css'));

				add_action('wp_footer',    array($this, 'footer'), 10000000 + 1);
				add_action('admin_footer', array($this, 'footer'), 10000000 + 1);
			}
			else {
				self::unregisterHook();
			}
		}

		public function bb_init()
		{
			$allow = apply_filters('enable_hook_profiler', bb_current_user_can('use_keys'));
			if ($allow) {
				wp_enqueue_style('hook-profiler-css', BB_PLUGIN_URL . '/sj-hook-profiler/profiler.css');

				add_action('bb_foot',         array($this, 'footer'), 10000000 + 1);
				add_action('bb_admin_footer', array($this, 'footer'), 10000000 + 1);
			}
			else {
				self::unregisterHook();
			}
		}

		public function footer()
		{
			$profile = &SjProfilerHelper::instance()->profile;
			if (!empty($profile)) {
				echo '<table class="hookdebug"><thead><tr><th>Hook Name</th><th>Total Time</th><th>Invocations</th><th>Average Time</th></tr></thead><tbody>';
				foreach ($profile as $tag => &$val) {
					$cnt = count($val);
					if ($cnt) {
						if ($val[$cnt-1] > self::$started) {
							SjProfilerHelper::instance()->end($tag);
						}
					}

					$sum = array_sum($val);
					$avg = ($cnt < 2) ? $sum : $sum/$cnt;
					echo '<tr><td>', esc_attr($tag), '</td><td>', number_format($sum, 6), '</td><td>', number_format($cnt), '</td><td>', number_format($avg, 6), '</td></tr>';
				}

				unset($val);
				echo '</tbody></table>';
			}
		}
	}

endif;

	SjHookProfiler::instance();
?>