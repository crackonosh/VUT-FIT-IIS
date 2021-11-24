<?php
namespace App\Services;

use App\Domain\ApprovedStudent;
use App\Domain\Course;
use App\Domain\User;
use Doctrine\ORM\EntityManager;

class ApprovedStudentService
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function alreadyHasApplication(User $student, Course $course)
    {
        return count(
            $this->em->getRepository(ApprovedStudent::class)
                ->findBy(array('student' => $student->getID(), 'course' => $course->getCode()))
        );
    }
}