<?php

namespace Lighthouse\CoreBundle\Request\ParamConverter\Filter;

use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ValidatorInterface;
use JMS\DiExtraBundle\Annotation as DI;
use ReflectionClass;

/**
 * @DI\Service("lighthouse.core.converter.filter")
 * @DI\Tag("request.param_converter", attributes={"converter": "filter"})
 */
class FilterParamConverter implements ParamConverterInterface
{
    /**
     * @var ValidatorInterface|ExceptionalValidator
     */
    protected $validator;

    /**
     * @DI\InjectParams({
     *      "validator" = @DI\Inject("lighthouse.core.validator")
     * })
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param ConfigurationInterface|ParamConverter $configuration
     * @return bool|void
     */
    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $filter = $this->createFilter($request, $configuration);
        $this->validateFilter($filter);
        $request->attributes->set($configuration->getName(), $filter);
    }

    /**
     * @param Request $request
     * @param ConfigurationInterface|ParamConverter $configuration
     * @return FilterInterface
     */
    protected function createFilter(Request $request, ConfigurationInterface $configuration)
    {
        $filterClass = $configuration->getClass();
        /* @var FilterInterface $filter */
        $filter = new $filterClass();
        $filter->populate($request->query->all());
        return $filter;
    }

    /**
     * @param FilterInterface $filter
     * @throws BadRequestHttpException
     */
    protected function validateFilter(FilterInterface $filter)
    {
        try {
            $this->validator->validate($filter, null, true, true);
        } catch (ValidationFailedException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    /**
     * @param ConfigurationInterface|ParamConverter $configuration
     * @return bool|void
     */
    public function supports(ConfigurationInterface $configuration)
    {
        $classReflection = new ReflectionClass($configuration->getClass());
        return $classReflection->implementsInterface(
            'Lighthouse\\CoreBundle\\Request\\ParamConverter\\Filter\\FilterInterface'
        );
    }
}
