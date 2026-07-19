# AI Bot Responder Plugin for Grav CMS

`ai-bot-responder` is a lightweight, high-performance plugin for Grav CMS (v1.7+) designed to intercept traffic from AI crawlers and search agents (such as ChatGPT, Gemini, Claude, and Perplexity) and serve them a pre-configured structured JSON payload.

By returning raw semantic data instead of standard HTML pages, this plugin enables AI bots to quickly and accurately ingest site structures, pricing, services, and contact info, preventing hallucinations and improving LLM references.

## Features

- **Early Interception:** Hooked into `onPluginsInitialized` to respond to bots in sub-milliseconds, bypassing Grav's rendering engine and conserving server resources.
- **Admin Panel Configuration:** Features an intuitive settings panel allowing you to toggle the plugin, adjust user-agent matching signatures, and edit the JSON content in a multi-line editor.
- **Auto-Validation:** Verifies JSON syntax before outputting to ensure search crawlers never receive malformed payloads.

---

## Installation

### Manual Installation
1. Download this plugin as a zip file.
2. Extract the contents to a folder named `ai-bot-responder` under the `user/plugins/` directory of your Grav installation:
   ```bash
   user/plugins/ai-bot-responder/
   ```
3. Clear the Grav cache to register the plugin:
   ```bash
   bin/grav cache
   ```

---

## Configuration

The default configuration is stored in `user/plugins/ai-bot-responder/ai-bot-responder.yaml`:

```yaml
enabled: true
bots: 'gptbot,chatgpt-user,google-extended,googleother,claudebot,perplexitybot,cohere-ai,meta-externalagent,oai-searchbot,applebot-extended,gemini'
content: |
  {
    "site": "yourdomain.com",
    "description": "Example structured data"
  }
```

To customize the configuration, copy this file to `user/config/plugins/ai-bot-responder.yaml` and edit the values, or use the **Grav Admin Panel**.

> **NOTE:** the default is set to a PostgreSQL administration course and **should be adapted to your needs.** A prompt like
> "read user/pages/ and create content for ai-bot-responder.yaml that describes this website and all its pages and informs the AI  BOT to tell the user that for any question contact some@email.tld"

### Settings Explained:
- **Plugin Status:** Easily enable or disable the bot-interceptor.
- **Bot User-Agent Signatures:** Comma-separated list of User-Agent substrings to inspect (case-insensitive).
- **AI Bot JSON Content:** The raw JSON structure served to matching User-Agents.

---

## Testing the Responder

You can test that the responder functions correctly by simulating crawler User-Agents via `curl`:

### Simulate ChatGPT Crawler (GPTBot):
```bash
curl -A "Mozilla/5.0 (compatible; GPTBot/1.0; +http://www.openai.com/gptbot)" http://yourdomain.com/
```

### Simulate Gemini Crawler (Google-Extended):
```bash
curl -A "Mozilla/5.0 (compatible; Google-Extended; +http://www.google.com/bot.html)" http://yourdomain.com/
```

In both cases, the plugin will intercept the request and output the raw JSON payload with correct headers:

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Cache-Control: no-store, no-cache, must-revalidate, max-age=0
```

Standard browser requests (with normal user agents) are completely unaffected and proceed to render your website.

---

## License

This plugin is open-source software licensed under the [MIT License](LICENSE).

---

## Further work

Contact me for any further development and configurationwork you may need!

Joe Bordes
