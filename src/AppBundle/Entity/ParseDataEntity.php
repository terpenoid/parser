<?php

// src/AppBundle/Entity/ParseDataEntity.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parse_data_entity")
 */
class ParseDataEntity
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;


	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ParseDataSource")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $source;

	/**
	 * @ORM\Column(type="string", length=70)
	 */
	private $source_order_id;


	/**
	 * @ORM\Column(type="integer")
	 */
	private $shop_id;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $status;

	/**
	 * @ORM\Column(type="float")
	 */
	private $total_amount;

	/**
	 * @ORM\Column(type="string", length=3)
	 */
	private $currency;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $event_date;


	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set sourceOrderId
	 *
	 * @param integer $sourceOrderId
	 *
	 * @return ParseDataEntity
	 */
	public function setSourceOrderId($sourceOrderId)
	{
		$this->source_order_id = $sourceOrderId;

		return $this;
	}

	/**
	 * Get sourceOrderId
	 *
	 * @return integer
	 */
	public function getSourceOrderId()
	{
		return $this->source_order_id;
	}

	/**
	 * Set shopId
	 *
	 * @param integer $shopId
	 *
	 * @return ParseDataEntity
	 */
	public function setShopId($shopId)
	{
		$this->shop_id = $shopId;

		return $this;
	}

	/**
	 * Get shopId
	 *
	 * @return integer
	 */
	public function getShopId()
	{
		return $this->shop_id;
	}

	/**
	 * Set status
	 *
	 * @param string $status
	 *
	 * @return ParseDataEntity
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Get status
	 *
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set totalAmount
	 *
	 * @param float $totalAmount
	 *
	 * @return ParseDataEntity
	 */
	public function setTotalAmount($totalAmount)
	{
		$this->total_amount = $totalAmount;

		return $this;
	}

	/**
	 * Get totalAmount
	 *
	 * @return float
	 */
	public function getTotalAmount()
	{
		return $this->total_amount;
	}

	/**
	 * Set currency
	 *
	 * @param string $currency
	 *
	 * @return ParseDataEntity
	 */
	public function setCurrency($currency)
	{
		$this->currency = $currency;

		return $this;
	}

	/**
	 * Get currency
	 *
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * Set eventDate
	 *
	 * @param \DateTime $eventDate
	 *
	 * @return ParseDataEntity
	 */
	public function setEventDate($eventDate)
	{
		$this->event_date = $eventDate;

		return $this;
	}

	/**
	 * Get eventDate
	 *
	 * @return \DateTime
	 */
	public function getEventDate()
	{
		return $this->event_date;
	}

	/**
	 * Set source
	 *
	 * @param \AppBundle\Entity\ParseDataSource $source
	 *
	 * @return ParseDataEntity
	 */
	public function setSource(\AppBundle\Entity\ParseDataSource $source = null)
	{
		$this->source = $source;

		return $this;
	}

	/**
	 * Get source
	 *
	 * @return \AppBundle\Entity\ParseDataSource
	 */
	public function getSource()
	{
		return $this->source;
	}
}
