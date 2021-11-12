<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;

class ThreadCategoryService
{
    public static function isNameUniqueForCourse(EntityManager &$em, string $name, string $course_code): bool
    {
        $results = $em->getRepository("App\Domain\ThreadCategory")->findBy(array("course" => $course_code, "name" => $name));

        return !count($results);
    }
}