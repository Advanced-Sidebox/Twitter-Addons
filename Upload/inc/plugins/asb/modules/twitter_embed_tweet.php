<?php
/**
 * @name  ASB Twitter Modules
 * @copyright  2011-2014 WildcardSearch
 *
 * this is an addon that displays an embedded tweet
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
function asb_twitter_embed_tweet_info()
{
	global $lang;

	if (!$lang->asb_twitter) {
		$lang->load('asb_twitter');
	}

	return array(
		"title" => $lang->asb_twitter_embed_tweet_title,
		"description" => $lang->asb_twitter_embed_tweet_description,
		"module_site" => 'https://github.com/Advanced-Sidebox/Twitter-Modules',
		"wrap_content" => false,
		"version" => '1',
		"compatibility" => '3.0',
		"settings" => array(
			"oauth_access_token" => array(
				"sid" => "NULL",
				"name" => 'oauth_access_token',
				"title" => $lang->asb_twitter_oauth_token_title,
				"description" => $lang->asb_twitter_oauth_token_description,
				"optionscode" => 'text',
				"value" => '',
			),
			"oauth_access_token_secret" => array(
				"sid" => "NULL",
				"name" => 'oauth_access_token_secret',
				"title" => $lang->asb_twitter_oauth_secret_title,
				"description" => $lang->asb_twitter_oauth_secret_description,
				"optionscode" => 'text',
				"value" => '',
			),
			"consumer_key" => array(
				"sid" => "NULL",
				"name" => 'consumer_key',
				"title" => $lang->asb_twitter_consumer_key_title,
				"description" => $lang->asb_twitter_consumer_key_description,
				"optionscode" => 'text',
				"value" => '',
			),
			"consumer_secret" => array(
				"sid" => "NULL",
				"name" => 'consumer_secret',
				"title" => $lang->asb_twitter_consumer_secret_title,
				"description" => $lang->asb_twitter_consumer_secret_description,
				"optionscode" => 'text',
				"value" => '',
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
			"hide_media" => array(
				"sid" => "NULL",
				"name" => 'hide_media',
				"title" => $lang->asb_twitter_hide_media_title,
				"description" => $lang->asb_twitter_hide_media_description,
				"optionscode" => 'yesno',
				"value" => '0',
			),
			"hide_thread" => array(
				"sid" => "NULL",
				"name" => 'hide_thread',
				"title" => $lang->asb_twitter_hide_thread_title,
				"description" => $lang->asb_twitter_hide_thread_description,
				"optionscode" => 'yesno',
				"value" => '1',
			),
			"status_url" => array(
				"sid" => "NULL",
				"name" => 'status_url',
				"title" => $lang->asb_twitter_status_url_title,
				"description" => $lang->asb_twitter_status_url_description,
				"optionscode" => 'text',
				"value" => '',
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
function asb_twitter_embed_tweet_build_template($args)
{
	extract($args);

	global $$template_var, $lang;

	if (!$lang->asb_twitter) {
		$lang->load('asb_twitter');
	}
	
	$authSettings = array(
		'oauth_access_token' => $settings['oauth_access_token'],
		'oauth_access_token_secret' => $settings['oauth_access_token_secret'],
		'consumer_key' => $settings['consumer_key'],
		'consumer_secret' => $settings['consumer_secret'],
	);

	$linkColor = '';
	if ($settings['link_color']) {
		$linkColor = "&link_color={$settings['link_color']}";
	}

	$hideMedia = '';
	if ($settings['hide_media']) {
		$hideMedia = '&hide_media=true';
	}

	$hideThread = '';
	if ($settings['hide_thread']) {
		$hideThread = '&hide_thread=true';
	}

	$url = 'https://publish.twitter.com/oembed';
	$getfield = "?url={$settings['status_url']}&maxwidth={$width}&theme={$settings['theme']}&omit_script=true{$linkColor}{$hideMedia}{$hideThread}";
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($authSettings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();

	$responseArray = json_decode($response, true);

	$$template_var = $responseArray['html'] . <<<EOF
<style>
iframe.twitter-tweet {
	margin-top: -5px !important;
}
</style>
EOF;

	// return true if your box has something to show, or false if it doesn't.
	return true;
}

?>
