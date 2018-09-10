<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

final class ExcelCsvEncoder implements EncoderInterface
{
    public const FORMAT = 'excel_csv';
    private const BOM = "\xEF\xBB\xBF";

    private $csvEncoder;
    private $defaultDelimiter;

    public function __construct(?EncoderInterface $csvEncoder = null, string $defaultDelimiter = ';')
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

        return self::BOM.$this->csvEncoder->encode($data, CsvEncoder::FORMAT, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format)
    {
        return self::FORMAT === $format;
    }
}
