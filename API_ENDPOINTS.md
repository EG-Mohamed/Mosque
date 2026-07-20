# API Endpoints

All endpoints are public read-only `GET` endpoints under `/api` and return JSON API resource responses. Paginated endpoints return Laravel pagination keys: `data`, `links`, and `meta`.

Common query parameters:

| Parameter | Type | Description |
| --- | --- | --- |
| `locale` | string | Locale code for translated fields. |
| `lang` | string | Alternative to `locale`. |
| `per_page` | integer | Items per page. Minimum `1`, maximum `100`. |
| `page` | integer | Page number. |

## Prayer Times

### `GET /api/prayer-times`

Returns paginated prayer times ordered by date.

| Parameter | Type | Description |
| --- | --- | --- |
| `date` | date `YYYY-MM-DD` | Return one specific date. |
| `from` | date `YYYY-MM-DD` | Start date for date range. |
| `to` | date `YYYY-MM-DD` | End date for date range. Must be after or equal to `from`. |
| `year` | integer | Filter by year. |
| `month` | integer | Filter by month from `1` to `12`. |

Example: `/api/prayer-times?from=2026-07-01&to=2026-07-31&per_page=31`

### `GET /api/prayer-times/today`

Returns prayer times for today, or the provided date.

| Parameter | Type | Description |
| --- | --- | --- |
| `date` | date `YYYY-MM-DD` | Optional date override. |

Example: `/api/prayer-times/today?date=2026-07-20`

## News

### `GET /api/news`

Returns published news ordered by publish date.

| Parameter | Type | Description |
| --- | --- | --- |
| `search` | string | Search title, excerpt, and content in the selected locale. |
| `category_id` | integer | Filter by news category ID. |
| `published_from` | date `YYYY-MM-DD` | Start publish date. |
| `published_to` | date `YYYY-MM-DD` | End publish date. Must be after or equal to `published_from`. |

Example: `/api/news?locale=en&category_id=1&search=community`

### `GET /api/news/{slug}`

Returns one published news item by slug.

Example: `/api/news/friday-program`

### `GET /api/news-categories`

Returns active news categories.

Example: `/api/news-categories?locale=en`

## Khutbas

### `GET /api/khutbas`

Returns published khutbas ordered by date descending.

| Parameter | Type | Description |
| --- | --- | --- |
| `search` | string | Search title, speaker, topic, and summary in the selected locale. |
| `category_id` | integer | Filter by khutba category ID. |
| `date` | date `YYYY-MM-DD` | Return khutbas for one date. |
| `from` | date `YYYY-MM-DD` | Start date for date range. |
| `to` | date `YYYY-MM-DD` | End date for date range. Must be after or equal to `from`. |

Example: `/api/khutbas?from=2026-07-01&to=2026-07-31&locale=en`

### `GET /api/khutbas/{slug}`

Returns one published khutba by slug.

Example: `/api/khutbas/patience-and-prayer`

### `GET /api/khutba-categories`

Returns active khutba categories.

Example: `/api/khutba-categories?locale=en`

## Announcements

### `GET /api/announcements`

Returns active announcements.

| Parameter | Type | Description |
| --- | --- | --- |
| `type` | string | Filter by `general`, `urgent`, or `maintenance`. |

Example: `/api/announcements?type=urgent&locale=en`

## Events

### `GET /api/events`

Returns published events ordered by start date.

| Parameter | Type | Description |
| --- | --- | --- |
| `from` | date `YYYY-MM-DD` | Start date for event start time. |
| `to` | date `YYYY-MM-DD` | End date for event start time. Must be after or equal to `from`. |
| `featured` | boolean | Filter featured events. Accepts `true`, `false`, `1`, or `0`. |

Example: `/api/events?from=2026-07-01&featured=1&locale=en`

### `GET /api/events/{event}`

Returns one published event by ID.

Example: `/api/events/1`

## Special Prayers

### `GET /api/special-prayers`

Returns special prayers ordered by date and time.

| Parameter | Type | Description |
| --- | --- | --- |
| `date` | date `YYYY-MM-DD` | Return special prayers for one date. |
| `from` | date `YYYY-MM-DD` | Start date for date range. |
| `to` | date `YYYY-MM-DD` | End date for date range. Must be after or equal to `from`. |
| `type` | string | Filter by `ramadan`, `eid`, `weekly`, or `other`. |

Example: `/api/special-prayers?type=eid&locale=en`

## Gallery

### `GET /api/gallery`

Returns media items. Defaults to image items when `type` is not provided.

| Parameter | Type | Description |
| --- | --- | --- |
| `collection` | string | Filter by collection name. |
| `type` | string | Filter by `image` or `video`. |

Example: `/api/gallery?collection=homepage&type=image&locale=en`

## Staff

### `GET /api/staff`

Returns active staff members ordered by sort order.

Example: `/api/staff?locale=en`
