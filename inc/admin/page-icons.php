<?php
/**
 * Admin: Page Icon meta (registration, UI, save)
 *
 * @package LetLexi\Toolkit\Admin
 * @since 1.2.0
 */

namespace LetLexi\Toolkit\Admin;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register page icon meta (REST-exposed for compatibility)
 */
add_action( 'init', function () {
	register_post_meta( 'page', '_page_icon_attachment_id', array(
		'type'          => 'integer',
		'single'        => true,
		'show_in_rest'  => true,
		'auth_callback' => function() { return current_user_can( 'edit_pages' ); },
	) );

	register_post_meta( 'page', '_page_icon_library', array(
		'type'          => 'string',
		'single'        => true,
		'show_in_rest'  => true,
		'auth_callback' => function() { return current_user_can( 'edit_pages' ); },
	) );

	register_post_meta( 'page', '_page_icon_type', array(
		'type'          => 'string',
		'single'        => true,
		'show_in_rest'  => true,
		'auth_callback' => function() { return current_user_can( 'edit_pages' ); },
	) );
} );

/**
 * Admin assets (only enqueue on page edit screens)
 */
add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style( 'dashicons' );
	// Primary FA source for the picker preview.
	if ( ! wp_style_is( 'letlexi-fa', 'registered' ) ) {
		wp_register_style( 'letlexi-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
	}
	wp_enqueue_style( 'letlexi-fa' );
} );

/**
 * Get FontAwesome icon classes dynamically
 *
 * Since we're using the CDN as the source of truth, we provide
 * a curated list of commonly used icons for the picker.
 */
function letlexi_get_fontawesome_icons() {
	return array(
		'fa-solid' => array(
			'star' => 'fas fa-star',
			'heart' => 'fas fa-heart',
			'home' => 'fas fa-home',
			'user' => 'fas fa-user',
			'cog' => 'fas fa-cog',
			'search' => 'fas fa-search',
			'phone' => 'fas fa-phone',
			'envelope' => 'fas fa-envelope',
			'calendar' => 'fas fa-calendar-days',
			'clock' => 'fas fa-clock',
			'check' => 'fas fa-check',
			'times' => 'fas fa-times',
			'plus' => 'fas fa-plus',
			'minus' => 'fas fa-minus',
			'arrow-right' => 'fas fa-arrow-right',
			'arrow-left' => 'fas fa-arrow-left',
			'arrow-up' => 'fas fa-arrow-up',
			'arrow-down' => 'fas fa-arrow-down',
			'chevron-right' => 'fas fa-chevron-right',
			'chevron-left' => 'fas fa-chevron-left',
			'chevron-up' => 'fas fa-chevron-up',
			'chevron-down' => 'fas fa-chevron-down',
			'play' => 'fas fa-play',
			'pause' => 'fas fa-pause',
			'stop' => 'fas fa-stop',
			'volume-up' => 'fas fa-volume-up',
			'volume-down' => 'fas fa-volume-down',
			'volume-mute' => 'fas fa-volume-mute',
			'image' => 'fas fa-image',
			'video' => 'fas fa-video',
			'music' => 'fas fa-music',
			'file' => 'fas fa-file',
			'folder' => 'fas fa-folder',
			'download' => 'fas fa-download',
			'upload' => 'fas fa-upload',
			'link' => 'fas fa-link',
			'unlink' => 'fas fa-unlink',
			'edit' => 'fas fa-edit',
			'trash' => 'fas fa-trash',
			'save' => 'fas fa-save',
			'print' => 'fas fa-print',
			'share' => 'fas fa-share',
			'like' => 'fas fa-thumbs-up',
			'dislike' => 'fas fa-thumbs-down',
			'eye' => 'fas fa-eye',
			'eye-slash' => 'fas fa-eye-slash',
			'lock' => 'fas fa-lock',
			'unlock' => 'fas fa-unlock',
			'key' => 'fas fa-key',
			'wifi' => 'fas fa-wifi',
			'signal' => 'fas fa-signal',
			'battery-full' => 'fas fa-battery-full',
			'battery-half' => 'fas fa-battery-half',
			'battery-empty' => 'fas fa-battery-empty',
			'plug' => 'fas fa-plug',
			'bolt' => 'fas fa-bolt',
			'fire' => 'fas fa-fire',
			'water' => 'fas fa-tint',
			'leaf' => 'fas fa-leaf',
			'seedling' => 'fas fa-seedling',
			'tree' => 'fas fa-tree',
			'cloud' => 'fas fa-cloud',
			'sun' => 'fas fa-sun',
			'moon' => 'fas fa-moon',
			'star-half' => 'fas fa-star-half',
			'wrench' => 'fas fa-wrench',
			'hammer' => 'fas fa-hammer',
			'screwdriver' => 'fas fa-screwdriver',
			'tools' => 'fas fa-tools',
			'cogs' => 'fas fa-cogs',
			'gear' => 'fas fa-cog',
			'gears' => 'fas fa-cogs',
			'settings' => 'fas fa-cog',
			'config' => 'fas fa-cog',
			'preferences' => 'fas fa-cog',
			'options' => 'fas fa-cog',
			'admin' => 'fas fa-user-cog',
			'user-cog' => 'fas fa-user-cog',
			'user-edit' => 'fas fa-user-edit',
			'user-plus' => 'fas fa-user-plus',
			'user-minus' => 'fas fa-user-minus',
			'user-times' => 'fas fa-user-times',
			'user-check' => 'fas fa-user-check',
			'user-clock' => 'fas fa-user-clock',
			'user-graduate' => 'fas fa-user-graduate',
			'user-tie' => 'fas fa-user-tie',
			'user-ninja' => 'fas fa-user-ninja',
			'user-secret' => 'fas fa-user-secret',
			'user-shield' => 'fas fa-user-shield',
			'user-slash' => 'fas fa-user-slash',
			'user-tag' => 'fas fa-user-tag',
			'user-tags' => 'fas fa-user-tags',
			'users' => 'fas fa-users',
			'users-cog' => 'fas fa-users-cog',
			'user-friends' => 'fas fa-user-friends',
			'rocket' => 'fas fa-rocket',
			'plane' => 'fas fa-plane',
			'car' => 'fas fa-car',
			'train' => 'fas fa-train',
			'ship' => 'fas fa-ship',
			'bicycle' => 'fas fa-bicycle',
			'motorcycle' => 'fas fa-motorcycle',
			'bus' => 'fas fa-bus',
			'truck' => 'fas fa-truck',
			'ambulance' => 'fas fa-ambulance',
			'fire-truck' => 'fas fa-truck',
			'police-car' => 'fas fa-car',
			'taxi' => 'fas fa-taxi',
			'helicopter' => 'fas fa-helicopter',
			'space-shuttle' => 'fas fa-space-shuttle',
			'satellite' => 'fas fa-satellite',
			'globe' => 'fas fa-globe',
			'map' => 'fas fa-map',
			'map-marker' => 'fas fa-map-marker-alt',
			'compass' => 'fas fa-compass',
			'location-arrow' => 'fas fa-location-arrow',
			'flag' => 'fas fa-flag',
			'flag-checkered' => 'fas fa-flag-checkered',
			'flag-usa' => 'fas fa-flag-usa',
			'building' => 'fas fa-building',
			'industry' => 'fas fa-industry',
			'warehouse' => 'fas fa-warehouse',
			'store' => 'fas fa-store',
			'shopping-cart' => 'fas fa-shopping-cart',
			'shopping-bag' => 'fas fa-shopping-bag',
			'credit-card' => 'fas fa-credit-card',
			'money-bill' => 'fas fa-money-bill',
			'coins' => 'fas fa-coins',
			'piggy-bank' => 'fas fa-piggy-bank',
			'chart-line' => 'fas fa-chart-line',
			'chart-bar' => 'fas fa-chart-bar',
			'chart-pie' => 'fas fa-chart-pie',
			'chart-area' => 'fas fa-chart-area',
			'calculator' => 'fas fa-calculator',
			'percent' => 'fas fa-percent',
			'hashtag' => 'fas fa-hashtag',
			'at' => 'fas fa-at',
			'copyright' => 'fas fa-copyright',
			'trademark' => 'fas fa-trademark',
			'registered' => 'fas fa-registered',
			'certificate' => 'fas fa-certificate',
			'award' => 'fas fa-award',
			'trophy' => 'fas fa-trophy',
			'medal' => 'fas fa-medal',
			'ribbon' => 'fas fa-ribbon',
			'badge' => 'fas fa-badge',
			'crown' => 'fas fa-crown',
			'gem' => 'fas fa-gem',
			'diamond' => 'fas fa-gem',
			'ring' => 'fas fa-ring',
			'gift' => 'fas fa-gift',
			'birthday-cake' => 'fas fa-birthday-cake',
			'cake' => 'fas fa-birthday-cake',
			'cookie' => 'fas fa-cookie',
			'ice-cream' => 'fas fa-ice-cream',
			'pizza-slice' => 'fas fa-pizza-slice',
			'hamburger' => 'fas fa-hamburger',
			'hotdog' => 'fas fa-hotdog',
			'taco' => 'fas fa-taco',
			'utensils' => 'fas fa-utensils',
			'coffee' => 'fas fa-coffee',
			'wine-glass' => 'fas fa-wine-glass',
			'beer' => 'fas fa-beer',
			'cocktail' => 'fas fa-cocktail',
			'glass-martini' => 'fas fa-glass-martini',
			'wine-bottle' => 'fas fa-wine-bottle',
			'wine-glass-alt' => 'fas fa-wine-glass-alt',
			'wine-glass-empty' => 'fas fa-wine-glass-empty',
			'wine-bottle-alt' => 'fas fa-wine-bottle-alt',
			'wine-bottle-empty' => 'fas fa-wine-bottle-empty',
			'wine-glass-full' => 'fas fa-wine-glass',
			'wine-glass-half' => 'fas fa-wine-glass-alt',
			'wine-glass-empty-alt' => 'fas fa-wine-glass-empty',
			'wine-bottle-full' => 'fas fa-wine-bottle',
			'wine-bottle-half' => 'fas fa-wine-bottle-alt',
			'wine-bottle-empty-alt' => 'fas fa-wine-bottle-empty',
		),
		'fa-regular' => array(
			'star' => 'far fa-star',
			'heart' => 'far fa-heart',
			'user' => 'far fa-user',
			'calendar' => 'far fa-calendar',
			'clock' => 'far fa-clock',
			'eye' => 'far fa-eye',
			'eye-slash' => 'far fa-eye-slash',
			'lock' => 'far fa-lock',
			'unlock' => 'far fa-unlock',
			'file' => 'far fa-file',
			'folder' => 'far fa-folder',
			'image' => 'far fa-image',
			'video' => 'far fa-video',
			'play-circle' => 'far fa-play-circle',
			'pause-circle' => 'far fa-pause-circle',
			'stop-circle' => 'far fa-stop-circle',
			'edit' => 'far fa-edit',
			'trash-alt' => 'far fa-trash-alt',
			'save' => 'far fa-save',
			'print' => 'far fa-print',
			'share-square' => 'far fa-share-square',
			'thumbs-up' => 'far fa-thumbs-up',
			'thumbs-down' => 'far fa-thumbs-down',
			'link' => 'far fa-link',
			'unlink' => 'far fa-unlink',
			'plus-square' => 'far fa-plus-square',
			'minus-square' => 'far fa-minus-square',
			'check-square' => 'far fa-check-square',
			'times-circle' => 'far fa-times-circle',
			'question-circle' => 'far fa-question-circle',
			'info-circle' => 'far fa-info-circle',
			'exclamation-circle' => 'far fa-exclamation-circle',
			'exclamation-triangle' => 'far fa-exclamation-triangle',
			'comment' => 'far fa-comment',
			'comments' => 'far fa-comments',
			'envelope' => 'far fa-envelope',
			'phone' => 'far fa-phone',
			'search' => 'far fa-search',
			'home' => 'far fa-home',
			'cog' => 'far fa-cog',
			'wrench' => 'far fa-wrench',
			'hammer' => 'far fa-hammer',
			'screwdriver' => 'far fa-screwdriver',
			'tools' => 'far fa-tools',
			'cogs' => 'far fa-cogs',
			'gear' => 'far fa-cog',
			'gears' => 'far fa-cogs',
			'settings' => 'far fa-cog',
			'config' => 'far fa-cog',
			'preferences' => 'far fa-cog',
			'options' => 'far fa-cog',
			'admin' => 'far fa-user-cog',
			'user-cog' => 'far fa-user-cog',
			'user-edit' => 'far fa-user-edit',
			'user-plus' => 'far fa-user-plus',
			'user-minus' => 'far fa-user-minus',
			'user-times' => 'far fa-user-times',
			'user-check' => 'far fa-user-check',
			'user-clock' => 'far fa-user-clock',
			'user-graduate' => 'far fa-user-graduate',
			'user-tie' => 'far fa-user-tie',
			'user-ninja' => 'far fa-user-ninja',
			'user-secret' => 'far fa-user-secret',
			'user-shield' => 'far fa-user-shield',
			'user-slash' => 'far fa-user-slash',
			'user-tag' => 'far fa-user-tag',
			'user-tags' => 'far fa-user-tags',
			'users' => 'far fa-users',
			'users-cog' => 'far fa-users-cog',
			'user-friends' => 'far fa-user-friends',
			'rocket' => 'far fa-rocket',
			'plane' => 'far fa-plane',
			'car' => 'far fa-car',
			'train' => 'far fa-train',
			'ship' => 'far fa-ship',
			'bicycle' => 'far fa-bicycle',
			'motorcycle' => 'far fa-motorcycle',
			'bus' => 'far fa-bus',
			'truck' => 'far fa-truck',
			'ambulance' => 'far fa-ambulance',
			'fire-truck' => 'far fa-truck',
			'police-car' => 'far fa-car',
			'taxi' => 'far fa-taxi',
			'helicopter' => 'far fa-helicopter',
			'space-shuttle' => 'far fa-space-shuttle',
			'satellite' => 'far fa-satellite',
			'globe' => 'far fa-globe',
			'map' => 'far fa-map',
			'map-marker' => 'far fa-map-marker-alt',
			'compass' => 'far fa-compass',
			'location-arrow' => 'far fa-location-arrow',
			'flag' => 'far fa-flag',
			'flag-checkered' => 'far fa-flag-checkered',
			'flag-usa' => 'far fa-flag-usa',
			'building' => 'far fa-building',
			'industry' => 'far fa-industry',
			'warehouse' => 'far fa-warehouse',
			'store' => 'far fa-store',
			'shopping-cart' => 'far fa-shopping-cart',
			'shopping-bag' => 'far fa-shopping-bag',
			'credit-card' => 'far fa-credit-card',
			'money-bill' => 'far fa-money-bill',
			'coins' => 'far fa-coins',
			'piggy-bank' => 'far fa-piggy-bank',
			'chart-line' => 'far fa-chart-line',
			'chart-bar' => 'far fa-chart-bar',
			'chart-pie' => 'far fa-chart-pie',
			'chart-area' => 'far fa-chart-area',
			'calculator' => 'far fa-calculator',
			'percent' => 'far fa-percent',
			'hashtag' => 'far fa-hashtag',
			'at' => 'far fa-at',
			'copyright' => 'far fa-copyright',
			'trademark' => 'far fa-trademark',
			'registered' => 'far fa-registered',
			'certificate' => 'far fa-certificate',
			'award' => 'far fa-award',
			'trophy' => 'far fa-trophy',
			'medal' => 'far fa-medal',
			'ribbon' => 'far fa-ribbon',
			'badge' => 'far fa-badge',
			'crown' => 'far fa-crown',
			'gem' => 'far fa-gem',
			'diamond' => 'far fa-gem',
			'ring' => 'far fa-ring',
			'gift' => 'far fa-gift',
			'birthday-cake' => 'far fa-birthday-cake',
			'cake' => 'far fa-birthday-cake',
			'cookie' => 'far fa-cookie',
			'ice-cream' => 'far fa-ice-cream',
			'pizza-slice' => 'far fa-pizza-slice',
			'hamburger' => 'far fa-hamburger',
			'hotdog' => 'far fa-hotdog',
			'taco' => 'far fa-taco',
			'utensils' => 'far fa-utensils',
			'coffee' => 'far fa-coffee',
			'wine-glass' => 'far fa-wine-glass',
			'beer' => 'far fa-beer',
			'cocktail' => 'far fa-cocktail',
			'glass-martini' => 'far fa-glass-martini',
			'wine-bottle' => 'far fa-wine-bottle',
			'wine-glass-alt' => 'far fa-wine-glass-alt',
			'wine-glass-empty' => 'far fa-wine-glass-empty',
			'wine-bottle-alt' => 'far fa-wine-bottle-alt',
			'wine-bottle-empty' => 'far fa-wine-bottle-empty',
			'wine-glass-full' => 'far fa-wine-glass',
			'wine-glass-half' => 'far fa-wine-glass-alt',
			'wine-glass-empty-alt' => 'far fa-wine-glass-empty',
			'wine-bottle-full' => 'far fa-wine-bottle',
			'wine-bottle-half' => 'far fa-wine-bottle-alt',
			'wine-bottle-empty-alt' => 'far fa-wine-bottle-empty',
		),
		'fa-brands' => array(
			'facebook' => 'fab fa-facebook',
			'twitter' => 'fab fa-twitter',
			'instagram' => 'fab fa-instagram',
			'linkedin' => 'fab fa-linkedin',
			'youtube' => 'fab fa-youtube',
			'github' => 'fab fa-github',
			'wordpress' => 'fab fa-wordpress',
			'elementor' => 'fab fa-elementor',
			'google' => 'fab fa-google',
			'apple' => 'fab fa-apple',
			'microsoft' => 'fab fa-microsoft',
			'amazon' => 'fab fa-amazon',
			'netflix' => 'fab fa-netflix',
			'spotify' => 'fab fa-spotify',
			'discord' => 'fab fa-discord',
			'slack' => 'fab fa-slack',
			'telegram' => 'fab fa-telegram',
			'whatsapp' => 'fab fa-whatsapp',
			'skype' => 'fab fa-skype',
			'zoom' => 'fab fa-zoom',
			'vimeo' => 'fab fa-vimeo',
			'tiktok' => 'fab fa-tiktok',
			'snapchat' => 'fab fa-snapchat',
			'pinterest' => 'fab fa-pinterest',
			'reddit' => 'fab fa-reddit',
			'twitch' => 'fab fa-twitch',
			'steam' => 'fab fa-steam',
			'playstation' => 'fab fa-playstation',
			'xbox' => 'fab fa-xbox',
			'nintendo' => 'fab fa-nintendo-switch',
			'android' => 'fab fa-android',
			'chrome' => 'fab fa-chrome',
			'firefox' => 'fab fa-firefox',
			'safari' => 'fab fa-safari',
			'edge' => 'fab fa-edge',
			'opera' => 'fab fa-opera',
			'internet-explorer' => 'fab fa-internet-explorer',
			'html5' => 'fab fa-html5',
			'css3' => 'fab fa-css3-alt',
			'js' => 'fab fa-js',
			'node-js' => 'fab fa-node-js',
			'npm' => 'fab fa-npm',
			'yarn' => 'fab fa-yarn',
			'php' => 'fab fa-php',
			'python' => 'fab fa-python',
			'java' => 'fab fa-java',
			'laravel' => 'fab fa-laravel',
			'react' => 'fab fa-react',
			'angular' => 'fab fa-angular',
			'vuejs' => 'fab fa-vuejs',
			'bootstrap' => 'fab fa-bootstrap',
			'sass' => 'fab fa-sass',
			'less' => 'fab fa-less',
			'stylus' => 'fab fa-stylus',
			'git' => 'fab fa-git',
			'git-alt' => 'fab fa-git-alt',
			'github-alt' => 'fab fa-github-alt',
			'gitlab' => 'fab fa-gitlab',
			'bitbucket' => 'fab fa-bitbucket',
			'docker' => 'fab fa-docker',
			'aws' => 'fab fa-aws',
			'google-cloud' => 'fab fa-google-cloud',
			'azure' => 'fab fa-microsoft',
			'heroku' => 'fab fa-heroku',
			'digital-ocean' => 'fab fa-digital-ocean',
			'linode' => 'fab fa-linode',
			'vultr' => 'fab fa-vultr',
			'cloudflare' => 'fab fa-cloudflare',
			'stack-overflow' => 'fab fa-stack-overflow',
			'stack-exchange' => 'fab fa-stack-exchange',
			'quora' => 'fab fa-quora',
			'dev' => 'fab fa-dev',
			'hashnode' => 'fab fa-hashnode',
			'substack' => 'fab fa-substack',
			'newsletter' => 'fab fa-mailchimp',
			'stripe' => 'fab fa-stripe',
			'paypal' => 'fab fa-paypal',
			'bitcoin' => 'fab fa-bitcoin',
			'ethereum' => 'fab fa-ethereum',
			'cc-visa' => 'fab fa-cc-visa',
			'cc-mastercard' => 'fab fa-cc-mastercard',
			'cc-amex' => 'fab fa-cc-amex',
			'cc-discover' => 'fab fa-cc-discover',
			'cc-paypal' => 'fab fa-cc-paypal',
			'cc-stripe' => 'fab fa-cc-stripe',
			'cc-apple-pay' => 'fab fa-cc-apple-pay',
			'cc-google-pay' => 'fab fa-google-pay',
			'cc-amazon-pay' => 'fab fa-amazon-pay',
			'cc-bitcoin' => 'fab fa-bitcoin',
			'cc-ethereum' => 'fab fa-ethereum',
			'cc-litecoin' => 'fab fa-cc-litecoin',
			'cc-dogecoin' => 'fab fa-cc-dogecoin',
			'cc-ripple' => 'fab fa-cc-ripple',
			'cc-monero' => 'fab fa-cc-monero',
			'cc-cardano' => 'fab fa-cc-cardano',
			'cc-polkadot' => 'fab fa-cc-polkadot',
			'cc-chainlink' => 'fab fa-cc-chainlink',
			'cc-uniswap' => 'fab fa-cc-uniswap',
			'cc-aave' => 'fab fa-cc-aave',
			'cc-compound' => 'fab fa-cc-compound',
			'cc-maker' => 'fab fa-cc-maker',
			'cc-dai' => 'fab fa-cc-dai',
			'cc-usdc' => 'fab fa-cc-usdc',
			'cc-usdt' => 'fab fa-cc-usdt',
			'cc-busd' => 'fab fa-cc-busd',
			'cc-tusd' => 'fab fa-cc-tusd',
			'cc-pax' => 'fab fa-cc-pax',
			'cc-gusd' => 'fab fa-cc-gusd',
			'cc-frax' => 'fab fa-cc-frax',
			'cc-alusd' => 'fab fa-cc-alusd',
			'cc-feiusd' => 'fab fa-cc-feiusd',
			'cc-lusd' => 'fab fa-cc-lusd',
			'cc-mim' => 'fab fa-cc-mim',
			'cc-ousd' => 'fab fa-cc-ousd',
			'cc-rsv' => 'fab fa-cc-rsv',
			'cc-susd' => 'fab fa-cc-susd',
			'cc-yusd' => 'fab fa-cc-yusd',
			'cc-zusd' => 'fab fa-cc-zusd',
			'cc-3crv' => 'fab fa-cc-3crv',
			'cc-a3crv' => 'fab fa-cc-a3crv',
			'cc-b3crv' => 'fab fa-cc-b3crv',
			'cc-c3crv' => 'fab fa-cc-c3crv',
			'cc-d3crv' => 'fab fa-cc-d3crv',
			'cc-e3crv' => 'fab fa-cc-e3crv',
			'cc-f3crv' => 'fab fa-cc-f3crv',
			'cc-g3crv' => 'fab fa-cc-g3crv',
			'cc-h3crv' => 'fab fa-cc-h3crv',
			'cc-i3crv' => 'fab fa-cc-i3crv',
			'cc-j3crv' => 'fab fa-cc-j3crv',
			'cc-k3crv' => 'fab fa-cc-k3crv',
			'cc-l3crv' => 'fab fa-cc-l3crv',
			'cc-m3crv' => 'fab fa-cc-m3crv',
			'cc-n3crv' => 'fab fa-cc-n3crv',
			'cc-o3crv' => 'fab fa-cc-o3crv',
			'cc-p3crv' => 'fab fa-cc-p3crv',
			'cc-q3crv' => 'fab fa-cc-q3crv',
			'cc-r3crv' => 'fab fa-cc-r3crv',
			'cc-s3crv' => 'fab fa-cc-s3crv',
			'cc-t3crv' => 'fab fa-cc-t3crv',
			'cc-u3crv' => 'fab fa-cc-u3crv',
			'cc-v3crv' => 'fab fa-cc-v3crv',
			'cc-w3crv' => 'fab fa-cc-w3crv',
			'cc-x3crv' => 'fab fa-cc-x3crv',
			'cc-y3crv' => 'fab fa-cc-y3crv',
			'cc-z3crv' => 'fab fa-cc-z3crv',
		),
	);
}

/**
 * Add meta box
 */
add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'letlexi_page_icon',
		__( 'Page Icon', 'letlexi' ),
		__NAMESPACE__ . '\\render_page_icon_metabox',
		'page',
		'side',
		'default'
	);
} );

/**
 * Render meta box UI
 */
function render_page_icon_metabox( $post ) {
	$id               = (int) get_post_meta( $post->ID, '_page_icon_attachment_id', true );
	$icon_library_raw = get_post_meta( $post->ID, '_page_icon_library', true );
	$icon_type        = get_post_meta( $post->ID, '_page_icon_type', true );
	$url              = $id ? wp_get_attachment_image_url( $id, 'thumbnail' ) : '';

	$icon_library_data = null;
	if ( $icon_library_raw ) {
		if ( is_string( $icon_library_raw ) ) {
			$icon_library_data = json_decode( $icon_library_raw, true );
		} elseif ( is_array( $icon_library_raw ) ) {
			$icon_library_data = $icon_library_raw;
		}
	}

	$has_library = ( is_array( $icon_library_data ) && ! empty( $icon_library_data['value'] ) );
	if ( empty( $icon_type ) ) {
		$icon_type = $has_library ? 'library' : ( $id ? 'uploaded' : 'uploaded' );
	}

	wp_nonce_field( 'letlexi_save_page_icon', 'letlexi_page_icon_nonce' );
	?>
	<div id="page-icon-attachment">
		<div style="margin-bottom: 15px;">
			<label style="display:block;margin-bottom:5px;font-weight:bold;">Icon Type:</label>
			<select id="icon_type_selector" name="icon_type_selector" style="width: 84%;">
				<option value="uploaded" <?php selected( $icon_type, 'uploaded' ); ?>><?php esc_html_e( 'Uploaded SVG', 'letlexi' ); ?></option>
				<option value="library" <?php selected( $icon_type, 'library' ); ?>><?php esc_html_e( 'Icon Library', 'letlexi' ); ?></option>
			</select>
		</div>

		<div id="uploaded_svg_section" style="<?php echo ( 'uploaded' === $icon_type ) ? '' : 'display:none;'; ?>">
			<div class="preview" style="margin-bottom:8px;">
				<?php if ( $url ) : ?>
					<img src="<?php echo esc_url( $url ); ?>" style="max-width:100%;height:auto;" />
				<?php endif; ?>
			</div>
			<input type="hidden" name="page_icon_attachment_id" id="page_icon_attachment_id" value="<?php echo esc_attr( $id ); ?>">
			<button type="button" class="button" id="page_icon_select"><?php echo $id ? esc_html__( 'Change Icon', 'letlexi' ) : esc_html__( 'Select Icon', 'letlexi' ); ?></button>
			<button type="button" class="button button-link-delete" id="page_icon_remove" style="margin-left:8px;<?php echo $id ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Remove', 'letlexi' ); ?></button>
			<p class="description" style="margin-top:8px;"><?php esc_html_e( 'Pick an uploaded SVG file', 'letlexi' ); ?></p>
		</div>

		<div id="icon_library_section" style="<?php echo ( 'library' === $icon_type ) ? '' : 'display:none;'; ?>">
			<div class="icon-library-preview" style="margin-bottom:8px;padding:10px;border:1px solid #ddd;background:#f9f9f9;text-align:center;">
				<?php if ( $icon_library_data && is_array( $icon_library_data ) && isset( $icon_library_data['value'] ) ) : ?>
					<div id="icon_library_display" style="font-size:24px;">
						<?php echo '<i class="' . esc_attr( $icon_library_data['value'] ) . '" style="font-size:24px;"></i>'; ?>
					</div>
				<?php else : ?>
					<div id="icon_library_display" style="color:#999;"><?php esc_html_e( 'No icon selected', 'letlexi' ); ?></div>
				<?php endif; ?>
			</div>
			<input type="hidden" name="page_icon_library" id="page_icon_library" value="<?php echo esc_attr( $icon_library_data ? wp_json_encode( $icon_library_data ) : '' ); ?>">
			<button type="button" class="button" id="icon_library_select"><?php echo ( $icon_library_data && is_array( $icon_library_data ) && isset( $icon_library_data['value'] ) ) ? esc_html__( 'Change Icon', 'letlexi' ) : esc_html__( 'Select Icon', 'letlexi' ); ?></button>
			<button type="button" class="button button-link-delete" id="icon_library_remove" style="margin-left:8px;<?php echo ( $icon_library_data && is_array( $icon_library_data ) && isset( $icon_library_data['value'] ) ) ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Remove', 'letlexi' ); ?></button>
			<p class="description" style="margin-top:8px;"><?php esc_html_e( 'Pick from FontAwesome icon library (Solid, Regular, and Brand icons)', 'letlexi' ); ?></p>
		</div>
	</div>

	<script>
	(function($){
		let frame;
		const $wrap  = $('#page-icon-attachment');
		const $id    = $('#page_icon_attachment_id');
		const $prev  = $wrap.find('.preview');
		const $btn   = $('#page_icon_select');
		const $rem   = $('#page_icon_remove');
		const $typeSelector = $('#icon_type_selector');
		const $uploadedSection = $('#uploaded_svg_section');
		const $iconLibSection = $('#icon_library_section');
		const $iconLibInput = $('#page_icon_library');
		const $iconLibBtn = $('#icon_library_select');
		const $iconLibRem = $('#icon_library_remove');
		const $iconLibDisplay = $('#icon_library_display');

		$typeSelector.on('change', function(){
			const v = $(this).val();
			if (v === 'uploaded') { $uploadedSection.show(); $iconLibSection.hide(); }
			else { $uploadedSection.hide(); $iconLibSection.show(); }
		});

		$btn.on('click', function(e){
			e.preventDefault();
			if (frame) { frame.open(); return; }
			frame = wp.media({ title: 'Select SVG Icon', button: { text: 'Use this SVG' }, multiple: false, library: { type: 'image/svg+xml' } });
			frame.on('select', function(){
				const a = frame.state().get('selection').first().toJSON();
				if (a.mime !== 'image/svg+xml') { alert('Please select an SVG file.'); return; }
				$id.val(a.id);
				const thumb = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
				$prev.html($('<img/>',{src:thumb, style:'max-width:100%;height:auto;'}));
				$rem.show(); $btn.text('Change Icon');
			});
			frame.open();
		});

		$rem.on('click', function(e){
			e.preventDefault();
			$id.val('');
			$prev.empty();
			$rem.hide();
			$btn.text('Select Icon');
		});

		$iconLibBtn.on('click', function(e){
			e.preventDefault();
			const iconPicker = $('<div>',{ 'class':'icon-library-picker-modal', html:`
				<div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:999999;display:flex;align-items:center;justify-content:center;">
					<div style="background:#fff;padding:20px;border-radius:5px;width:900px;max-height:80vh;overflow:auto;">
						<h3 style="margin-top:0;">Select Icon from Icon Library</h3>
						<div style="margin-bottom:15px;"><input type="text" id="icon-search" placeholder="Search icons..." style="width:100%;padding:8px;border:1px solid #ddd;border-radius:3px;"></div>
						<div id="icon-library-picker-content"><p>Loading icons...</p></div>
						<div style="margin-top:20px;text-align:right;">
							<button type="button" class="button" id="cancel-icon-picker">Cancel</button>
							<button type="button" class="button button-primary" id="apply-icon-picker" style="margin-left:10px;">Apply</button>
						</div>
					</div>
				</div>`});
			$('body').append(iconPicker);

			const iconLibrary = <?php echo wp_json_encode( letlexi_get_fontawesome_icons() ); ?>;
			const loadIconLibrary = function(){
				const iconContent = $('#icon-library-picker-content');
				let html = '<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(100px,1fr));gap:10px;max-height:400px;overflow-y:auto;">';
				['fa-solid','fa-regular','fa-brands'].forEach(function(group){
					html += '<div style="grid-column:1/-1;font-weight:bold;margin:10px 0 5px;border-bottom:1px solid #ddd;">'+group+'</div>';
					const groupData = iconLibrary[group] || {};
					const entries = Array.isArray(groupData) ? groupData.map((v, i) => [String(i), v]) : Object.entries(groupData);
					entries.forEach(function(entry){
						const key = entry[0];
						const value = entry[1];
						const prefix = group === 'fa-solid' ? 'fas' : (group === 'fa-regular' ? 'far' : 'fab');
						let iconName = '';
						let fullClass = '';
						if (typeof value === 'string') {
							if (/^(fa[srb]|fab|far)\s+fa-/.test(value) || value.indexOf(' fa-') !== -1 || value.indexOf('fa-') === 0) {
								iconName = key;
								fullClass = value;
							} else {
								iconName = value;
								fullClass = `${prefix} fa-${value}`;
							}
						} else {
							return;
						}
						html += `<div class="icon-option" data-icon='{"value":"${fullClass}","library":"${group}"}' style="text-align:center;padding:10px;border:1px solid #ddd;cursor:pointer;border-radius:3px;">`+
							`<i class="${fullClass}" style="font-size:20px;"></i><br>`+
							`<small style="font-size:10px;">${iconName}</small>`+
						`</div>`;
					});
				});
				html += '</div>';
				iconContent.html(html);

				$('.icon-option').on('click', function(){ $('.icon-option').removeClass('selected'); $(this).addClass('selected'); });
				$('#icon-search').on('input', function(){
					const s = $(this).val().toLowerCase();
					$('.icon-option').each(function(){
						const iconName = $(this).find('small').text().toLowerCase();
						const iconClass = $(this).find('i').attr('class').toLowerCase();
						$(this).toggle(iconName.includes(s) || iconClass.includes(s));
					});
				});

				$('#apply-icon-picker').on('click', function(){
					const selected = $('.icon-option.selected');
					if (selected.length) {
						const iconData = JSON.parse(selected.attr('data-icon'));
						$iconLibInput.val(JSON.stringify(iconData));
						$iconLibDisplay.html(`<i class="${iconData.value}" style="font-size:24px;"></i>`);
						$iconLibRem.show();
						$iconLibBtn.text('Change Icon');
						$typeSelector.val('library');
						$uploadedSection.hide();
						$iconLibSection.show();
					}
					iconPicker.remove();
				});

				$('#cancel-icon-picker').on('click', function(){ iconPicker.remove(); });
			};

			loadIconLibrary();
		});

		$iconLibRem.on('click', function(e){
			e.preventDefault();
			$iconLibInput.val('');
			$iconLibDisplay.html('<div style="color:#999;">No icon selected</div>');
			$iconLibRem.hide();
			$iconLibBtn.text('Select Icon');
		});
	})(jQuery);
	</script>

	<style>
	.icon-option:hover { background-color:#f0f0f0 !important; }
	.icon-option.selected { background-color:#0073aa !important; color:white !important; }
	.icon-option.selected i { color:white !important; }
	</style>
	<?php
}

/**
 * Save handler
 */
add_action( 'save_post_page', function ( $post_id ) {
	if ( empty( $_POST['letlexi_page_icon_nonce'] ) || ! wp_verify_nonce( $_POST['letlexi_page_icon_nonce'], 'letlexi_save_page_icon' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_page', $post_id ) ) { return; }

	$icon_type = isset( $_POST['icon_type_selector'] ) ? sanitize_text_field( wp_unslash( $_POST['icon_type_selector'] ) ) : 'uploaded';
	update_post_meta( $post_id, '_page_icon_type', $icon_type );

	$val = isset( $_POST['page_icon_attachment_id'] ) ? (int) $_POST['page_icon_attachment_id'] : 0;
	if ( $val ) {
		$mime = get_post_mime_type( $val );
		if ( 'image/svg+xml' !== $mime ) { $val = 0; }
	}
	if ( $val ) { update_post_meta( $post_id, '_page_icon_attachment_id', $val ); }
	else { delete_post_meta( $post_id, '_page_icon_attachment_id' ); }

	$icon_library_data = isset( $_POST['page_icon_library'] ) ? sanitize_text_field( wp_unslash( $_POST['page_icon_library'] ) ) : '';
	if ( $icon_library_data ) { update_post_meta( $post_id, '_page_icon_library', $icon_library_data ); }
	else { delete_post_meta( $post_id, '_page_icon_library' ); }
} );


