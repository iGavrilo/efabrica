<?php

declare(strict_types = 1);

namespace App\Enums;

enum GenderEnum: string
{

	case MALE = 'M';
	case FEMALE = 'F';
	case OTHER = 'O';


	public function getGender(): string
	{
		return match ($this) {
			self::MALE => 'Male',
			self::FEMALE => 'Female',
			self::OTHER => 'Other',
		};

	}


	/**
	 * @return array<string,string>
	 */
	public static function getGenders(): array
	{
		return [
			self::MALE->value => 'Male',
			self::FEMALE->value => 'Female',
			self::OTHER->value => 'Other',
		];

	}

}