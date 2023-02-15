<?php

namespace Axytos\ECommerce\OrderSync;

use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoInterface;

class OrderHashCalculator
{
    /**
     * @var HashAlgorithmInterface
     */
    private $hashAlgorithm;

    /**
     * @var DtoArrayMapper
     */
    private $dtoArrayMapper;

    public function __construct(HashAlgorithmInterface $hashAlgorithm, DtoArrayMapper $dtoArrayMapper)
    {
        $this->hashAlgorithm = $hashAlgorithm;
        $this->dtoArrayMapper = $dtoArrayMapper;
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $order
     * @return string
     */
    public function computeBasketHash($order)
    {
        return $this->computeDtoHash($order->getBasket());
    }

    /**
     * @return string
     */
    private function computeDtoHash(DtoInterface $dto)
    {
        $arrayDto = $this->dtoArrayMapper->toArray($dto);
        $serializedDto = serialize($arrayDto);
        return $this->hashAlgorithm->compute($serializedDto);
    }
}
