<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Events\Component;

use CachetHQ\Cachet\Bus\Events\ActionInterface;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\User;

/**
 * This is the component was removed event class.
 *
 * @author James Brooks <james@alt-three.com>
 */
final class ComponentWasRemovedEvent implements ActionInterface, ComponentEventInterface
{
    /**
     * The user who removed the component.
     *
     * @var \CachetHQ\Cachet\Models\User
     */
    public $user;

    /**
     * The component that was removed.
     *
     * @var \CachetHQ\Cachet\Models\Component
     */
    public $component;

    /**
     * Create a new component was removed event instance.
     *
     * @param \CachetHQ\Cachet\Models\User      $user
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return void
     */
    public function __construct(User $user, Component $component)
    {
        $this->user = $user;
        $this->component = $component;
    }

    /**
     * Get the event description.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Component was removed.';
    }

    /**
     * Get the event action.
     *
     * @return array
     */
    public function getAction()
    {
        return [
            'user' => $this->user,
            'description' => (string) $this,
        ];
    }
}
