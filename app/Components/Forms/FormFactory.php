<?php
declare(strict_types = 1);

namespace App\Components\Forms;

use Nette\Application\UI\Form;

/**
 * Class FormFactory
 */
class FormFactory
{

	/**
	 * @return Form
	 */
	public function create(): Form
	{
		$form = new Form();
		$form->setTranslator(NULL);
		return $form;
	}

}
