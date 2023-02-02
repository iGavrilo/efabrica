<?php

declare(strict_types = 1);

namespace App\Model\Employer;

use App\Enums\GenderEnum;

class Employer
{

	private int $id;

	private string $fullName;

	private int $age;

	private GenderEnum $gender;


	public function __construct(EmployerData $data)
	{
		$this->set($data);
	}


	public function set(EmployerData $data): void
	{
		$this->id = $data->id;
		$this->fullName = $data->fullName;
		$this->age = $data->age;
		$this->gender = $data->gender;
	}


	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getFullName(): string
	{
		return $this->fullName;
	}


	/**
	 * @return int
	 */
	public function getAge(): int
	{
		return $this->age;
	}


	/**
	 * @return GenderEnum
	 */
	public function getGender(): GenderEnum
	{
		return $this->gender;
	}



}