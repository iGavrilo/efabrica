<?php

declare(strict_types = 1);

namespace App\Components\DataGrid;

use Ublaboo\DataGrid\DataGrid;

class DataGridFactory
{

	public function create(): DataGrid
	{
		$dataGrid = new DataGrid();
		$dataGrid->setItemsPerPageList([10, 50, 100, 500, 1000]);
		$dataGrid->setRememberState(FALSE);

		return $dataGrid;
	}

}
