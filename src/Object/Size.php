<?php
/**
 * Spartoo API
 *
 * @author debuss-a <zizilex@gmail.com>
 * @copyright 2020 debuss-a
 * @license https://github.com/debuss/spartoo/blob/master/LICENSE.md MIT License
 */

namespace Spartoo\Object;

use DOMDocument;
use DOMException;
use Spartoo\Exception\InvalidArgumentException;
use Spartoo\Exception\XMLFileException;
use Spartoo\Interfaces\XMLTransformerInterface;
use Spartoo\Provisionning;

/**
 * Class Size
 *
 * @package Spartoo
 */
class Size implements XMLTransformerInterface
{

    /** @var string */
    protected $size_name;

    /** @var int */
    protected $size_quantity;

    /** @var string */
    protected $size_reference;

    /** @var string */
    protected $ean;

    /** @var float */
    protected $product_price;

    /**
     * Size constructor.
     * @param string $size_name
     * @param int $size_quantity
     * @param string $size_reference
     * @param null|string $ean
     * @param float|null $product_price
     * @throws InvalidArgumentException
     * @throws XMLFileException
     */
    public function __construct(string $size_name, int $size_quantity, string $size_reference, ?string $ean = null, ?float $product_price = null)
    {
        $this->setSizeName($size_name);

        $this->size_quantity = $size_quantity;
        $this->size_reference = $size_reference;
        $this->ean = $ean;
        $this->product_price = $product_price;
    }

    /**
     * @return string
     */
    public function getSizeName(): string
    {
        return $this->size_name;
    }

    /**
     * @param string $size_name
     * @return Size
     * @throws InvalidArgumentException
     * @throws \Spartoo\Exception\XMLFileException
     */
    public function setSizeName(?string $size_name): Size
    {
        if ($size_name && !in_array($size_name, array_column(Provisionning::getInstance()->getSizes(), 'size_name'))) {
            throw InvalidArgumentException::notSupportedSize($size_name);
        }

        $this->size_name = $size_name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSizeQuantity(): int
    {
        return $this->size_quantity;
    }

    /**
     * @param int $size_quantity
     * @return Size
     */
    public function setSizeQuantity(int $size_quantity): Size
    {
        $this->size_quantity = $size_quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getSizeReference(): string
    {
        return $this->size_reference;
    }

    /**
     * @param string $size_reference
     * @return Size
     */
    public function setSizeReference(string $size_reference): Size
    {
        $this->size_reference = $size_reference;
        return $this;
    }

    /**
     * @return string
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @param string $ean
     * @return Size
     */
    public function setEan(?string $ean): Size
    {
        $this->ean = $ean;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getProductPrice(): ?float
    {
        return $this->product_price;
    }

    /**
     * @param float|null $product_price
     */
    public function setProductPrice(?float $product_price): void
    {
        $this->product_price = $product_price;
    }

    /**
     * @inheritDoc
     */
    public function toXML(DOMDocument $document)
    {
        $size = $document->createElement('size');

        foreach (array_filter(get_object_vars($this)) as $property => $value) {
            try {
                // Escape the value to prevent invalid characters
                $escapedValue = htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
                $size->appendChild($document->createElement($property, $escapedValue));
            } catch (DOMException $e) {
                continue;
            }
        }

        return $size;
    }
}
