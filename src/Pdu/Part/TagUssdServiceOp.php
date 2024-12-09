<?php


namespace PhpSmpp\Pdu\Part;


class TagUssdServiceOp extends Tag
{
    /**
     * @return static
     */
    public static function build($id,$value)
    {
        $instance = new static(Tag::USSD_SERVICE_OP, $value, 1, 'c');
        return $instance;
    }
}
