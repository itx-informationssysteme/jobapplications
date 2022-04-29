<?php

	namespace ITX\Jobapplications\Event;

	use ITX\Jobapplications\Domain\Model\Posting;

	/**
	 *
	 */
	class DisplayPostingEvent
	{
		protected Posting $posting;
		
		public function __construct(Posting $posting) {
			$this->posting = $posting;
		}

		/**
		 * @return Posting
		 */
		public function getPosting(): Posting
		{
			return $this->posting;
		}

		/**
		 * @param Posting $posting
		 */
		public function setPosting(Posting $posting): void
		{
			$this->posting = $posting;
		}
	}