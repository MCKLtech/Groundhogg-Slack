<?php

declare(strict_types=1);

/*
 * This file is part of JoliCode's Slack PHP API project.
 *
 * (c) JoliCode <coucou@jolicode.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JoliCode\Slack\Api\Model;

class UsersSetPhotoPostResponse200
{
    /**
     * @var bool|null
     */
    protected $ok;
    /**
     * @var UsersSetPhotoPostResponse200Profile|null
     */
    protected $profile;

    public function getOk(): ?bool
    {
        return $this->ok;
    }

    public function setOk(?bool $ok): self
    {
        $this->ok = $ok;

        return $this;
    }

    public function getProfile(): ?UsersSetPhotoPostResponse200Profile
    {
        return $this->profile;
    }

    public function setProfile(?UsersSetPhotoPostResponse200Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }
}
