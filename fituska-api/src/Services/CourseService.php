<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;

class CourseService
{
    public static function isCodeUnique(EntityManager &$em, string $code): bool
    {
        $result = $em->getRepository("App\Domain\Course")->findBy(array("code" => $code));

        return count($result);
    }

    public static function isCourseApproved(EntityManager &$em, string $code): bool
    {
        $result = $em->getRepository("App\Domain\Course")->findBy(array("code" => $code));

        // code is unique so 
        return $result[0]->getApprovedOn() != null;
    }
}