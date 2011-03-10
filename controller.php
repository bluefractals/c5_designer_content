<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

class DesignerContentPackage extends Package {
	
	protected $pkgHandle = 'designer_content';
	protected $appVersionRequired = '5.4.1';
	protected $pkgVersion = '1.0';
	
	public function getPackageName() {
		return t("Designer Content"); 
	}	
	
	public function getPackageDescription() {
		return t('A content block generator for designers and developers. Allows you to easily create blocks which display rich content, images, and/or text in just the right way.');
	}

	public function on_start() {
		Events::extend('on_page_view', 'DesignerContentPackage', 'on_page_view', 'packages/designer_content/controller.php');
	}

	public function on_page_view() {
		//Override system js IF user is in edit mode (or in the global scrapbook)
		if (Page::getCurrentPage()->isEditMode() || Page::getCurrentPage()->getCollectionPath() == '/dashboard/scrapbook') {
			$html = Loader::helper('html');
			$view = View::getInstance();
			$view->addHeaderItem($html->javascript(BASE_URL.DIR_REL.'/packages/designer_content/js/ccm.filemanager.js'), 'CONTROLLER');
			//Note that we passed the 'CONTROLLER' namespace to addHeaderItem() so that it adds our items AFTER the core items
		}
	}

	public function install() {
		$pkg = parent::install();
		
		//Install dashboard page
		Loader::model('single_page');
		$p = SinglePage::add('/dashboard/designer_content', $pkg);
		$p->update(array('cDescription' => t('Create custom content blocks')));
	}
	
}