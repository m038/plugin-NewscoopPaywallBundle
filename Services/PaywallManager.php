<?php
/**
 * @package Newscoop\PaywallBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\PaywallBundle\Services;

use Doctrine\ORM\EntityManager;
use Newscoop\Services\SubscriptionService;

/**
 * PaywallManager class manages paywall adapters
 */
class PaywallManager
{   
    /** @var Doctrine\ORM\EntityManager */
    private $em;

     /** @var Newscoop\Services\SubscriptionService */
    private $subscriptionService;
 
    /**
     * Apply entity manager and injected services
     *
     * @param EntityManager       $em
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(EntityManager $em, SubscriptionService $subscriptionService)
    {
        $this->em = $em;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Gets adapter, if adapter doesn't exist, use default one
     *
     * @param EntityManager       $em
     * @param SubscriptionService $subscriptionService
     */
    public function getAdapter() {

        $settings = $this->em->getRepository('Newscoop\PaywallBundle\Entity\Settings')
            ->findOneByIsActive(true);

        $adapter = '\Newscoop\PaywallBundle\Adapter\\'.$settings->getValue();

        if (!@include($adapter)) {
            return new \Newscoop\PaywallBundle\Adapter\PaypalAdapter($this->subscriptionService);
        }

        return new $adapter($this->subscriptionService);
    }
}