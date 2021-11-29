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

    public function isApproved(User $requester, Course $course)
    {
        /** @var ApprovedStudent */
        $application = $this->em->getRepository(ApprovedStudent::class)
            ->findOneBy(array('student' => "{$requester->getID()}", 'course' => $course->getCode()));

        if (!$application) return false;
        return $application->getStatus();
    }
}