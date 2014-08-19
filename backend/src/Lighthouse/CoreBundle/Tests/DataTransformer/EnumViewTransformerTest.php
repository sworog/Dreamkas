<?php

namespace Lighthouse\CoreBundle\Tests\DataTransformer;

use Lighthouse\CoreBundle\DataTransformer\EnumViewTransformer;
use Lighthouse\CoreBundle\Test\TestCase;

class EnumViewTransformerTest extends TestCase
{
    /**
     * @dataProvider transformProvider
     * @param mixed $value
     * @param mixed $expected
     */
    public function testTransform($value, $expected)
    {
        $transformer = new EnumViewTransformer();
        $result = $transformer->transform($value);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function transformProvider()
    {
        return array(
            'null' => array(
                null,
                null
            ),
            'array a,b' => array(
                array('a', 'b'),
                'a,b'
            ),
            'empty array' => array(
                array(),
                ''
            )
        );
    }

    /**
     * @dataProvider reverseTransformProvider
     * @param mixed $value
     * @param mixed $expected
     */
    public function testReverseTransform($value, $expected)
    {
        $transformer = new EnumViewTransformer();
        $result = $transformer->reverseTransform($value);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function reverseTransformProvider()
    {
        return array(
            'null' => array(
                null,
                null
            ),
            'string a,b to array' => array(
                'a,b',
                array('a', 'b')
            ),
            'empty string' => array(
                '',
                array()
            ),
            'array to array' => array(
                array(' a ', 'b '),
                array('a', 'b')
            )
        );
    }

    /**
     * @dataProvider invalidTransformValueProvider
     * @param mixed $value
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testInvalidTransformValue($value)
    {
        $transformer = new EnumViewTransformer();
        $transformer->transform($value);
    }

    /**
     * @return array
     */
    public function invalidTransformValueProvider()
    {
        return array(
            'string' => array('aa')
        );
    }

    /**
     * @dataProvider invalidReverseTransformValueProvider
     * @param mixed $value
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testInvalidReverseTransformValue($value)
    {
        $transformer = new EnumViewTransformer();
        $transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function invalidReverseTransformValueProvider()
    {
        return array(
            'object' => array(new \stdClass())
        );
    }
}
