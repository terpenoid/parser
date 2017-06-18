<?php

// src/AppBundle/Service/Provider/DataProvider.php

namespace AppBundle\Service\Provider;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class DataProvider
 * @package AppBundle\Service\Provider
 */
abstract class DataProvider
{

	const DATA_DIR = __DIR__ . '/../../../../provider_files/'; // @todo: need best solution!

	const MAPPING_ORDER_ID = 'source_order_id'; // required
	const MAPPING_EVENT_DATE = 'event_date'; // required
	const MAPPING_SHOP_ID = 'shop_id';
	const MAPPING_ORDER_STATUS = 'status';
	const MAPPING_TOTAL_AMOUNT = 'total_amount';
	const MAPPING_CURRENCY = 'currency';

	/* @var $logger Logger */
	protected $logger;

	/**
	 * @return array
	 */
	abstract function getMapper();

	/**
	 * @return array
	 */
	abstract function getData();

	/**
	 * @param $logger
	 * @return $this
	 */
	public function setLogger($logger) {
		$this->logger = $logger;
		return $this;
	}

	/**
	 * @param $data
	 * @return array
	 */
	protected function parseRawData($data)
	{
		$preparedData = [];
		foreach ($data as $row) {
			$preparedRow = [];
			foreach ($this->getMapper() as $key => $field) {
				if (!is_array($field)) {
					$preparedRow[$key] = $row[$field];
				} else {
					switch ($field[0]) {
						case 'const':
							$preparedRow[$key] = $field[1];
							break;
						case 'float':
							$preparedRow[$key] = floatval($row[$field[1]]);
							break;
						case 'int':
							$preparedRow[$key] = intval($row[$field[1]]);
							break;
						case 'datetime':
							$preparedRow[$key] = new \DateTime($row[$field[1]]);
							break;
					}
				}
			}
			$preparedData[] = $preparedRow;
		}
		return $preparedData;
	}
}