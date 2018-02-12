<?php

namespace Ruvents\RuworkBundle\Validator\Constraints;

use Egulias\EmailValidator\Exception;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Email extends Constraint
{
    protected static $errorNames = [
        Exception\AtextAfterCFWS::CODE => Exception\AtextAfterCFWS::REASON,
        Exception\CharNotAllowed::CODE => Exception\CharNotAllowed::REASON,
        Exception\CommaInDomain::CODE => Exception\CommaInDomain::REASON,
        Exception\ConsecutiveAt::CODE => Exception\ConsecutiveAt::REASON,
        Exception\ConsecutiveDot::CODE => Exception\ConsecutiveDot::REASON,
        Exception\CRLFAtTheEnd::CODE => Exception\CRLFAtTheEnd::REASON,
        Exception\CRLFX2::CODE => Exception\CRLFX2::REASON,
        Exception\CRNoLF::CODE => Exception\CRNoLF::REASON,
        Exception\DomainHyphened::CODE => Exception\DomainHyphened::REASON,
        Exception\DotAtEnd::CODE => Exception\DotAtEnd::REASON,
        Exception\DotAtStart::CODE => Exception\DotAtStart::REASON,
        Exception\ExpectingAT::CODE => Exception\ExpectingAT::REASON,
        Exception\ExpectingATEXT::CODE => Exception\ExpectingATEXT::REASON,
        Exception\ExpectingCTEXT::CODE => Exception\ExpectingCTEXT::REASON,
        Exception\ExpectingDomainLiteralClose::CODE => Exception\ExpectingDomainLiteralClose::REASON,
        Exception\ExpectingDTEXT::CODE => Exception\ExpectingDTEXT::REASON,
        Exception\NoDNSRecord::CODE => Exception\NoDNSRecord::REASON,
        Exception\NoDomainPart::CODE => Exception\NoDomainPart::REASON,
        Exception\NoLocalPart::CODE => Exception\NoLocalPart::REASON,
        Exception\UnclosedComment::CODE => Exception\UnclosedComment::REASON,
        Exception\UnclosedQuotedString::CODE => Exception\UnclosedQuotedString::REASON,
        Exception\UnopenedComment::CODE => Exception\UnopenedComment::REASON,
    ];

    /**
     * @var string
     */
    public $message = 'This value is not a valid email address.';

    /**
     * @var bool
     */
    public $checkDNS = false;
}
