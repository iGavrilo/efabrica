<?php

declare(strict_types = 1);

namespace App\Model\Employer;

use App\Enums\GenderEnum;

class EmployerData
{

	public int $id;

	public string $fullName;

	public int $age;

	public GenderEnum $gender;

}