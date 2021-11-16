<?php
namespace App\Services;

use App\Domain\ThreadCategory;
use Doctrine\ORM\EntityManager;

class ThreadCategoryService
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function isNameUniqueForCourse(string $name, string $course_code): bool
    {
        $results = $this->em->getRepository(ThreadCategory::class)
            ->findBy(array("course" => $course_code, "name" => $name));

        return !count($results);
    }
}