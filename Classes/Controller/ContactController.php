<?php
namespace ITX\Jobs\Controller;


use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
 * ContactController
 */
class ContactController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * contactRepository
     * 
     * @var \ITX\Jobs\Domain\Repository\ContactRepository
     * @inject
     */
    protected $contactRepository = null;

    /**
     * action list
     * 
     * @param ITX\Jobs\Domain\Model\Contact
     * @return void
     */
    public function listAction()
    {
    	$contacts = array();
    	$selectedContactsStr = $this->settings["selectedContacts"];
    	if ($selectedContactsStr) {
			$contacts = explode(",",$selectedContactsStr);
		}
		$contactObjects = $this->contactRepository->findMultipleByUid($contacts);
        $this->view->assign('contacts', $contactObjects);
    }

    /**
     * action show
     * 
     * @param ITX\Jobs\Domain\Model\Contact
     * @return void
     */
    public function showAction(\ITX\Jobs\Domain\Model\Contact $contact)
    {
        $this->view->assign('contact', $contact);
    }

    /**
     * action new
     * 
     * @param ITX\Jobs\Domain\Model\Contact
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * action create
     * 
     * @param ITX\Jobs\Domain\Model\Contact
     * @return void
     */
    public function createAction(\ITX\Jobs\Domain\Model\Contact $newContact)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->contactRepository->add($newContact);
        $this->redirect('list');
    }

    /**
     * action edit
     * 
     * @param ITX\Jobs\Domain\Model\Contact
     * @ignorevalidation $contact
     * @return void
     */
    public function editAction(\ITX\Jobs\Domain\Model\Contact $contact)
    {
        $this->view->assign('contact', $contact);
    }

    /**
     * action update
     * 
     * @param ITX\Jobs\Domain\Model\Contact
     * @return void
     */
    public function updateAction(\ITX\Jobs\Domain\Model\Contact $contact)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->contactRepository->update($contact);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param ITX\Jobs\Domain\Model\Contact
     * @return void
     */
    public function deleteAction(\ITX\Jobs\Domain\Model\Contact $contact)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->contactRepository->remove($contact);
        $this->redirect('list');
    }
}
