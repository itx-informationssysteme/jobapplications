<?php

namespace ITX\Jobapplications\Event;

use ITX\Jobapplications\Domain\Model\Posting;

class ModifyGoogleForJobsDataEvent
{
	protected array $googleForJobsData;
	protected Posting $posting;
	public function __construct(array $googleForJobsData, Posting $posting) {
		$this->googleForJobsData = $googleForJobsData;
		$this->posting = $posting;
	}

	public function getGoogleForJobsData(): array
	{
		return $this->googleForJobsData;
	}

	public function setGoogleForJobsData(array $googleForJobsData): void
	{
		$this->googleForJobsData = $googleForJobsData;
	}

	public function getPosting(): Posting
	{
		return $this->posting;
	}
}