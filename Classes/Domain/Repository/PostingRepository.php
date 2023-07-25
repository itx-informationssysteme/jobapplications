<?php

namespace ITX\Jobapplications\Domain\Repository;

use Doctrine\DBAL\Exception;
use ITX\Jobapplications\Domain\Model\Constraint;
use ITX\Jobapplications\Domain\Repository\RepoHelpers;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020
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

/**
 * The repository for Postings
 */
class PostingRepository extends JobapplicationsRepository
{
    /**
     * Helper function for finding postings by category
     *
     * @param array $categories
     *
     * @return array|QueryResultInterface
     */
    public function findByCategory(array $categories): QueryResultInterface|array
    {
        $query = $this->createQuery();

        $qb = $this->getQueryBuilder("tx_jobapplications_domain_model_posting");

        $qb->select("*")
           ->from("tx_jobapplications_domain_model_posting")
           ->join("tx_jobapplications_domain_model_posting",
                  "sys_category_record_mm",
                  "sys_category_record_mm",
                  "tx_jobapplications_domain_model_posting.uid = sys_category_record_mm.uid_foreign")
           ->andWhere($qb->expr()->in('pid', $query->getQuerySettings()->getStoragePageIds()));

        $qb = $this->buildCategoriesToSQL($categories, $qb);

        $query->statement($qb->getSQL());

        return $query->execute();
    }

    /**
     * Gets all divisions
     *
     * @param array|null $categories
     * @param int        $languageUid
     *
     * @return array
     * @throws Exception
     */
    public function findAllDivisions(array $categories = null, int $languageUid): array
    {
        $qb = $this->getQueryBuilder("tx_jobapplications_domain_model_posting");
        $query = $this->createQuery();

        if (count($categories) === 0) {
            $qb->select("division", "sys_language_uid")
               ->groupBy("division", "sys_language_uid")
               ->from("tx_jobapplications_domain_model_posting")
               ->andWhere($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
               ->andWhere($qb->expr()->eq("sys_language_uid", $languageUid));
        } else {
            $qb->select("division", "sys_language_uid")
               ->groupBy("division", "sys_language_uid")
               ->from("tx_jobapplications_domain_model_posting")
               ->andWhere($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
               ->andWhere($qb->expr()->eq("sys_language_uid", $languageUid))
               ->join("tx_jobapplications_domain_model_posting",
                      "sys_category_record_mm",
                      "sys_category_record_mm",
                      $qb->expr()->eq("tx_jobapplications_domain_model_posting.uid",
                                      "sys_category_record_mm.uid_foreign"))
               ->orderBy('division', QueryInterface::ORDER_ASCENDING);
            $qb = $this->buildCategoriesToSQL($categories, $qb);
        }

        /** @var array $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return array_column($result, 'division');
    }

    /**
     * @param array|null $categories
     * @param int        $languageUid
     *
     * @return array
     * @throws Exception
     */
    public function findAllCareerLevels(array $categories = null, int $languageUid): array
    {
        $qb = $this->getQueryBuilder("tx_jobapplications_domain_model_posting");

        $query = $this->createQuery();

        if (count($categories) === 0) {
            $qb->select("career_level AS careerLevel", "sys_language_uid")
               ->groupBy("careerLevel", "sys_language_uid")
               ->from("tx_jobapplications_domain_model_posting")
               ->where($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
               ->andWhere($qb->expr()->eq("sys_language_uid", $languageUid));
        } else {
            $qb->select("career_level AS careerLevel", "sys_language_uid")
               ->groupBy("careerLevel", "sys_language_uid")
               ->from("tx_jobapplications_domain_model_posting")
               ->where($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
               ->andWhere($qb->expr()->eq("sys_language_uid", $languageUid))
               ->join("tx_jobapplications_domain_model_posting",
                      "sys_category_record_mm",
                      "sys_category_record_mm",
                      $qb->expr()->eq("tx_jobapplications_domain_model_posting.uid",
                                      "sys_category_record_mm.uid_foreign"))
               ->orderBy('careerLevel', QueryInterface::ORDER_ASCENDING);
            $qb = $this->buildCategoriesToSQL($categories, $qb);
        }

        /** @var array $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return array_column($result, 'careerLevel');
    }

    /**
     * @param array|null $categories
     * @param int        $languageUid
     *
     * @return array
     * @throws Exception
     */
    public function findAllEmploymentTypes(array $categories = null, int $languageUid): array
    {
        $qb = $this->getQueryBuilder("tx_jobapplications_domain_model_posting");

        $query = $this->createQuery();

        if (count($categories) === 0) {
            $qb->select("employment_type AS employmentType", "sys_language_uid")
               ->groupBy("employmentType", "sys_language_uid")
               ->from("tx_jobapplications_domain_model_posting")
               ->andWhere($qb->expr()->in('pid', $query->getQuerySettings()->getStoragePageIds()))
               ->andWhere($qb->expr()->eq("sys_language_uid", $languageUid))
               ->orderBy('employmentType', QueryInterface::ORDER_ASCENDING);
        } else {
            $qb->select("employment_type AS employmentType", "sys_language_uid")
               ->groupBy("employmentType", "sys_language_uid")
               ->from("tx_jobapplications_domain_model_posting")
               ->where($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
               ->andWhere($qb->expr()->eq("sys_language_uid", $languageUid))
               ->join("tx_jobapplications_domain_model_posting",
                      "sys_category_record_mm",
                      "sys_category_record_mm",
                      $qb->expr()->eq("tx_jobapplications_domain_model_posting.uid",
                                      "sys_category_record_mm.uid_foreign"))
               ->orderBy('employmentType', QueryInterface::ORDER_ASCENDING);
            $qb = $this->buildCategoriesToSQL($categories, $qb);

        }

        /** @var array $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        $return = [];
        foreach (array_column($result, 'employmentType') as $string) {
            $explodedString = explode(',', $string);
            if (count($explodedString) < 2) {
                $return[] = $string;
            } else {
                $return = array_merge($return, $explodedString);
            }
        }

        $result = array_unique($return);
        asort($result);

        return $result;
    }

    /**
     * @param $categories
     *
     * @return array|QueryResultInterface
     * @throws Exception
     */
    public function findAllCategories($categories)
    {
        $sb = $this->getQueryBuilder("sys_category");
        $sb->select("sys_category.*")
           ->from("sys_category")
           ->join("sys_category",
                  "sys_category_record_mm",
                  "sys_category_record_mm",
                  $sb->expr()
                     ->eq("sys_category.uid", "sys_category_record_mm.uid_local"))
           ->join("sys_category_record_mm",
                  "tx_jobapplications_domain_model_posting",
                  'tx_jobapplications_domain_model_posting',
                  $sb->expr()->eq("tx_jobapplications_domain_model_posting.uid", "sys_category_record_mm.uid_foreign"))
           ->groupBy('uid')
           ->orderBy('title', QueryInterface::ORDER_ASCENDING);
        $sb = $this->buildCategoriesToSQL($categories, $sb);

        /** @var array $result */
        return $sb->executeQuery()->fetchAllAssociative();
    }

    /**
     * @param array           $categories
     * @param array           $repositoryConfig
     * @param Constraint|null $constraint
     * @param string          $orderBy
     * @param string          $order
     *
     * @return array|QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     * @throws \TYPO3\CMS\Extbase\Reflection\Exception\UnknownClassException
     */
    public function findByFilter(array      $categories,
                                 array      $repositoryConfig,
                                 Constraint $constraint = null,
                                            $orderBy = 'date_posted',
                                            $order = QueryInterface::ORDER_DESCENDING)
    {
        $query = $this->createQuery();

        $andConstraints = [];

        if ($constraint instanceof Constraint) {
            /** @var ReflectionService $reflection */
            $reflection = GeneralUtility::makeInstance(ReflectionService::class);
            $schema = $reflection->getClassSchema($constraint);
            $constraints = $schema->getProperties();

            foreach ($constraints as $index => $property) {
                // TYPO3 v9 compatibility
                $propertyName = is_array($property) ? $index : $property->getName();

                $propertyMethodName = GeneralUtility::underscoredToUpperCamelCase($propertyName);
                $array = $constraint->{'get' . $propertyMethodName}();
                if (empty($array)) {
                    continue;
                }

                $orConstraints = [];
                foreach ($array as $input) {
                    //Skip empty values. The prepend option, associated with 'All', returns this empty string.
                    if ($input === '') {
                        continue;
                    }

                    if ($repositoryConfig[$propertyName] === null) {
                        throw new \RuntimeException("Missing TypoScript repository config for property: " . $propertyName);
                    }

                    $orConstraints[] = match ($repositoryConfig[$propertyName]['relationType']) {
                        'equals' => $query->equals($repositoryConfig[$propertyName]['relation'], $input),
                        'contains' => $query->contains($repositoryConfig[$propertyName]['relation'], $input),
                        'in' => $query->in($repositoryConfig[$propertyName]['relation'], $input),
                        default => throw new \InvalidArgumentException(sprintf('Invalid relation type %s',
                                                                               $repositoryConfig[$propertyName]['relationType'])),
                    };
                }

                if (count($orConstraints) > 0) {
                    $andConstraints[] = $query->logicalOr(...$orConstraints);
                }
            }
        }

        if (!empty($categories)) {
            $orConstraints = [];

            foreach ($categories as $category) {
                $orConstraints[] = $query->contains('categories', $category);
            }

            $andConstraints[] = $query->logicalOr(...$orConstraints);
        }

        if (!empty($andConstraints)) {
            $query->matching($query->logicalAnd(...$andConstraints));
        }

        $query->setOrderings([$orderBy => $order]);

        return $query->execute();
    }

    /**
     * Only for use in Backend context
     *
     * @param $contact int Contact Uid
     * @param $orderBy string
     * @param $order   string
     *
     * @return array|QueryResultInterface
     */
    public function findByContact(int $contact, string $orderBy = "title", string $order = QueryInterface::ORDER_ASCENDING)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false)->setIgnoreEnableFields(true);

        $query->matching($query->equals("contact.uid", $contact));
        $query->setOrderings([$orderBy => $order]);

        return $query->execute();
    }

    /**
     * Returns all objects of this repository. Only for use in backend context
     *
     * @return QueryResultInterface|array
     */
    public function findAllWithOrderIgnoreEnable(string $orderBy = "title", string $order = QueryInterface::ORDER_ASCENDING)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false)->setIgnoreEnableFields(true);

        $query->setOrderings([
                                 $orderBy => $order
                             ]);

        return $query->execute();
    }

    /**
     * @return array|QueryResultInterface
     */
    public function findAllIgnoreStoragePage()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query->execute();
    }

    /**
     * Returns !all! objects of this repository, no matter if hidden or deleted.
     *
     * @return QueryResultInterface|array
     */
    public function findAllIncludingHiddenAndDeleted(): QueryResultInterface|array
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false)->setIgnoreEnableFields(true)->setIncludeDeleted(true);

        return $query->execute();
    }
}
