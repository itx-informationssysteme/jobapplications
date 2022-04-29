<?php

	namespace ITX\Jobapplications\Event;



	use ITX\Jobapplications\Domain\Model\Application;

	/**
	 *
	 */
	class BeforeApplicationPersisted
	{
		protected Application $application;
		
		public function __construct(Application $application) {
			$this->application = $application;
		}

		/**
		 * @return Application
		 */
		public function getApplication(): Application
		{
			return $this->application;
		}

		/**
		 * @param Application $application
		 */
		public function setApplication(Application $application): void
		{
			$this->application = $application;
		}
	}