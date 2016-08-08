<?php

namespace Application\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("Register")
 */
class Register
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Username:"})
     */
    public $username;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Name:"})
     */
    public $name;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Password:"})
     */
    public $password;

     /**
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Email:"})
     */
    public $email;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Submit"})
     */
    public $submit;
}
