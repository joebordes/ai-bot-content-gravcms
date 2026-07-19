<?php
/**
 * @package    Grav\Plugin
 *
 * @copyright  Copyright (c) 2026 Joe Bordes. All rights reserved.
 * @license    MIT License; see LICENSE file for details.
 */

declare(strict_types=1);

namespace Grav\Plugin;

use Grav\Common\Plugin;

class AIBotResponderPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 1000],
        ];
    }

    /**
     * Check if user agent matches configured AI bots and return JSON payload.
     *
     * @return void
     */
    public function onPluginsInitialized(): void
    {
        // Don't intercept requests in the Admin Panel
        if ($this->isAdmin()) {
            return;
        }

        // Check if plugin is enabled
        if (!$this->config->get('plugins.ai-bot-responder.enabled')) {
            return;
        }

        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
        $botsConfig = $this->config->get('plugins.ai-bot-responder.bots', '');

        // Parse bots config list
        $aiBots = array_filter(array_map('trim', explode(',', strtolower((string)$botsConfig))));

        if (empty($aiBots)) {
            return;
        }

        $isBot = false;
        foreach ($aiBots as $bot) {
            if ($bot !== '' && strpos($userAgent, $bot) !== false) {
                $isBot = true;
                break;
            }
        }

        if ($isBot) {
            $content = (string)$this->config->get('plugins.ai-bot-responder.content', '{}');

            // Sanity check/validation of configured JSON
            json_decode($content);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Return server configuration error instead of malformed JSON
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'error' => 'Invalid JSON configured in AI Bot Responder plugin.'
                ]);
                exit;
            }
            
            // Serve JSON response and terminate early
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            echo $content;
            exit;
        }
    }
}
