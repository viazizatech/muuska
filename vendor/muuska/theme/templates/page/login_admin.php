<?php
/** @var \muuska\html\HtmlPage $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php $mainContent = $item->drawMainContent($globalConfig, $callerConfig, '')?>
<?php echo $item->drawPageStart($globalConfig, $callerConfig, '', 'login_page kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading');?>
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(<?php echo $globalConfig->getTheme()->createHtmlImage('bg/bg-3.jpg')->getFinalSrc($globalConfig, $callerConfig);?>);">
					<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
						<div class="kt-login__container">
							<?php echo $item->renderLogo($globalConfig, $callerConfig, '<div class="kt-login__logo">', '</div>');?>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<?php echo $item->drawContentByName('loginTitle', $globalConfig, $callerConfig, '<h3 class="kt-login__title">', '</h3>');?>
								</div>
								<?php echo $mainContent;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php echo $item->drawPageEnd($globalConfig, $callerConfig);?>