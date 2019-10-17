<?php
namespace ITX\Jobs\Domain\Model;


/***
 *
 * This file is part of the "Jobs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Stefanie Döll, it.x informationssysteme gmbh
 *           Benjamin Jasper, it.x informationssysteme gmbh
 *
 ***/
/**
 * A Contact is the person who handles the application process for this posting.
 */
class Contact extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     * 
     * @var string
     */
    protected $name = '';

    /**
     * email
     * 
     * @var string
     */
    protected $email = '';

    /**
     * phone
     * 
     * @var string
     */
    protected $phone = '';

    /**
     * division
     * 
     * @var string
     */
    protected $division = '';

    /**
     * Returns the name
     * 
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     * 
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the email
     * 
     * @return string email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     * 
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the phone
     * 
     * @return string phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone
     * 
     * @param string $phone
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Returns the division
     * 
     * @return string division
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Sets the division
     * 
     * @param string $division
     * @return void
     */
    public function setDivision($division)
    {
        $this->division = $division;
    }
}
