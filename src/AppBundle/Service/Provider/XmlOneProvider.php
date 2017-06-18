<?php

// src/AppBundle/Service/Provider/XmlOneProvider.php

namespace AppBundle\Service\Provider;

use Symfony\Component\DomCrawler\Crawler;


/**
 * Class CsvOneProvider
 * @package AppBundle\Provider\Service
 */
class XmlOneProvider extends DataProvider
{

	/**
	 * @return array
	 */
	public function getMapper()
	{
		return [
			self::MAPPING_SHOP_ID => 'advcampaign_id',
			self::MAPPING_ORDER_ID => 'order_id',
			self::MAPPING_ORDER_STATUS => 'status',
			self::MAPPING_TOTAL_AMOUNT => ['float', 'cart'],
			self::MAPPING_CURRENCY => 'currency',
			self::MAPPING_EVENT_DATE => ['datetime', 'action_date'],
		];
	}

	/**
	 * @return array
	 */
	public function getData()
	{

		$xmlFile = self::DATA_DIR . 'xml.xml';

		if (!file_exists($xmlFile)) {
			//throw new \Exception('no xml-file (' . $xmlFile . ')!');
			$this->logger->error('no xml-file (' . $xmlFile . ')!');
			return [];
		}

		$crawler = new Crawler();
		$crawler->addXmlContent(file_get_contents($xmlFile));

		$crawler = $crawler->filter('stats > stat');

		$rawData = [];

		foreach ($crawler as $domElement) {
			$row = [];
			foreach ($domElement->childNodes as $param) {
				$row[$param->nodeName] = $param->nodeValue;
			}
			$rawData[] = $row;
		}

		$preparedData = $this->parseRawData($rawData);

		return $preparedData;

	}

}