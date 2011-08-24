<?php
namespace tapeet\web\service;


require_once 'smarty/libs/Smarty.class.php';
require_once 'smarty/libs/plugins/block.component.php';

use Smarty;
use ComponentBlock;


/** @Service('renderer') */
class Renderer {


	/** @Service */
	public $controllerState;
	/** @Service */
	public $response;
	/** @Configuration('smarty_compile_dir') */
	public $smartyCompileDir;


	public function doFilter($chain) {
		if ($this->controllerState->isRenderRequest()) {
			$this->response->write(
					$this->render(
							$this->controllerState->page
						)
				);
		}
		$chain->doFilter();
	}


	function render($component) {
		$result = array();

		while (true) {
			$beginRender = null;
			if (method_exists($component->object, 'beginRender')) {
				$beginRender = $component->beginRender();
			}

			if (! isset($beginRender) || $beginRender) {
				$result[] = $this->renderOnce($component);
			}

			$endRender = null;
			if (method_exists($component->object, 'endRender')) {
				$endRender = $component->endRender();
			}

			if (! isset($endRender) || $endRender) {
				break;
			}
		}

		return implode($result);
	}


	private function renderOnce($component) {
		$template = new Smarty();
		$template->default_resource_type = 'include_path';
		$template->compile_dir = $this->smartyCompileDir;
		$template->compiler_file = 'MVC_Smarty_Compiler.php';
		$template->compiler_class = 'MVC_Smarty_Compiler';
		$template->assign('c', $component);
		foreach ($component->_components as $name => $subComponent) {
			$componentBlock = new ComponentBlock();
			$componentBlock->component = $subComponent;
			$template->register_block($name . '_block', array($componentBlock, 'renderBlock'));
			$template->register_function($name, array($componentBlock, 'render'));
		}
		return $template->fetch($component->_template);
	}

}
?>