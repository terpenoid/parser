<?php

// src/AppBundle/Service/Provider/CsvOneProvider.php

namespace AppBundle\Service\Provider;


/**
 * Class CsvOneProvider
 * @package AppBundle\Provider\Service
 */
class CsvOneProvider extends DataProvider
{

	/**
	 * @return array
	 */
	public function getMapper()
	{
		return [
			self::MAPPING_SHOP_ID => ['const', 1],
			self::MAPPING_ORDER_ID => 18,
			self::MAPPING_ORDER_STATUS => ['const', 'approved'],
			self::MAPPING_TOTAL_AMOUNT => ['float', 15],
			self::MAPPING_CURRENCY => ['const', 'USD'],
			self::MAPPING_EVENT_DATE => ['datetime', 11],
		];
	}

	/**
	 * @return array
	 */
	public function getData()
	{

		$csvFile = self::DATA_DIR . 'csv.csv';

		if (!file_exists($csvFile)) {
			//throw new \Exception('no csv-file (' . $xmlFile . ')!');
			$this->logger->error('no csv-file (' . $csvFile . ')!');
			return [];
		}

		$csvHandle = fopen($csvFile, 'r');

		$rawData = [];

		while ($row = fgetcsv($csvHandle, null, "\t")) {
			if ($row[0] == 'Event Date' || empty($row) // skip headers and spaces
				|| $row[2] != 'Winning Bid (Revenue)' // skip other types
				|| !$row[15] // skip empty amount
			) {
				continue;
			}
			$rawData[] = $row;
		}

		$preparedData = $this->parseRawData($rawData);

		return $preparedData;

	}

}