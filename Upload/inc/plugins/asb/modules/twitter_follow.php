<?php
/**
 * @name  ASB Twitter Modules
 * @copyright  2011-2014 WildcardSearch
 *
 * this is an addon that adds a twitter follow button in a side box
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
function asb_twitter_follow_info()
{
	global $lang;

	if (!$lang->asb_twitter) {
		$lang->load('asb_twitter');
	}

	return array(
		"title" => $lang->asb_twitter_follow_title,
		"description" => $lang->asb_twitter_follow_description,
		"module_site" => 'https://github.com/Advanced-Sidebox/Twitter-Modules',
		"wrap_content" => true,
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
			"show_count" => array(
				"sid" => "NULL",
				"name" => 'show_count',
				"title" => $lang->asb_twitter_follow_show_count_title,
				"description" => $lang->asb_twitter_follow_show_count_description,
				"optionscode" => 'yesno',
				"value" => '1',
			),
			"show_screen_name" => array(
				"sid" => "NULL",
				"name" => 'show_screen_name',
				"title" => $lang->asb_twitter_follow_show_screen_name_title,
				"description" => $lang->asb_twitter_follow_show_screen_name_description,
				"optionscode" => 'yesno',
				"value" => '1',
			),
			"size" => array(
				"sid" => "NULL",
				"name" => 'size',
				"title" => $lang->asb_twitter_follow_size_title,
				"description" => $lang->asb_twitter_follow_size_description,
				"optionscode" => <<<EOF
select
0=default
1=Large
EOF
				,
				"value" => '0',
			),
		),
		"templates" => array(
			array(
				"title" => 'asb_twitter_follow',
				"template" => <<<EOF
				<tr>
					<td style="background: url({\$bannerImage})" class="trow1">
						<a class="twitter-follow-button" href="{\$link}"{\$data}>Follow @{\$username}</a>
					</td>
				</tr>
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
function asb_twitter_follow_build_template($args)
{
	extract($args);

	global $$template_var, $lang, $templates;

	if (!$lang->asb_twitter) {
		$lang->load('asb_twitter');
	}
	
	$username = $settings['twitter_user'];
	$link = "https://www.twitter.com/{$settings['twitter_user']}";

	$data = '';
	if ($settings['show_count']) {
		$data .= ' data-show-count="true"';
	} else {
		$data .= ' data-show-count="false"';
	}

	if ($settings['show_screen_name']) {
		$data .= ' data-show-screen-name="true"';
	} else {
		$data .= ' data-show-screen-name="false"';
	}

	if ($settings['size']) {
		$data .= ' data-size="large"';
	}

	eval("\${$template_var} = \"" . $templates->get('asb_twitter_follow') . "\";");

	// return true if your box has something to show, or false if it doesn't.
	return true;
}

?>
