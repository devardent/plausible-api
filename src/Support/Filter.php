<?php

namespace Devarts\PlausiblePHP\Support;

use InvalidArgumentException;

class Filter
{
    public const EQUAL = '==';
    public const NOT_EQUAL = '!=';

    public const SUPPORTED_COMPARISONS = [
        self::EQUAL,
        self::NOT_EQUAL,
    ];

    /**
     * @var string[]
     */
    private array $filters = [];

    public static function create(): self
    {
        return new self();
    }

    /**
     * @param scalar|array $value
     * @throws InvalidArgumentException
     */
    public function add(string $name, $value, string $comparison = self::EQUAL): self
    {
        if (! is_array($value) && ! is_scalar($value)) {
            throw new InvalidArgumentException('Value must be either array or scalar');
        }

        if (is_array($value) && $comparison === self::NOT_EQUAL) {
            throw new InvalidArgumentException('Cannot filter multiple values with `!=` comparison');
        }

        if (! in_array($comparison, self::SUPPORTED_COMPARISONS)) {
            throw new InvalidArgumentException("Unsupported comparison provided: `$comparison`");
        }

        $filters = clone $this;

        $filters->filters[] = $name . $comparison . (is_array($value) ? implode('|', $value) : $value);

        return $filters;
    }

    public function addEventName($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::EVENT_NAME, $value, $comparison);
    }

    public function addEventGoal($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::EVENT_GOAL, $value, $comparison);
    }

    public function addEventHostname($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::EVENT_HOSTNAME, $value, $comparison);
    }

    public function addEventPage($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::EVENT_PAGE, $value, $comparison);
    }

    public function addEventCustomProperty(string $property, $value, string $comparison = self::EQUAL): self
    {
        return $this->add("event:props:$property", $value, $comparison);
    }

    public function addVisitEntryPage($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_ENTRY_PAGE, $value, $comparison);
    }

    public function addVisitExitPage($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_EXIT_PAGE, $value, $comparison);
    }

    public function addVisitSource($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_SOURCE, $value, $comparison);
    }

    public function addVisitReferrer($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_REFERRER, $value, $comparison);
    }

    public function addVisitUtmMedium($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_UTM_MEDIUM, $value, $comparison);
    }

    public function addVisitUtmSource($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_UTM_SOURCE, $value, $comparison);
    }

    public function addVisitUtmCampaign($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_UTM_CAMPAIGN, $value, $comparison);
    }

    public function addVisitUtmContent($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_UTM_CONTENT, $value, $comparison);
    }

    public function addVisitUtmTerm($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_UTM_TERM, $value, $comparison);
    }

    public function addVisitDevice($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_DEVICE, $value, $comparison);
    }

    public function addVisitBrowser($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_BROWSER, $value, $comparison);
    }

    public function addVisitBrowserVersion($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_BROWSER_VERSION, $value, $comparison);
    }

    public function addVisitOs($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_OS, $value, $comparison);
    }

    public function addVisitOsVersion($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_OS_VERSION, $value, $comparison);
    }

    public function addVisitCountry($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_COUNTRY, $value, $comparison);
    }

    public function addVisitRegion($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_REGION, $value, $comparison);
    }

    public function addVisitCity($value, string $comparison = self::EQUAL): self
    {
        return $this->add(Property::VISIT_CITY, $value, $comparison);
    }

    public function toString(): string
    {
        return implode(';', $this->filters);
    }

    public function __toString()
    {
        return $this->toString();
    }
}