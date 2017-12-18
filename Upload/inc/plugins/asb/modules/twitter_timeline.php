<?php
/**
 * @name  ASB Twitter Modules
 * @copyright  2011-2014 WildcardSearch
 *
 * this is an addon that embeds a twitter timeline in the sidebar
 */

// disallow direct access
if (!defined('IN_MYBB') ||
	!defined('IN_ASB')) {
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

/**
 * provide info to ASB about the addon
 *
 * @return array module info
 */
function asb_twitter_timeline_info()
{
	global $lang;

	if (!$lang->asb_twitter) {
		$lang->load('asb_twitter');
	}

	return array(
		"title" => $lang->asb_twitter_timeline_title,
		"description" => $lang->asb_twitter_timeline_description,
		"module_site" => 'https://github.com/Advanced-Sidebox/Twitter-Modules',
		"wrap_content" => false,
		"version" => '1',
		"compatibility" => '3.0',
		"settings" => array(
			"twitter_user" => array(
				"sid" => "NULL",
				"name" => 'twitter_user',
				"title" => $lang->asb_twitter_twitter_user_title,
				"description" => $lang->asb_twitter_twitter_user_description,
				"optionscode" => 'text',
				"value" => '',
			),
			"show_replies" => array(
				"sid" => "NULL",
				"name" => 'show_replies',
				"title" => $lang->asb_twitter_timeline_show_replies_title,
				"description" => $lang->asb_twitter_timeline_show_replies_description,
				"optionscode" => 'yesno',
				"value" => '0',
			),
			"theme" => array(
				"sid" => "NULL",
				"name" => 'theme',
				"title" => $lang->asb_twitter_theme_title,
				"description" => $lang->asb_twitter_theme_description,
				"optionscode" => <<<EOF
select
light=Light
dark=Dark
EOF
				,
				"value" => 'light',
			),
			"link_color" => array(
				"sid" => "NULL",
				"name" => 'link_color',
				"title" => $lang->asb_twitter_latest_status_link_color_title,
				"description" => $lang->asb_twitter_latest_status_link_color_description,
				"optionscode" => 'text',
				"value" => '',
			),
			"border_color" => array(
				"sid" => "NULL",
				"name" => 'border_color',
				"title" => $lang->asb_twitter_timeline_border_color_title,
				"description" => $lang->asb_twitter_timeline_border_color_description,
				"optionscode" => 'text',
				"value" => '',
			),
			"tweet_limit" => array(
				"sid" => "NULL",
				"name" => 'tweet_limit',
				"title" => $lang->asb_twitter_timeline_tweet_limit_title,
				"description" => $lang->asb_twitter_timeline_tweet_limit_description,
				"optionscode" => 'text',
				"value" => '0',
			),
			"max_height" => array(
				"sid" => "NULL",
				"name" => 'max_height',
				"title" => $lang->asb_twitter_timeline_max_height_title,
				"description" => $lang->asb_twitter_timeline_max_height_description,
				"optionscode" => 'text',
				"value" => '0',
			),
		),
		"templates" => array(
			array(
				"title" => 'asb_twitter_timeline',
				"template" => <<<EOF
<a class="twitter-timeline" href="{\$link}"{\$data}>Twwets by @{\$username}</a>
EOF
			),
		),
		"scripts" => array(
			'Twitter/widgets',
		),
	);
}

/**
 * handles display of children of this addon at page load
 *
 * @param  array info from child box
 * @return bool success/fail
 */
function asb_twitter_timeline_build_template($args)
{
	extract($args);

	global $$template_var, $lang, $templates;

	if (!$lang->asb_twitter) {
		$lang->load('asb_twitter');
	}
	
	$username = $settings['twitter_user'];
	$link = "https://www.twitter.com/{$settings['twitter_user']}";

	$data = " data-theme=\"{$settings['theme']}\" data-width=\"{$width}\"";
	if ($settings['show_replies']) {
		$data .= ' data-show-replies="true"';
	} else {
		$data .= ' data-show-replies="false"';
	}

	if ($settings['tweet_limit']) {
		$data .= " data-tweet-limit=\"{$settings['tweet_limit']}\"";
	}

	if ($settings['link_color']) {
		$data .= " data-link-color=\"{$settings['link_color']}\"";
	}

	if ($settings['border_color']) {
		$data .= " data-border-color=\"{$settings['border_color']}\"";
	}

	if ($settings['max_height']) {
		$data .= " data-height=\"{$settings['max_height']}\"";
	}

	eval("\${$template_var} = \"" . $templates->get('asb_twitter_timeline') . "\";");

	// return true if your box has something to show, or false if it doesn't.
	return true;
}

?>
