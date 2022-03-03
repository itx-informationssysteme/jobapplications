<?php
	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2019
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

	namespace ITX\Jobapplications\Widgets\Provider;

	use ITX\Jobapplications\Domain\Repository\PostingRepository;
	use TYPO3\CMS\Dashboard\Widgets\NumberWithIconDataProviderInterface;

	/**
	 * Class PostingsActive
	 *
	 * @package ITX\Jobapplications\Widgets
	 */
	class PostingsActiveProvider implements NumberWithIconDataProviderInterface
	{
		protected PostingRepository $postingRepository;

		public function __construct(PostingRepository $postingRepository)
		{
			$this->postingRepository = $postingRepository;
		}

		public function getNumber(): int
		{
			return $this->postingRepository->findAllIgnoreStoragePage()->count();
		}
	}