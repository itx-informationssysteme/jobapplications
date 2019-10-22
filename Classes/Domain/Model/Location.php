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
 * Location
 */
class Location extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     * 
     * @var string
     */
    protected $name = '';

    /**
     * address
     * 
     * @var string
     */
    protected $address = '';

    /**
     * latitude
     * 
     * @var string
     */
    protected $latitude = '';

    /**
     * londitude
     * 
     * @var string
     */
    protected $londitude = '';

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
     * Returns the address
     * 
     * @return string address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     * 
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the latitude
     * 
     * @return string $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     * 
     * @param string $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Returns the londitude
     * 
     * @return string $londitude
     */
    public function getLonditude()
    {
        return $this->londitude;
    }

    /**
     * Sets the londitude
     * 
     * @param string $londitude
     * @return void
     */
    public function setLonditude($londitude)
    {
        $this->londitude = $londitude;
    }
}
