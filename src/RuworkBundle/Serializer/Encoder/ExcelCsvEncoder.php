<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

final class ExcelCsvEncoder implements EncoderInterface
{
    const FORMAT = 'excel_csv';

    private $csvEncoder;

    public function __construct(CsvEncoder $csvEncoder)
    {
        $this->csvEncoder = $csvEncoder;
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
        return self::FORMAT === $format && $this->csvEncoder->supportsEncoding($format);
    }
}
