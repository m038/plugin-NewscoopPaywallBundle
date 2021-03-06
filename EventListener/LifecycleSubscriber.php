<?php
/**
 * @package Newscoop\PaywallBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\PaywallBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newscoop\EventDispatcher\Events\GenericEvent;
use Newscoop\PaywallBundle\Entity\Settings;
use Newscoop\PaywallBundle\Events\AdaptersEvent;

/**
 * Event lifecycle management
 */
class LifecycleSubscriber implements EventSubscriberInterface
{
    private $em;

    private $dispatcher;

    public function __construct($em, $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function install(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        $adapter = new Settings();
        $adapter->setName('Paypal');
        $adapter->setValue('PaypalAdapter');
        $this->em->persist($adapter);
        $this->em->flush();

        $this->dispatcher->dispatch('newscoop_paywall.adapters.register', new AdaptersEvent($this, array()));

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function update(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        $this->dispatcher->dispatch('newscoop_paywall.adapters.register', new AdaptersEvent($this, array()));

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function remove(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->dropSchema($this->getClasses(), true);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'plugin.install.newscoop_newscoop_paywall_bundle' => array('install', 1),
            'plugin.update.newscoop_newscoop_paywall_bundle' => array('update', 1),
            'plugin.remove.newscoop_newscoop_paywall_bundle' => array('remove', 1),
        );
    }

    private function getClasses()
    {
        return array(
          $this->em->getClassMetadata('Newscoop\PaywallBundle\Entity\Subscriptions'),
          $this->em->getClassMetadata('Newscoop\PaywallBundle\Entity\SubscriptionSpecification'),
          $this->em->getClassMetadata('Newscoop\PaywallBundle\Entity\Settings'),
          $this->em->getClassMetadata('Newscoop\PaywallBundle\Entity\UserSubscription'),
          $this->em->getClassMetadata('Newscoop\PaywallBundle\Entity\Trial'),
        );
    }
}
