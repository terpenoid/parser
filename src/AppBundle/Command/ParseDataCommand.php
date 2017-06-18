<?php

// src/AppBundle/Command/ParseDataCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\ParseDataEntity;
use AppBundle\Entity\ParseDataSource;
use AppBundle\Service\Provider\DataProvider;
use Doctrine\Common\Util\Inflector;
use Monolog\Logger;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ParseDataCommand extends ContainerAwareCommand
{

	protected function configure()
	{
		$this
			->setName('app:parse-data')// as example: php bin/console app:parse-data xmlOne
			->setDescription('Parse data from providers by list')
			->addArgument('providers', InputArgument::REQUIRED, 'The list of providers, separates by comma');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		/* @var $logger Logger */
		$logger = $this->getContainer()->get('monolog.logger.console');
		$logger->info('Parser has been started...');
		$output->writeln('Parser has been started...');

		$inputProviderNames = explode(',', $input->getArgument('providers'));
		$logger->info('Providers: ' . implode(', ', $inputProviderNames));
		$output->writeln('Providers: ' . implode(', ', $inputProviderNames));

		/* @var $entityManager \Doctrine\ORM\EntityManager */
		$entityManager = $this->getContainer()->get('doctrine')->getManager();

		$sourcesRepository = $entityManager->getRepository('AppBundle:ParseDataSource');
		$dataSources = $sourcesRepository->findBy(['name' => $inputProviderNames]);

		/* @var $source ParseDataSource */
		foreach ($dataSources as $source) {

			$logger->info('Source [' . $source->getName() . '](' . $source->getId() . '):');
			$output->writeln('Source [' . $source->getName() . '](' . $source->getId() . '):');

			if (!class_exists($source->getProvider())) {
				//throw new \Exception('no provider-class-file!');
				$logger->error('no provider-class-file ('.$source->getProvider().')!');
				continue;
			}

		
			
					/* @var $provider DataProvider */
			$provider = $this->getContainer()->get($source->getProvider());
			$provider->setLogger($logger);

			$preparedData = $provider->getData();
			$logger->info('Prepared data: ' . count($preparedData) . ' rows');
			$output->writeln('Prepared data: ' . count($preparedData) . ' rows');

			$entityRepository = $entityManager->getRepository('AppBundle:ParseDataEntity');

			foreach ($preparedData as $dataRow) {

				$dataEntity = $entityRepository->findOneBy([
					'source' => $source->getId(),
					'source_order_id' => $dataRow[DataProvider::MAPPING_ORDER_ID],
				]);

				if (!is_null($dataEntity)) {
					if ($dataRow[DataProvider::MAPPING_EVENT_DATE] <= $dataEntity->getEventDate()) {
						continue;
					}
				} else {
					$dataEntity = new ParseDataEntity();
					$dataEntity->setSource($source);
					$dataEntity->setSourceOrderId($dataRow[DataProvider::MAPPING_ORDER_ID]);
				}

				// update/insert data if need

				foreach (array_keys($provider->getMapper()) as $field) {
					if (!property_exists($dataEntity, $field)) {
						throw new \Exception('no property `' . $field . '`!');
					}
					$key = 'set' . ucfirst(Inflector::camelize($field));
					$dataEntity->{$key}($dataRow[$field]);
				}

				$entityManager->persist($dataEntity);
				$entityManager->flush();

				$output->writeln('ID: ' . $dataRow[DataProvider::MAPPING_ORDER_ID]);

			}

			$source->setUpDate(new \DateTime());

			$logger->info('Provider-info has been processed');
			$output->writeln('Provider-info has been processed');

		}

		$logger->info('Parsing has been finished');
		$output->writeln('Parsing has been finished');

	}

}
