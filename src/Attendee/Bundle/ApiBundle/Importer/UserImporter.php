<?php

namespace Attendee\Bundle\ApiBundle\Importer;

use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Service\UserService;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class UserImporter
 *
 * @package Attendee\Bundle\ApiBundle\Importer
 *
 * @DI\Service("attendee.importer.user")
 */
class UserImporter
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Attendee\Bundle\ApiBundle\Service\UserService
     */
    private $userService;

    /**
     * @param EntityManager $em
     * @param UserService   $userService
     *
     * @DI\InjectParams({
     *      "em"          = @DI\Inject("doctrine.orm.entity_manager"),
     *      "userService" = @DI\Inject("attendee.user_service"),
     * })
     */
    public function __construct(EntityManager $em, UserService $userService)
    {
        $this->em          = $em;
        $this->userService = $userService;
    }

    /**
     * @param string $file
     * @param bool   $dryRun
     *
     * @throws ImporterException
     *
     * @return int
     */
    public function import($file, $dryRun = false)
    {
        if (! is_readable($file)) {
            throw new ImporterException(sprintf('File %s is not readable.', $file));
        }

        $users = $this->getUsersFromCsv($file);

        if (! $dryRun) {
            foreach ($users as $user) {
                $this->em->persist($user);
            }

            $this->em->flush();
        }

        return count($users);
    }

    /**
     * @param string $file
     *
     * @throws ImporterException
     *
     * @return User[]
     */
    private function getUsersFromCsv($file)
    {
        $csv   = $this->readCsv($file);
        $users = array();

        foreach ($csv as $userData) {
            if (! array_key_exists('email', $userData)) {
                throw new ImporterException('User data has to contain email field.');
            }

            $user = $this->userService->findOneByEmail($userData['email']);

            if (! $user) {
                $user = new User();
            }

            foreach ($userData as $key => $value) {
                $setter = sprintf('set%s', ucfirst($key));
                if (!method_exists($user, $setter)) {
                    throw new ImporterException(sprintf('Property %s does not exists for user object.', $key));
                }
                $user->$setter($value);
            }

            if (strlen($user->getPlainPassword()) === 0) {
                $user
                    ->setPlainPassword('blank')
                    ->setEnabled(false);
            }

            $users[] = $user;
        }

        return $users;
    }

    /**
     * @param string $file
     *
     * @throws ImporterException
     *
     * @return array
     */
    private function readCsv($file)
    {
        $row = 0;
        $result = array();

        if (($handle = fopen($file, 'r')) !== false) {
            $keys = array();
            while (($data = fgetcsv($handle)) !== false) {
                $row++;

                if ($row === 1) {
                    foreach ($data as $keyName) {
                        $keys[] = trim($keyName);
                    }

                    continue;
                }

                if (count($data) !== count($keys)) {
                    throw new ImporterException(sprintf('CSV parsing problem on line %d.', $row));
                }

                $column = 0;
                foreach ($data as $value) {
                    $key = $keys[$column];

                    $result[$row - 2][$key] = trim($value);
                    $column++;
                }
            }
            fclose($handle);
        }

        return $result;
    }
}