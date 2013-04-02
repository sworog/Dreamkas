<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToRfc3339Transformer as BaseTransformer;

class DateTimeToRfc3339Transformer extends BaseTransformer
{
    /**
     * @param string $rfc3339
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     * @throws \Exception|\Symfony\Component\Form\Exception\TransformationFailedException
     * @throws \Exception|\Symfony\Component\Form\Exception\UnexpectedTypeException
     * @return \DateTime|null
     */
    public function reverseTransform($rfc3339)
    {
        try {
            $dateTime = parent::reverseTransform($rfc3339);
        } catch (UnexpectedTypeException $e) {
            throw $e;
        } catch (TransformationFailedException $e) {
            throw $e;
        } catch (\Exception $e) {
            // Catch invalid DateTime construct message
            throw new TransformationFailedException($e->getMessage());
        }
        return $dateTime;
    }
}
