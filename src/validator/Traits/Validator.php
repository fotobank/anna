<?php
/**
 * Framework Component
 *
 * @copyright Alex PHP
 *
 */

/**
 * @namespace
 */
namespace validator\Traits;

use validator\ValidatorBuilder;

/**
 * Validator trait
 *
 * Example of usage
 *    use validator\Traits\Validator;
 *    use validator\Validator as v;
 *
 *    class Row extends Db\Row {
 *        use Validator;
 *        function beforeSave()
 *        {
 *             $this->addValidator(
 *                 'login',
 *                 v::required()->latin()->length(3, 255)
 *             );
 *        }
 *    }
 *
 * @package  validator\Traits
 *
 * @author   Alex Jurii
 * @created  04.07.2014 10:14
 */
trait Validator
{
    /**
     * @var ValidatorBuilder Instance of ValidatorBuilder
     */
    protected $validatorBuilder;

    /**
     * Get ValidatorBuilder
     *
     * @return ValidatorBuilder
     */
    protected function getValidatorBuilder()
    {
        if (!$this->validatorBuilder) {
            $this->validatorBuilder = new ValidatorBuilder();
        }
        return $this->validatorBuilder;
    }

    /**
     * Add Validator for field
     *
     * @param string $name
     * @param \validator\Validator[] $validators
     * @return Validator
     */
    protected function addValidator($name)
    {
        $this->getValidatorBuilder()->add($name, array_slice(func_get_args(), 1));
        return $this;
    }

    /**
     * Validate input data
     *
     * @param array|object $input
     * @return boolean
     */
    public function validate($input)
    {
        return $this->getValidatorBuilder()->validate($input);
    }

    /**
     * Assert input data
     *
     * @param array|object $input
     * @return boolean
     */
    public function assert($input)
    {
        return $this->getValidatorBuilder()->assert($input);
    }
}
