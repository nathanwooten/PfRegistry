<?php

namespace nathanwooten;

use nathanwooten\RegistryWrapper;

trait RegistryTrait
{

	public function getRegistry()
	{

		return new RegistryWrapper;

	}

}
