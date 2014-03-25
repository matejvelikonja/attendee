<?php

namespace Attendee\Bundle\ApiBundle\Importer;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Config\Util\XmlUtils;

/**
 * Class LocationImporter
 *
 * @package Attendee\Bundle\ApiBundle\Importer
 *
 * @DI\Service("attendee.importer.location")
 */
class LocationImporter
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     *
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $file
     * @param bool   $dryRun
     *
     * @throws ImporterException
     * @return int
     */
    public function import($file, $dryRun = false)
    {
        if (! is_readable($file)) {
            throw new ImporterException('File is not readable.');
        }

        $locations = $this->getLocationsFromKml($file);

        if (! $dryRun) {
            foreach ($locations as $location) {
                $this->em->persist($location);
            }

            $this->em->flush();
        }

        return count($locations);
    }

    /**
     * @param string $file
     *
     * @return Location[]
     */
    private function getLocationsFromKml($file)
    {
        $xml = $this->parseFile($file);
        $locations = array();

        foreach ($xml->{"Document"}->{"Placemark"} as $placeMark) {
            $name        = (string) $placeMark->name;
            $coordinates = (string) $placeMark->{"Point"}->coordinates;

            list($lng, $lat) = explode(',', $coordinates);

            $location = new Location();
            $location
                ->setName($name)
                ->setLat($lat)
                ->setLng($lng);

            $locations[] = $location;
        }

        return $locations;
    }

    /**
     * @param string $file
     *
     * @return \SimpleXMLElement
     *
     * @throws ImporterException
     */
    protected function parseFile($file)
    {
        try {
            $dom = XmlUtils::loadFile($file);
        } catch (\InvalidArgumentException $e) {
            throw new ImporterException(sprintf('Unable to parse file "%s".', $file), $e->getCode(), $e);
        }

        return simplexml_import_dom($dom);
    }
}