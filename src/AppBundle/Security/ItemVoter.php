<?php

namespace AppBundle\Security;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use AppBundle\Entity\User;
use AppBundle\Utils\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Item objects inside this voter
        if (!$subject instanceof ItemInterface) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) $user = null;

        // admin can do anything
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
        }

        throw new \LogicException('Error occurred.');
    }

    private function canView(ItemInterface $item, User $user = null)
    {
        // everyone can view if it's public
        if ($item->isPublic()){
            return true;
        }

        // if they can edit, they can view
        return $this->canEdit($item, $user);
    }

    private function canEdit(ItemInterface $item, User $user = null)
    {
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $item->getAddedBy() && !$item->isPublic();
    }
}