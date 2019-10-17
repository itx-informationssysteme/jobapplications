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
 * ApplicationController
 */
class ApplicationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $applications = $this->applicationRepository->findAll();
        $this->view->assign('applications', $applications);
    }

    /**
     * action show
     * 
     * @param \ITX\JobsExtension\Domain\Model\Application $application
     * @return void
     */
    public function showAction(\ITX\JobsExtension\Domain\Model\Application $application)
    {
        $this->view->assign('application', $application);
    }

    /**
     * action new
     * 
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * action create
     * 
     * @param \ITX\JobsExtension\Domain\Model\Application $newApplication
     * @return void
     */
    public function createAction(\ITX\JobsExtension\Domain\Model\Application $newApplication)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->applicationRepository->add($newApplication);
        $this->redirect('list');
    }

    /**
     * action edit
     * 
     * @param \ITX\JobsExtension\Domain\Model\Application $application
     * @ignorevalidation $application
     * @return void
     */
    public function editAction(\ITX\JobsExtension\Domain\Model\Application $application)
    {
        $this->view->assign('application', $application);
    }

    /**
     * action update
     * 
     * @param \ITX\JobsExtension\Domain\Model\Application $application
     * @return void
     */
    public function updateAction(\ITX\JobsExtension\Domain\Model\Application $application)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->applicationRepository->update($application);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param \ITX\JobsExtension\Domain\Model\Application $application
     * @return void
     */
    public function deleteAction(\ITX\JobsExtension\Domain\Model\Application $application)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->applicationRepository->remove($application);
        $this->redirect('list');
    }
}
