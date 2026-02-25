<?php

namespace App\Concerns;

use App\Enum\UserRole;
use Illuminate\Validation\Rule;

trait UserRoleValidationRules
{
	protected function roleRules():array
	{
		return [
			'required',
			'string',
			Rule::in(UserRole::cases())
		];
	}
}
