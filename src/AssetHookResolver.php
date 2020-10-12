<?php

declare(strict_types=1);

/*
 * This file is part of the Assets package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Assets;

use Inpsyde\WpContext;

class AssetHookResolver
{

    /**
     * @var WpContext|null
     */
    private $context;

    /**
     * @param WpContext|null $context
     */
    public function __construct(?WpContext $context = null)
    {
        $this->context = $context ?? WpContext::determine();
    }

    /**
     * Resolving to the current location/page in WordPress all current hooks.
     *
     * @return array
     */
    public function resolve(): array
    {
        $isLogin = $this->context->isLogin();
        $isFront = $this->context->isFrontoffice();

        if (!$isLogin && !$isFront && !$this->context->isBackoffice()) {
            return [];
        }

        if ($isLogin) {
            return [Asset::HOOK_LOGIN];
        }

        // These hooks might be fired in both front and back office.
        $assets = [Asset::HOOK_BLOCK_ASSETS];

        if ($isFront) {
            $assets[] = Asset::HOOK_FRONTEND;

            return $assets;
        }

        $assets[] = Asset::HOOK_BLOCK_EDITOR_ASSETS;
        $assets[] = Asset::HOOK_CUSTOMIZER;
        $assets[] = Asset::HOOK_BACKEND;

        return $assets;
    }

    /**
     * @return string|null
     */
    public function lastHook(): ?string
    {
        switch (true) {
            case $this->context->isLogin():
                return Asset::HOOK_LOGIN;
            case $this->context->isFrontoffice():
                return Asset::HOOK_FRONTEND;
            case $this->context->isBackoffice():
                return Asset::HOOK_BACKEND;
        }

        return null;
    }
}
