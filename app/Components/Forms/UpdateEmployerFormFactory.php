<?php

declare(strict_types = 1);

namespace App\Components\Forms;

use App\Enums\GenderEnum;
use App\Model\Employer\EmployerData;
use App\Model\Employer\EmployerRepository;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class UpdateEmployerFormFactory
{

	/** @var FormFactory */
	private FormFactory $factory;

	private EmployerRepository $employerRepository;


	/**
	 * @param FormFactory        $factory
	 * @param EmployerRepository $employerRepository
	 */
	public function __construct(FormFactory $factory, EmployerRepository $employerRepository)
	{
		$this->factory = $factory;
		$this->employerRepository = $employerRepository;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();

		$form->addHidden('id');
		$form->addText('fullName', 'Celé meno');
		$form->addInteger('age', 'Vek');
		$form->addSelect('gender', 'Pohlavie', GenderEnum::getGenders());

		$form->addSubmit('send', 'send');
		/**
		 * @param Form      $form
		 * @param ArrayHash $values
		 */
		$form->onSuccess[] = function (Form $form, ArrayHash $values) use ($onSuccess) {

			$employer = new EmployerData();
			$employer->fullName = $values->offsetGet('fullName');
			$employer->age = $values->offsetGet('age');
			$employer->gender = GenderEnum::from($values->offsetGet('gender'));

			$this->employerRepository->updateEmployer((int) $values->id, $employer);

			$onSuccess($values);
		};

		return $form;
	}

}
