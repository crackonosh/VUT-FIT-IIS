<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;
use App\Domain\Course;

class CourseService
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function isCodeUnique(string $code): bool
    {
        $result = $this->em->find(Course::class, $code);

        return count($result);
    }

    public function isCourseApproved(string $code): bool
    {
        $result = $this->em->find(Course::class, $code);

        return $result->getApprovedOn() != null;
    }
}