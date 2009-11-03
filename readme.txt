=== SJ Hook Profiler ===
Contributors: vladimir_kolesnikov
Donate link: http://blog.sjinks.pro/feedback/
Tags: bbPress, optimization, performance, profile, speed
Requires at least: 2.8
Tested up to: 2.9
Stable tag: 0.2

Profiles how much time to execute every hook takes. Warning: For PHP 5 only.

== Description ==

WordPress and bbPress plugin developers use two functions to extend WordPress/bbPress functionalty.
These are `add_action()` and `add_filter()`. They are used to set up a *hook* which is triggered by some event.

The experience shows that hook execution takes signifacnt part of the page generation time.
When there are few queries but the page generation is high, this plugin comes to the rescue.

Unlike WP Tuner, which needs to specify the events to profile explicitly, SJ Hook Profiler
automatically detects *all* used hooks and sets up the handlers to measure their execution time.

By default the plugin is active only for the Administrator (WordPress) or Key Master (bbPress).
This behavior can be changed by setting up a handler for `enable_hook_profiler` filter.
The handler should return `true` if the hook profiler should be activated, or `false` otherwise.

== Installation ==

1. Upload `sj-hook-profiler` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. That's all :-)

== Frequently Asked Questions ==

None yet. Be the first to ask.

== Changelog ==

= 0.2 =
* Got rid of a PHP Warning in SjProfiler::end_profile().
* Improved performance a bit by using [PHP code optimization](http://blog.sjinks.pro/php/651-php-code-beauty-impacts-performance-part-2/ "PHP Code Beauty Impacts Performance")

= 0.1 =
* First public release

== Screenshots ==
1. Sample output
