<?php
/**
 * @package Newscoop\PaywallBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\PaywallBundle\Entity;

use Newscoop\Entity\Publication;
use Newscoop\Entity\User;
use Newscoop\Entity\Section;
use Newscoop\Entity\Issue;
use Newscoop\Entity\Article;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Subscription entity
 * @ORM\Entity(repositoryClass="Newscoop\PaywallBundle\Entity\Repository\UserSubscriptionRepository")
 * @ORM\Table(name="plugin_paywall_user_subscriptions")
 */
class UserSubscription
{
    const TYPE_PAID = 'P';
    const TYPE_PAID_NOW = 'PN';
    const TYPE_TRIAL = 'T';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="Id")
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Newscoop\Entity\User")
     * @ORM\JoinColumn(name="IdUser", referencedColumnName="Id")
     * @var Newscoop\Entity\User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Newscoop\PaywallBundle\Entity\Subscriptions")
     * @ORM\JoinColumn(name="IdSubscription", referencedColumnName="id")
     * @var Newscoop\PaywallBundle\Entity\Subscriptions
     */
    protected $subscription;

    /**
     * @ORM\ManyToOne(targetEntity="Newscoop\Entity\Publication")
     * @ORM\JoinColumn(name="IdPublication", referencedColumnName="Id")
     * @var Newscoop\Entity\Publication
     */
    protected $publication;

    /**
     * @ORM\Column(type="decimal", name="ToPay")
     * @var float
     */
    protected $toPay = 0.0;

    /**
     * @ORM\Column(name="Type")
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(name="Currency")
     * @var string
     */
    protected $currency;

    /**
     * @ORM\OneToMany(targetEntity="Newscoop\Subscription\Section", mappedBy="subscription", cascade={"persist", "remove"})
     * @var array
     */
    protected $sections;

    /**
     * @ORM\OneToMany(targetEntity="Newscoop\Subscription\Article", mappedBy="subscription", cascade={"persist", "remove"})
     * @var array
     */
    protected $articles;

    /**
     * @ORM\OneToMany(targetEntity="Newscoop\Subscription\Issue", mappedBy="subscription", cascade={"persist", "remove"})
     * @var array
     */
    public $issues;

    /**
     * @ORM\ManyToOne(targetEntity="Newscoop\PaywallBundle\Entity\Trial")
     * @ORM\JoinColumn(name="trial_id", referencedColumnName="id")
     * @var Newscoop\PaywallBundle\Entity\Trial
     */
    protected $trial;

    /**
     * Subscription status visible for admin
     * @ORM\Column(name="Active")
     * @var string
     */
    protected $active;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var DateTime
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", name="expire_at", nullable=true)
     * @var DateTime
     */
    protected $expire_at;

    /**
     * Custom field
     * @ORM\Column(type="boolean", name="custom")
     * @var boolean
     */
    protected $custom;

    /**
     * Second custom field
     * @ORM\Column(type="boolean", name="custom_2")
     * @var boolean
     */
    protected $customOther;

    /**
     * To hide from users totally
     * @ORM\Column(type="boolean", name="is_active")
     * @var boolean
     */
    protected $is_active;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     * @var DateTime
     */
    protected $updated;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->currency = '';
        $this->active = 'N';
        $this->created_at = new \DateTime();
        $this->is_active = false;
        $this->custom = false;
        $this->customOther = false;
        $this->type = self::TYPE_PAID;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * Set subscription
     *
     * @param  Newscoop\PaywallBundle\Entity\Subscriptions $subscription
     * @return void
     */
    public function setSubscription(Subscriptions $subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return Newscoop\PaywallBundle\Entity\Subscription_specification
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set user
     *
     * @param  Newscoop\Entity\User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Newscoop\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set publication
     *
     * @param  Newscoop\Entity\Publication  $publication
     * @return Newscoop\Entity\Subscription
     */
    public function setPublication(Publication $publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return Newscoop\Entity\Publication
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Get publication name
     *
     * @return string
     */
    public function getPublicationName()
    {
        return $this->publication->getName();
    }

    /**
     * Get publication id
     *
     * @return int
     */
    public function getPublicationId()
    {
        return $this->publication->getId();
    }

    /**
     * Set to pay
     *
     * @param  float                        $toPay
     * @return Newscoop\Entity\Subscription
     */
    public function setToPay($toPay)
    {
        $this->toPay = (float) $toPay;

        return $this;
    }

    /**
     * Get to pay
     *
     * @return float
     */
    public function getToPay()
    {
        return (float) $this->toPay;
    }

    /**
     * Set type
     *
     * @param  string                       $type
     * @return Newscoop\Entity\Subscription
     */
    public function setType($type)
    {
        $this->type = strtoupper($type) === self::TYPE_TRIAL ? self::TYPE_TRIAL : self::TYPE_PAID;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Test if is trial
     *
     * @return bool
     */
    public function isTrial()
    {
        return $this->type === self::TYPE_TRIAL;
    }

    /**
     * Set active
     *
     * @param  bool                         $active
     * @return Newscoop\Entity\Subscription
     */
    public function setActive($active)
    {

        $this->active = ((bool) $active) ? 'Y' : 'N';

        return $this;
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return strtoupper($this->active) === 'Y';
    }

    /**
     * Add sections
     *
     * @param  array                       $values
     * @param  Newscoop\Entity\Publication $publication
     * @return void
     */
    public function addSections(array $values, Publication $publication)
    {
        $languages = array();
        if (!empty($values['individual_languages']) && $values['individual_languages']) {
            $languages = $values['languages'];
            if (empty($languages)) {
                throw new \InvalidArgumentException("No languages set for individual languages");
            }
        }

        foreach ($publication->getIssues() as $issue) {
            if (!empty($languages) && !in_array($issue->getLanguageId(), $languages)) {
                continue;
            }

            foreach ($issue->getSections() as $section) {
                if ($this->hasSection($section, $languages)) {
                    continue;
                }

                $subSection = new Section($this, $section->getNumber());
                $subSection->setStartDate(new \DateTime($values['start_date']));
                $subSection->setDays($values['days']);

                if ($this->isTrial() || $values['type'] === self::TYPE_PAID_NOW) {
                    $subSection->setPaidDays($values['days']);
                }

                if (!empty($languages)) {
                    $subSection->setLanguage($issue->getLanguage());
                }
            }
        }
    }

    /**
     * Set sections
     *
     * @param  array $values
     * @return void
     */
    public function setSections(array $values)
    {
        $ids = array_map(function ($section) {
            return !empty($section['id']) ? $section['id'] : null;
        }, $values);

        foreach ($this->sections as $key => $section) {
            if (!in_array($section->getId(), $ids)) {
                $this->sections->remove($key);
            }
        }
    }

    /**
     * Add section
     *
     * @param  Newscoop\Subscription\Section $section
     * @return void
     */
    public function addSection(Section $section)
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
        }
    }

    /**
     * Set articles
     *
     * @param  array $values
     * @return void
     */
    public function setArticles(array $values)
    {
        $ids = array_map(function ($article) {
            return !empty($article['id']) ? $article['id'] : null;
        }, $values);

        foreach ($this->articles as $key => $article) {
            if (!in_array($article->getId(), $ids)) {
                $this->articles->remove($key);
            }
        }
    }

    /**
     * Add article
     *
     * @param  Newscoop\Subscription\Article $article
     * @return void
     */
    public function addArticle(Article $article)
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }
    }

    /**
     * Set issues
     *
     * @param  array $values
     * @return void
     */
    public function setIssues(array $values)
    {
        $ids = array_map(function ($issue) {
            return !empty($issue['id']) ? $issue['id'] : null;
        }, $values);

        foreach ($this->issues as $key => $issue) {
            if (!in_array($issue->getId(), $ids)) {
                $this->issues->remove($key);
            }
        }
    }

    /**
     * Add issue
     *
     * @param  Newscoop\Subscription\Issue $issue
     * @return void
     */
    public function addIssue(Issue $issue)
    {
        if (!$this->issues->contains($issue)) {
            $this->issues->add($issue);
        }
    }

    /**
     * Test if has given section
     *
     * @param  Newscoop\Subscription\Section $section
     * @param  array                         $languages
     * @return bool
     */
    protected function hasSection(Section $section, array $languages)
    {
        foreach ($this->sections as $s) {
            if ($s->getSectionNumber() == $section->getNumber()) {
                if (!$s->hasLanguage()) {
                    return true;
                } elseif (empty($languages)) {
                    $s->setLanguage(null);

                    return true;
                } elseif ($s->getLanguage() == $section->getLanguage()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Get articles
     *
     * @return array
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Get issues
     *
     * @return array
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Get currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency
     * @return string
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get trial
     * @return Trial
     */
    public function getTrial()
    {
        return $this->trial;
    }

    /**
     * Set trial
     * @return Trial
     */
    public function setTrial($trial)
    {
        $this->trial = $trial;

        return $this;
    }

    /**
     * Get create date
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set create date
     *
     * @param  datetime $created_at
     * @return datetime
     */
    public function setCreatedAt(\DateTime $created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get expire date
     *
     * @return datetime
     */
    public function getExpireAt()
    {
        return $this->expire_at;
    }

    /**
     * Set expire date
     *
     * @param  datetime $expire_at
     * @return datetime
     */
    public function setExpireAt(\DateTime $expire_at = null)
    {
        $this->expire_at = $expire_at;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set status
     *
     * @param  boolean $is_active
     * @return boolean
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * Gets the Custom field.
     *
     * @return boolean
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * Sets the Custom field.
     *
     * @param boolean $custom the custom
     *
     * @return self
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * Gets the Second custom field.
     *
     * @return boolean
     */
    public function getCustomOther()
    {
        return $this->customOther;
    }

    /**
     * Sets the Second custom field.
     *
     * @param boolean $customOther the custom other
     *
     * @return self
     */
    public function setCustomOther($customOther)
    {
        $this->customOther = $customOther;

        return $this;
    }

    /**
     * Gets the value of updated.
     *
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Sets the value of updated.
     *
     * @param DateTime $updated the updated
     *
     * @return self
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
