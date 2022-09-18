## Installation

Please note this is only tested with PHP 8.1 and follow the mentioned steps to set up the project

1. Run `composer install` to install php packages
2. Please update the `.env` file with correct values
3. Run `php artisan migrate`

### API endpoints
Here are the API endpoints for the calendar app.

### POST /api/register
Register an user

**Parameters**

|       Name | Required |  Type   |
|-----------:|:--------:|:-------:|
|     `name` | required | string  |
|    `email` | required | string  |
| `password` | required | string  |

### POST /api/login
Authorise an user

**Parameters**

|       Name | Required |  Type   |
|-----------:|:--------:|:-------:|
|    `email` | required | string  |
| `password` | required | string  |

### POST /api/account-recovery
Reset the password for an user

**Parameters**

|                    Name | Required |  Type   |
|------------------------:|:--------:|:-------:|
|                 `email` | required | string  |
|                 `token` | required | string  |
|              `password` | required | string  |
| `password_confirmation` | required | string  |

### GET /api/events
List of events with pagination. This API needs authorization. Please pass the token inside the `Authorization` header.

### GET /api/events/{id}
Show single event. This API needs authorization. Please pass the token inside the `Authorization` header.

### POST /api/events
Create an event. This API needs authorization. Please pass the token inside the `Authorization` header.

**Parameters**

|                    Name | Required |                  Type                   |
|------------------------:|:--------:|:---------------------------------------:|
|                 `title` | required |                 string                  |
|                 `start` | required |         string(ISO 8601 format)         |
|                   `end` | required |         string(ISO 8601 format)         |

### PUT /api/events/id
Update an event. This API needs authorization. Please pass the token inside the `Authorization` header.

**Parameters**

|                    Name | Required |                  Type                   |
|------------------------:|:--------:|:---------------------------------------:|
|                 `title` | required |                 string                  |
|                 `start` | required |         string(ISO 8601 format)         |
|                   `end` | required |         string(ISO 8601 format)         |

### DELETE /api/events/{id}
Delete single event. This API needs authorization. Please pass the token inside the `Authorization` header


## Testing

```bash
composer test
```
