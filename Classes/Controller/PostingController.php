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
    	if($this->request->hasArgument("detail")) {
			$postingUid = $this->request->getArgument("posting");
			$posting = $this->postingRepository->findByUid($postingUid);
			$this->settings = $this->request->getArgument("settings");
			$this->view->assign("settings", $this->settings);
		} else {
			$detailViewUid = $this->settings['detailViewUid'];
			if($detailViewUid) {
				$this->uriBuilder->reset()->setTargetPageUid($detailViewUid);
				$uri = $this->uriBuilder->uriFor('show', array('posting' => $posting, 'detail' => '1', 'appForm' => $this->settings['applicationFormUid'], 'settings' => $this->settings), 'Posting', null, "DetailView");
				$this->redirectToUri($uri);
			}
		}
    	if($this->request->hasArgument("appForm")) {
			$this->view->assign('appForm', $this->request->getArgument("appForm"));
		} else {
			$this->view->assign('appForm', '');
		}
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
