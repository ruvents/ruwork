<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

final class ExcelCsvEncoder implements EncoderInterface
{
    const FORMAT = 'excel_csv';

    private $csvEncoder;
    private $defaultDelimiter;

    public function __construct(CsvEncoder $csvEncoder = null, string $defaultDelimiter = ';')
    {
        $this->csvEncoder = $csvEncoder ?? new CsvEncoder();
        $this->defaultDelimiter = $defaultDelimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = [])
    {
        if (!isset($context[CsvEncoder::DELIMITER_KEY])) {
            $context[CsvEncoder::DELIMITER_KEY] = $this->defaultDelimiter;
        }

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
