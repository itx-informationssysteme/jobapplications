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
     * @inject
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
     * action create
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @return void
     */
    public function createAction(\ITX\Jobs\Domain\Model\Location $newLocation)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->locationRepository->add($newLocation);
        $this->redirect('list');
    }

    /**
     * action edit
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @ignorevalidation $location
     * @return void
     */
    public function editAction(\ITX\Jobs\Domain\Model\Location $location)
    {
        $this->view->assign('location', $location);
    }

    /**
     * action update
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @return void
     */
    public function updateAction(\ITX\Jobs\Domain\Model\Location $location)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->locationRepository->update($location);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param ITX\Jobs\Domain\Model\Location
     * @return void
     */
    public function deleteAction(\ITX\Jobs\Domain\Model\Location $location)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->locationRepository->remove($location);
        $this->redirect('list');
    }
}
