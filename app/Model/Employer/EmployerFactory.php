<?php

declare(strict_types = 1);

namespace App\Model\Employer;

use App\Enums\GenderEnum;

class EmployerFactory
{

	/**
	 * @param array<int|string, mixed> $employerData
	 * @return EmployerData
	 */
	public function createFromArray(array $employerData): EmployerData
	{
		$data = new EmployerData();
		$data->id = (int) $employerData['id'];
		$data->fullName = $employerData['fullName'];
		$data->age = (int) $employerData['age'];
		$data->gender = GenderEnum::from($employerData['gender']);

		return $data;
	}

}