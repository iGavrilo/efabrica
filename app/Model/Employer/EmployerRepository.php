<?php

declare(strict_types = 1);

namespace App\Model\Employer;

use App\Model\BaseXmlRepository;
use App\Model\Employer\Exception\EmployerNotFoundException;

class EmployerRepository extends BaseXmlRepository
{

	private EmployerFactory $employerFactory;


	/**
	 * @param EmployerFactory $employerFactory
	 */
	public function __construct(EmployerFactory $employerFactory)
	{
		parent::__construct();
		$this->employerFactory = $employerFactory;
	}


	protected function setup(): void
	{
		$this->table = 'employers';
	}


	/**
	 * @throws EmployerNotFoundException
	 */
	public function getEmployerById(int $employerId): Employer
	{
		$employer = $this->getById($employerId);

		if ($employer === NULL) {
			throw new EmployerNotFoundException();
		}

		return new Employer($this->employerFactory->createFromArray($employer));
	}


	public function updateEmployer(int $id, EmployerData $employerData): void
	{
		$data = (array) $employerData;
		$data['gender'] = $employerData->gender->value;

		$this->updateById($id, $data);
	}


	public function insertEmployer(EmployerData $employerData): void
	{
		$data = (array) $employerData;
		$data['gender'] = $employerData->gender->value;

		$this->insert($data);
	}


	/**
	 * @return Employer[]
	 */
	public function getAllEmployer(): array
	{
		$data = $this->getAll();

		if ($data === NULL) {
			return [];
		}

		$employers = [];
		foreach ($data as $employer) {
			$employers[] = new Employer($this->employerFactory->createFromArray($employer));
		}

		return $employers;
	}


	/**
	 * @return array<int, array<string, string>>
	 */
	public function getAllEmployerWithoutDTOValidation(): array
	{
		$data = $this->getAll();

		if ($data === NULL) {
			return [];
		}

		$employers = [];
		foreach ($data as $employer) {
			$employers[] = $employer;
		}

		if (count($data[0]) === 0) {
			return [];
		}

		return $employers;
	}


	/**
	 * @return array<string, array<int, mixed>>
	 */
	public function getAllEmployerToGraph(): array
	{
		$data = $this->getAll();

		if ($data === NULL) {
			return ['label' => [], 'age' => []];
		}

		if (count($data[0]) === 0) {
			return ['label' => [], 'age' => []];
		}

		$employers = [];
		foreach ($data as $employer) {
			$employers['label'][] = $employer['fullName'];
			$employers['age'][] = $employer['age'];
		}

		return $employers;
	}

}