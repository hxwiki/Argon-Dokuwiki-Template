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
$showSidebar = page_findnearest($conf['sidebar']) && ($ACT == 'show');

/* Begin. */ ?>
<!DOCTYPE html>
<html lang="<?= $conf['lang'] ?>" dir="<?= $lang['direction'] ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php tpl_pagetitle(); ?> - <?= strip_tags($conf['title']) ?></title>
	<?php tpl_metaheaders(); ?>
	<?php print(tpl_favicon(['favicon', 'mobile'])); ?>
	<?php tpl_includeFile('meta.html'); ?>
	<!-- MDUI -->
	<link rel="stylesheet" href="<?= tpl_basedir() ?>mdui/css/mdui.min.css" />
	<!-- Argon Icons/Fonts/Styles -->
	<link rel="stylesheet" href="<?= tpl_basedir() ?>assets/css/fonts.css" />
	<link rel="stylesheet" href="<?= tpl_basedir() ?>assets/css/doku.css" />
</head>

<body class="mdui-theme-primary-orange mdui-theme-accent-orange mdui-appbar-with-toolbar mdui-drawer-body-left mdui-color-grey-100 docs">
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
		</div>
	</header>
	<aside class="mdui-drawer" id="mdwiki-left-drawer">
		<ul class="mdui-list">
			<section><?php tpl_searchform(); ?></section>
			<?php
			if (!empty($_SERVER['REMOTE_USER'])) : ?>
				<section class="mdui-text-truncate" style="margin: 0; padding: 2px 16px 16px;">
					<?php tpl_userinfo(); ?>
				</section>
			<?php endif; ?>


			<?php if ($showTools && !tpl_getConf('movePageTools')) : ?>
				<section id="dokuwiki__pagetools" class="ct-toc-item active">
					<li class="mdui-subheader" style="margin: 0;"><?= $lang['page_tools'] ?></li>
					<ul class="nav ct-sidenav">
						<?php
						$menu_items = (new \dokuwiki\Menu\PageMenu())->getItems();
						foreach ($menu_items as $item) {
							$accesskey = $item->getAccesskey();
							$akey = '';
							if ($accesskey) {
								$akey = 'accesskey="' . $accesskey . '" ';
							}
							echo '<li class="' . $item->getType() . '">'
								. '<a class="' . $item->getLinkAttributes('')['class'] . '" href="' . $item->getLink() . '" title="' . $item->getTitle() . '" ' . $akey . '>'
								. $item->getLabel()
								. '</a></li>';
						}
						?>
					</ul>
				</section>
			<?php endif; ?>

			<div class="ct-toc-item active">

				<a class="ct-toc-link">
					<?php echo $lang['site_tools'] ?>
				</a>
				<ul class="nav ct-sidenav">
					<?php
					$menu_items = (new \dokuwiki\Menu\SiteMenu())->getItems();
					foreach ($menu_items as $item) {
						echo '<li class="' . $item->getType() . '">'
							. '<a class="" href="' . $item->getLink() . '" title="' . $item->getTitle() . '">'
							. $item->getLabel()
							. '</a></li>';
					}

					?>
				</ul>
			</div>



			<?php if ($showSidebar) : ?>
				<div id="dokuwiki__aside" class="ct-toc-item active">
					<a class="ct-toc-link">
						<?php echo $lang['sidebar'] ?>
					</a>
					<div class="leftsidebar">
						<?php tpl_includeFile('sidebarheader.html') ?>
						<?php tpl_include_page($conf['sidebar'], 1, 1) ?>
						<?php tpl_includeFile('sidebarfooter.html') ?>
					</div>
				</div>
			<?php endif; ?>
		</ul>
	</aside>

	<div id="dokuwiki__site">
		<div class="container-fluid">
			<div class="row flex-xl-nowrap">


				<?php
				// Render the content initially
				ob_start();
				tpl_content(false);
				$buffer = ob_get_clean();
				?>


				<!-- center content -->

				<main class="col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 ct-content dokuwiki" role="main">

					<div id="dokuwiki__top" class="site
						<?php echo tpl_classes(); ?>
						<?php echo ($showSidebar) ? 'hasSidebar' : ''; ?>">
					</div>

					<?php html_msgarea() ?>
					<?php tpl_includeFile('header.html') ?>


					<!-- Trace/Navigation -->
					<nav aria-label="breadcrumb" role="navigation">
						<ol class="breadcrumb">
							<?php if ($conf['breadcrumbs']) { ?>
								<div class="breadcrumbs"><?php tpl_breadcrumbs() ?></div>
							<?php } ?>
							<?php if ($conf['youarehere']) { ?>
								<div class="breadcrumbs"><?php tpl_youarehere() ?></div>
							<?php } ?>
						</ol>
					</nav>

					<?php if ($showTools && tpl_getConf('movePageTools')) : ?>
						<!-- Page Menu -->
						<div class="argon-doku-page-menu">
							<?php
							$menu_items = (new \dokuwiki\Menu\PageMenu())->getItems();
							foreach ($menu_items as $item) {
								$accesskey = $item->getAccesskey();
								$akey = '';
								if ($accesskey) {
									$akey = 'accesskey="' . $accesskey . '" ';
								}
								echo '<li class="' . $item->getType() . '">'
									. '<a class="page-menu__link ' . $item->getLinkAttributes('')['class'] . '" href="' . $item->getLink() . '" title="' . $item->getTitle() . '" ' . $akey . '>'
									. '<i class="">' . inlineSVG($item->getSvg()) . '</i>'
									. '<span class="a11y">' . $item->getLabel() . '</span>'
									. '</a></li>';
							}
							?>
						</div>
					<?php endif; ?>

					<!-- Wiki Contents -->
					<div id="dokuwiki__content">
						<div class="pad">

							<div class="page">

								<?php echo $buffer ?>
							</div>
						</div>
					</div>

					<hr />
					<!-- Footer -->
					<div class="card footer-card">
						<div class="card-body">
							<div class="container">
								<div class="row">
									<div class="col">
										<div id="dokuwiki__footer">
											<div class="pad">
												<div class="doc">
													<?php tpl_pageinfo() /* 'Last modified' etc */ ?></div>
												<?php tpl_license('0') /* content license, parameters: img=*badge|button|0, imgonly=*0|1, return=*0|1 */ ?>
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
									<?php tpl_includeFile('footer.html') ?>
								</div>

							</div>

						</div>
					</div>
					<?php tpl_indexerWebBug(); ?>
				</main>




				<!-- Right Sidebar -->
				<div class="d-none d-xl-block col-xl-2 ct-toc">
					<div>
						<?php tpl_toc() ?>
					</div>
				</div>

			</div>
		</div>
	</div>
	<script src="<?= tpl_basedir() ?>mdui/js/mdui.min.js"></script>
</body>

</html>