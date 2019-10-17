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
 * PostingController
 */
class PostingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * postingRepository
     * 
     * @var \ITX\Jobs\Domain\Repository\PostingRepository
     * @inject
     */
    protected $postingRepository = null;

    /**
     * action list
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @return void
     */
    public function listAction()
    {
        $postings = $this->postingRepository->findAll();
        $this->view->assign('postings', $postings);
    }

    /**
     * action show
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @return void
     */
    public function showAction(\ITX\Jobs\Domain\Model\Posting $posting)
    {
        $this->view->assign('posting', $posting);
    }

    /**
     * action new
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * action create
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @return void
     */
    public function createAction(\ITX\Jobs\Domain\Model\Posting $newPosting)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->postingRepository->add($newPosting);
        $this->redirect('list');
    }

    /**
     * action edit
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @ignorevalidation $posting
     * @return void
     */
    public function editAction(\ITX\Jobs\Domain\Model\Posting $posting)
    {
        $this->view->assign('posting', $posting);
    }

    /**
     * action update
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @return void
     */
    public function updateAction(\ITX\Jobs\Domain\Model\Posting $posting)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->postingRepository->update($posting);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @return void
     */
    public function deleteAction(\ITX\Jobs\Domain\Model\Posting $posting)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->postingRepository->remove($posting);
        $this->redirect('list');
    }

    /**
     * action
     * 
     * @param ITX\Jobs\Domain\Model\Posting
     * @return void
     */
    public function Action()
    {
    }
}
