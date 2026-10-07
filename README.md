# LowSeek Chinese Search

Replaces the default discussion full-text filter with database `LIKE` substring matching for Chinese titles and post content. It uses Flarum's search extension API and does not modify Flarum core files.

Enable Flarum's `search_cjk_mode` setting in the admin panel so one- and two-character Chinese queries are sent to the server.
