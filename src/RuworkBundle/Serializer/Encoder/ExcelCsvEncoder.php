<?php

namespace Ruvents\RuworkBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class ExcelCsvEncoder implements EncoderInterface
{
    const FORMAT = 'excel_csv';

    /**
     * @var CsvEncoder
     */
    private $csvEncoder;

    public function __construct(
        string $delimiter = ';',
        string $enclosure = '"',
        string $escapeChar = '\\',
        string $keySeparator = '.'
    ) {
        $this->csvEncoder = new CsvEncoder($delimiter, $enclosure, $escapeChar, $keySeparator);
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = [])
    {
        return "\xEF\xBB\xBF".$this->csvEncoder->encode($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format)
    {
        return self::FORMAT === $format;
    }
}
