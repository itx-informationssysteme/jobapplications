<?php
namespace ITX\Jobs\Controller;


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
 * LocationController
 */
class LocationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * locationRepository
     * 
     * @var \ITX\Jobs\Domain\Repository\LocationRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $locationRepository = null;

    /**
     * action list
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @return void
     */
    public function listAction()
    {
        $locations = $this->locationRepository->findAll();
        $this->view->assign('locations', $locations);
    }

    /**
     * action show
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @return void
     */
    public function showAction(\ITX\Jobs\Domain\Model\Location $location)
    {
        $this->view->assign('location', $location);
    }

    /**
     * action new
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @return void
     */
    public function newAction()
    {
    }


    /**
     * action edit
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation $location
     * @return void
     */
    public function editAction(\ITX\Jobs\Domain\Model\Location $location)
    {
        $this->view->assign('location', $location);
    }
}
