<?php
/** @var \muuska\html\HtmlPage $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
?>
<?php $mainContent = $item->drawMainContent($globalConfig, $callerConfig)?>
<?php echo $item->drawPageStart($globalConfig, $callerConfig, '', 'default_page');?>
		<header id="header" class="header">
			<div class="row clearfix header_content">
        		<div class="col-md-3">
        			<?php echo $item->drawContentsByPosition('header_left', $globalConfig, $callerConfig, '<div class="header_left">', '</div>'); ?>
        		</div>
        		<div class="col-md-5">
        			<?php echo $item->drawContentsByPosition('header_center', $globalConfig, $callerConfig, '<div class="header_center">', '</div>'); ?>
        		</div>
        		<div class="col-md-4">
        			<?php echo $item->drawContentsByPosition('header_right', $globalConfig, $callerConfig, '<div class="header_right clearfix">', '</div>'); ?>
        		</div>
        	</div>
		</header>
		<div id="main" class="main">
			<div class="alerts"><?php echo $item->drawAlerts($globalConfig, $callerConfig); ?></div>
			<div class="main_content"><?php echo $mainContent; ?></div>
		</div>
		<footer class="footer">
    		<div class="row">
        		<?php echo $item->drawContentsByPosition('footer_left', $globalConfig, $callerConfig, '<div class="col-md-3">', '</div>'); ?>
        		<?php echo $item->drawContentsByPosition('footer_center_left', $globalConfig, $callerConfig, '<div class="col-md-3">', '</div>'); ?>
        		<?php echo $item->drawContentsByPosition('footer_center_right', $globalConfig, $callerConfig, '<div class="col-md-3">', '</div>'); ?>
        		<?php echo $item->drawContentsByPosition('footer_right', $globalConfig, $callerConfig, '<div class="col-md-3">', '</div>'); ?>
    		</div>
    		<?php echo $item->drawContentsByPosition('footer_copyright', $globalConfig, $callerConfig, '<p class="footer_copyright">', '</p>'); ?>
        </footer>
		
		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>
<?php echo $item->drawPageEnd($globalConfig, $callerConfig);?>