<?php

namespace Devarts\PlausiblePHP\Support;

class Property
{
    public const EVENT_GOAL = 'event:goal';
    public const EVENT_HOSTNAME = 'event:hostname';
    public const EVENT_PAGE = 'event:page';
    public const VISIT_ENTRY_PAGE = 'visit:entry_page';
    public const VISIT_EXIT_PAGE = 'visit:exit_page';
    public const VISIT_SOURCE = 'visit:source';
    public const VISIT_REFERRER = 'visit:referrer';
    public const VISIT_UTM_MEDIUM = 'visit:utm_medium';
    public const VISIT_UTM_SOURCE = 'visit:utm_source';
    public const VISIT_UTM_CAMPAIGN = 'visit:utm_campaign';
    public const VISIT_UTM_CONTENT = 'visit:utm_content';
    public const VISIT_UTM_TERM = 'visit:utm_term';
    public const VISIT_DEVICE = 'visit:device';
    public const VISIT_BROWSER = 'visit:browser';
    public const VISIT_BROWSER_VERSION = 'visit:browser_version';
    public const VISIT_OS = 'visit:os';
    public const VISIT_OS_VERSION = 'visit:os_version';
    public const VISIT_COUNTRY = 'visit:country';
    public const VISIT_REGION = 'visit:region';
    public const VISIT_CITY = 'visit:city';
}