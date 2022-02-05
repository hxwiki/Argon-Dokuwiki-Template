<?php /* must be run from within DokuWiki */ if (!defined('DOKU_INC')) die();

/**
 * Argon Dokuwiki Template
 * 
 * Based on the Argon Design System by Creative Tim
 * Ported to Dokuwiki by Anchit (@IceWreck)
 * 
 * @link https://github.com/IceWreck/Argon-Dokuwiki-Template
 */

/**
 * Secondary development Info
 * Rewritten using Material Design library MDUI (https://mdui.org/)
 * 
 * @author FlyingSky (@FlyingSky-CN)
 * @link https://github.com/hxwiki/Argon-Dokuwiki-Template
 */

/* Include hook for template functions. */
@require_once dirname(__FILE__) . '/tpl_functions.php';

/* Load basic config. */
$showTools = !tpl_getConf('hideTools') || (tpl_getConf('hideTools') && !empty($_SERVER['REMOTE_USER']));

/* Begin. */ ?>
<!DOCTYPE html>
<html lang="<?= $conf['lang'] ?>" dir="<?= $lang['direction'] ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php tpl_metaheaders(); ?>
	<title><?php tpl_pagetitle(); ?> - <?= strip_tags($conf['title']) ?></title>
	<!-- MDUI -->
	<link rel="stylesheet" href="<?= tpl_basedir() ?>mdui/css/mdui.min.css" />
	<style>
		a,
		a:hover {
			text-decoration: none !important;
		}

		article button {
			background-color: #BF360C !important;
			border-color: #BF360C !important;
		}

		#dw__toc {
			float: unset;
			margin: 0;
			width: 100%;
			background-color: transparent;
		}

		.mdui-typo sup a {
			display: inline !important;
			vertical-align: baseline !important;
		}
	</style>
	<!-- Argon Icons/Fonts/Styles -->
	<link rel="stylesheet" href="<?= tpl_basedir() ?>assets/css/fonts.css" />
	<link rel="stylesheet" href="<?= tpl_basedir() ?>assets/css/doku.css" />
</head>

<body class="mdui-theme-primary-deep-orange mdui-theme-accent-deep-orange mdui-appbar-with-toolbar mdui-drawer-body-left mdui-drawer-body-right mdui-color-grey-100 docs">
	<!-- Header -->
	<header class="mdui-appbar mdui-appbar-fixed">
		<div class="mdui-toolbar mdui-color-theme">
			<a class="mdui-btn mdui-btn-icon mdui-text-color-white-icon" mdui-drawer="{target: '#mdwiki-left-drawer'}">
				<i class="mdui-icon material-icons">menu</i>
			</a>
			<a href="<?= wl() ?>" style="text-decoration: none !important;">
				<span class="mdui-typo-title mdui-text-color-white">
					<b><?= $conf['title'] ?></b>
				</span>
			</a>
			<div class="mdui-toolbar-spacer"></div>
			<?php foreach ((new \dokuwiki\Menu\UserMenu())->getItems() as $item) : ?>
				<a href="<?= $item->getLink() ?>" class="mdui-btn mdui-btn-icon <?= $item->getType() ?>" title="<?= $item->getTitle() ?>" mdui-tooltip="{content: '<?= $item->getLabel() ?>'}">
					<i class="mdui-icon material-icons" style="margin-top: -6px; fill: white;">
						<?= inlineSVG($item->getSvg()) ?>
					</i>
				</a>
			<?php endforeach; ?>
			<a class="mdui-btn mdui-btn-icon" mdui-dialog="{target: '#mdwiki-search-form'}" mdui-tooltip="{content: '搜索'}" title="搜索">
				<i class="mdui-icon material-icons" style="color: white;">search</i>
			</a>
		</div>
	</header>
	<!-- Search Form -->
	<div class="mdui-dialog" id="mdwiki-search-form">
		<div class="mdui-dialog-content">
			<?php tpl_searchform(); ?>
		</div>
	</div>
	<!-- Left Drawer -->
	<aside class="mdui-drawer" id="mdwiki-left-drawer">
		<ul class="mdui-list">
			<!-- Page Tools -->
			<?php if ($showTools && !tpl_getConf('movePageTools')) : ?>
				<li class="mdui-subheader" style="margin: 0;"><?= $lang['page_tools'] ?></li>
				<?php foreach ((new \dokuwiki\Menu\PageMenu())->getItems() as $item) : ?>
					<?php $akey = $item->getAccesskey() ? 'accesskey="' . $item->getAccesskey() . '"' : ''; ?>
					<a class="<?= $item->getLinkAttributes('')['class'] ?>" href="<?= $item->getLink() ?>" title="<?= $item->getTitle() ?>" <?= $akey ?>>
						<li class="mdui-list-item mdui-ripple <?= $item->getType() ?>" style="margin: 0;">
							<?= $item->getLabel() ?>
						</li>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
			<!-- User Info -->
			<?php if (!empty($_SERVER['REMOTE_USER'])) : ?>
				<li class="mdui-subheader" style="margin: 0;"><?= $lang['profile'] ?></li>
				<li class="mdui-list-item mdui-ripple" style="margin: 0;">
					<span class="mdui-text-truncate"><?php tpl_userinfo(); ?></span>
				</li>
			<?php endif; ?>
			<!-- Site Tools -->
			<li class="mdui-subheader" style="margin: 0;"><?= $lang['site_tools'] ?></li>
			<?php foreach ((new \dokuwiki\Menu\SiteMenu())->getItems() as $item) : ?>
				<a href="<?= $item->getLink() ?>" title="<?= $item->getTitle() ?>">
					<li class="mdui-list-item mdui-ripple <?= $item->getType() ?>" style="margin: 0; align-items: center;">
						<?= $item->getLabel() ?>
					</li>
				</a>
			<?php endforeach; ?>
		</ul>
	</aside>
	<!-- Main -->
	<main class="mdui-container dokuwiki" style="padding-top: 24px; max-width: 800px;">
		<!-- Messages -->
		<?php html_msgarea() ?>
		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb" role="navigation">
			<ol class="breadcrumb">
				<?php if ($conf['breadcrumbs']) : ?>
					<div class="breadcrumbs mdui-typo"><?php tpl_breadcrumbs() ?></div>
				<?php endif; ?>
				<?php if ($conf['youarehere']) : ?>
					<div class="breadcrumbs mdui-typo"><?php tpl_youarehere() ?></div>
				<?php endif; ?>
			</ol>
		</nav>
		<!-- Page Menu -->
		<?php if ($showTools && tpl_getConf('movePageTools')) : ?>
			<div class="argon-doku-page-menu" style="margin: 0 -4px;">
				<?php
				foreach ((new \dokuwiki\Menu\PageMenu())->getItems() as $item) : ?>
					<?php $akey = $item->getAccesskey() ? 'accesskey="' . $item->getAccesskey() . '"' : ''; ?>
					<li class="<?= $item->getType() ?>" style="margin: 4px;">
						<a class="mdui-btn mdui-btn-icon page-menu__link <?= $item->getLinkAttributes('')['class'] ?>" href="<?= $item->getLink() ?>" title="<?= $item->getTitle() ?>" <?= $akey ?> mdui-tooltip="{content: '<?= $item->getLabel() ?>'}">
							<i class="mdui-icon material-icons" style="margin-top: -6px; fill: #6c757d;"><?= inlineSVG($item->getSvg()) ?></i>
						</a>
					</li>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<!-- Wiki Contents -->
		<article class="page mdui-typo" id="dokuwiki__content">
			<?php tpl_content(false); ?>
		</article>
		<!-- Footer -->
		<footer class="card footer-card">
			<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col">
							<div id="dokuwiki__footer">
								<div class="pad">
									<div class="doc mdui-typo">
										<?php tpl_pageinfo() /* 'Last modified' etc */ ?>
										<?php tpl_license('0') /* content license, parameters: img=*badge|button|0, imgonly=*0|1, return=*0|1 */ ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="footer-search">
							<?php tpl_searchform() ?>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="argon-doku-footer-fullmenu">
							<?php
							$menu_items = (new \dokuwiki\Menu\MobileMenu())->getItems();
							foreach ($menu_items as $item) {
								echo '<li class="' . $item->getType() . '">'
									. '<a class="" href="' . $item->getLink() . '" title="' . $item->getTitle() . '">'
									. '<i class="" aria-hidden="true">' . inlineSVG($item->getSvg()) . '</i>'
									. '<span class="a11y">' . $item->getLabel() . '</span>'
									. '</a></li>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</main>
	<!-- Right Drawer -->
	<aside class="mdui-drawer mdui-drawer-right">
		<div class="ct-toc" style="margin-left: -12px; margin-top: -16px;">
			<?php tpl_toc() ?>
		</div>
	</aside>
	<?php tpl_indexerWebBug(); ?>
	<!-- MDUI -->
	<script src="<?= tpl_basedir() ?>mdui/js/mdui.min.js"></script>
</body>

</html>