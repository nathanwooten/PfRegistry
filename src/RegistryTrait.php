<?php

namespace nathanwooten;

trait RegistryTrait
{

	public function getRegistry()
	{

		return new RegistryWrapper;

	}

}
