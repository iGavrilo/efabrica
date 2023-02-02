<?php

declare(strict_types = 1);

namespace App\Components\DataGrid;

use App\Enums\GenderEnum;
use App\Model\Employer\EmployerRepository;
use Ublaboo\DataGrid\DataGrid;

class EmployerGridFactory
{

	private DataGridFactory $factory;

	private EmployerRepository $employerRepository;


	/**
	 * @param DataGridFactory    $factory
	 * @param EmployerRepository $employerRepository
	 */
	public function __construct(DataGridFactory $factory, EmployerRepository $employerRepository)
	{
		$this->factory = $factory;
		$this->employerRepository = $employerRepository;
	}


	public function create(): DataGrid
	{
		$dataGrid = $this->factory->create();
		$dataGrid->setPrimaryKey('id');

		$dataGrid->setDataSource($this->employerRepository->getAllEmployerWithoutDTOValidation());

		$dataGrid->addColumnText('id', 'Interný kód')->setFilterText();

		$dataGrid->addColumnText('fullName', 'Meno zamestnanca')->setFilterText();

		$dataGrid->addColumnText('age', 'Vek')->setFilterText();

		$dataGrid->addColumnText('gender', 'Pohlavie')
			->setRenderer(function ($item) {
				return GenderEnum::getGenders()[$item['gender']];
			})->setFilterSelect(GenderEnum::getGenders())
			->setPrompt('Choose gender');

		$dataGrid->addAction('delete', 'Vymazať', 'removeEmployer!', ['id' => 'id'])
			->setClass('btn-sm btn-danger ajax');

		$dataGrid->addAction('update', 'Upraviť', 'update', ['id' => 'id'])
			->setClass('btn-sm btn-primary');

		$dataGrid->addToolbarButton('Homepage:create', 'Vytvoriť zamestnanca')
			->setClass('btn-sm btn-primary');

		$dataGrid->addToolbarButton('Homepage:removeEmployerDatabase', 'Vymazať tabuľku')
			->setClass('btn-sm btn-primary ajax');

		$dataGrid->addToolbarButton('Homepage:generateData', 'Vygenerovať 100 záznamov')
			->setClass('btn-sm btn-primary ajax');

		return $dataGrid;
	}

}
