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
    private $mainPid = 1;

	public function initializeAction()
	{
		$this->mainPid = $GLOBALS['TSFE']->id;
	}

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
    public function showAction(\ITX\Jobs\Domain\Model\Posting $posting = null)
    {
		$this->view->assign('posting', $posting);
    }

    /**
     * action
     * 
     * @return void
     */
    public function Action()
    {
    }
}
