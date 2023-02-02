<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Components\Compiler\ViteFactory;
use App\Components\DataGrid\EmployerGridFactory;
use App\Components\Forms\EmployerFormFactory;
use App\Components\Forms\UpdateEmployerFormFactory;
use App\Enums\GenderEnum;
use App\Model\Employer\EmployerRepository;
use App\Model\Employer\Exception\EmployerNotFoundException;
use Faker\Factory;
use Nette;
use Nette\Application\UI\Form;
use Ublaboo\DataGrid\DataGrid;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{

	/** @inject */
	public ViteFactory $viteFactory;

	/** @inject */
	public EmployerRepository $employerRepository;

	/** @inject */
	public EmployerGridFactory $employerGridFactory;

	/** @inject */
	public EmployerFormFactory $employerFormFactory;

	/** @inject */
	public UpdateEmployerFormFactory $updateEmployerFormFactory;


	protected function createComponentEmployerGrid(): DataGrid
	{
		return $this->employerGridFactory->create();
	}


	protected function createComponentUpdateEmployerForm(): Form
	{
		return $this->updateEmployerFormFactory->create(function ($values) {
			$this->flashMessage('Zamestnanec bol upravený.');
			$this->redirect('Homepage:default');
		});
	}


	protected function createComponentEmployerForm(): Form
	{
		return $this->employerFormFactory->create(function ($values) {
			$this->flashMessage('Zamestnanec bol vytvorený.');
			$this->redirect('Homepage:default');
		});
	}


	protected function beforeRender()
	{
		$this->template->viteFactory = $this->viteFactory;
	}


	public function renderDefault(): void
	{
		$this->template->employerGraphData = $this->employerRepository->getAllEmployerToGraph();

		bdump($this->template->employerGraphData);
	}


	public function actionUpdate(int $id): void
	{
		try {
			$employer = $this->employerRepository->getEmployerById($id);
		} catch (EmployerNotFoundException $exception) {
			$this->error();
		}

		$this['updateEmployerForm']->setDefaults([
			'fullName' => $employer->getFullName(),
			'age' => $employer->getAge(),
			'gender' => $employer->getGender()->value,
		]);
	}


	public function handleRemoveEmployer(int $id): void
	{
		$this->employerRepository->deleteById($id);

		$this->template->employerGraphData = $this->employerRepository->getAllEmployerToGraph();

		$this->flashMessage('Vymazano');

		if ($this->isAjax()) {
			$this->redrawControl('flashes');
			$this->redrawControl('content');
			$this->redrawControl('scripts');

		} else {
			$this->redirect('Homepage:default');
		}

	}


	public function actionGenerateData(int $count = 100): void
	{
		$faker = Factory::create();

		$x = 0;
		while ($x <= $count) {
			$this->employerRepository->insert([
				'fullName' => $faker->name,
				'age' => (string) rand(18, 100),
				'gender' => array_rand(GenderEnum::getGenders()),
			]);
			$x++;
		}

		$this->redirect('Homepage:default');
	}


	public function actionRemoveEmployerDatabase(): void
	{
		$this->employerRepository->removeDatabase();

		$this->redirect('Homepage:default');
	}

}
