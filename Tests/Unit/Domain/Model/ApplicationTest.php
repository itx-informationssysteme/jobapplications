<?php

	namespace ITX\Jobapplications\Tests\Unit\Domain\Model;

	/**
	 * Test case.
	 *
	 * @author Stefanie Döll
	 * @author Benjamin Jasper
	 */
	class ApplicationTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
	{
		/**
		 * @var \ITX\Jobapplications\Domain\Model\Application
		 */
		protected $subject = null;

		/**
		 * @test
		 */
		public function getSalutationReturnsInitialValueForInt()
		{
			self::assertSame(
				0,
				$this->subject->getSalutation()
			);
		}

		/**
		 * @test
		 */
		public function setSalutationForIntSetsSalutation()
		{
			$this->subject->setSalutation(12);

			self::assertAttributeEquals(
				12,
				'salutation',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getFirstNameReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getFirstName()
			);
		}

		/**
		 * @test
		 */
		public function setFirstNameForStringSetsFirstName()
		{
			$this->subject->setFirstName('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'firstName',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getLastNameReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getLastName()
			);
		}

		/**
		 * @test
		 */
		public function setLastNameForStringSetsLastName()
		{
			$this->subject->setLastName('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'lastName',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getEmailReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getEmail()
			);
		}

		/**
		 * @test
		 */
		public function setEmailForStringSetsEmail()
		{
			$this->subject->setEmail('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'email',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getPhoneReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getPhone()
			);
		}

		/**
		 * @test
		 */
		public function setPhoneForStringSetsPhone()
		{
			$this->subject->setPhone('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'phone',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getAddressStreetAndNumberReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getAddressStreetAndNumber()
			);
		}

		/**
		 * @test
		 */
		public function setAddressStreetAndNumberForStringSetsAddressStreetAndNumber()
		{
			$this->subject->setAddressStreetAndNumber('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'addressStreetAndNumber',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getAddressAdditionReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getAddressAddition()
			);
		}

		/**
		 * @test
		 */
		public function setAddressAdditionForStringSetsAddressAddition()
		{
			$this->subject->setAddressAddition('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'addressAddition',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getAddressPostCodeReturnsInitialValueForInt()
		{
			self::assertSame(
				0,
				$this->subject->getAddressPostCode()
			);
		}

		/**
		 * @test
		 */
		public function setAddressPostCodeForIntSetsAddressPostCode()
		{
			$this->subject->setAddressPostCode(12);

			self::assertAttributeEquals(
				12,
				'addressPostCode',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getAddressCityReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getAddressCity()
			);
		}

		/**
		 * @test
		 */
		public function setAddressCityForStringSetsAddressCity()
		{
			$this->subject->setAddressCity('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'addressCity',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getAddressCountryReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getAddressCountry()
			);
		}

		/**
		 * @test
		 */
		public function setAddressCountryForStringSetsAddressCountry()
		{
			$this->subject->setAddressCountry('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'addressCountry',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getSalaryExpectationReturnsInitialValueForString()
		{
			self::assertSame(
				'',
				$this->subject->getSalaryExpectation()
			);
		}

		/**
		 * @test
		 */
		public function setSalaryExpectationForStringSetsSalaryExpectation()
		{
			$this->subject->setSalaryExpectation('Conceived at T3CON10');

			self::assertAttributeEquals(
				'Conceived at T3CON10',
				'salaryExpectation',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getEarliestDateOfJoiningReturnsInitialValueForDateTime()
		{
			self::assertEquals(
				null,
				$this->subject->getEarliestDateOfJoining()
			);
		}

		/**
		 * @test
		 */
		public function setEarliestDateOfJoiningForDateTimeSetsEarliestDateOfJoining()
		{
			$dateTimeFixture = new \DateTime();
			$this->subject->setEarliestDateOfJoining($dateTimeFixture);

			self::assertAttributeEquals(
				$dateTimeFixture,
				'earliestDateOfJoining',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getCvReturnsInitialValueForFileReference()
		{
			self::assertEquals(
				null,
				$this->subject->getCv()
			);
		}

		/**
		 * @test
		 */
		public function setCvForFileReferenceSetsCv()
		{
			$fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
			$this->subject->setCv($fileReferenceFixture);

			self::assertAttributeEquals(
				$fileReferenceFixture,
				'cv',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getCoverLetterReturnsInitialValueForFileReference()
		{
			self::assertEquals(
				null,
				$this->subject->getCoverLetter()
			);
		}

		/**
		 * @test
		 */
		public function setCoverLetterForFileReferenceSetsCoverLetter()
		{
			$fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
			$this->subject->setCoverLetter($fileReferenceFixture);

			self::assertAttributeEquals(
				$fileReferenceFixture,
				'coverLetter',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getTestimonialsReturnsInitialValueForFileReference()
		{
			self::assertEquals(
				null,
				$this->subject->getTestimonials()
			);
		}

		/**
		 * @test
		 */
		public function setTestimonialsForFileReferenceSetsTestimonials()
		{
			$fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
			$this->subject->setTestimonials($fileReferenceFixture);

			self::assertAttributeEquals(
				$fileReferenceFixture,
				'testimonials',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getOtherFilesReturnsInitialValueForFileReference()
		{
			self::assertEquals(
				null,
				$this->subject->getOtherFiles()
			);
		}

		/**
		 * @test
		 */
		public function setOtherFilesForFileReferenceSetsOtherFiles()
		{
			$fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
			$this->subject->setOtherFiles($fileReferenceFixture);

			self::assertAttributeEquals(
				$fileReferenceFixture,
				'otherFiles',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getPrivacyAgreementReturnsInitialValueForBool()
		{
			self::assertSame(
				false,
				$this->subject->getPrivacyAgreement()
			);
		}

		/**
		 * @test
		 */
		public function setPrivacyAgreementForBoolSetsPrivacyAgreement()
		{
			$this->subject->setPrivacyAgreement(true);

			self::assertAttributeEquals(
				true,
				'privacyAgreement',
				$this->subject
			);
		}

		/**
		 * @test
		 */
		public function getPostingReturnsInitialValueForPosting()
		{
			self::assertEquals(
				null,
				$this->subject->getPosting()
			);
		}

		/**
		 * @test
		 */
		public function setPostingForPostingSetsPosting()
		{
			$postingFixture = new \ITX\Jobapplications\Domain\Model\Posting();
			$this->subject->setPosting($postingFixture);

			self::assertAttributeEquals(
				$postingFixture,
				'posting',
				$this->subject
			);
		}

		protected function setUp()
		{
			parent::setUp();
			$this->subject = new \ITX\Jobapplications\Domain\Model\Application();
		}

		protected function tearDown()
		{
			parent::tearDown();
		}
	}
