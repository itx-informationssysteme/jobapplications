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
     * @TYPO3\CMS\Extbase\Annotation\Inject
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
}
