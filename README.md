# Laravel + Passport Skeleton

## Working from this skeleton

You should fork this repository before working with it.


Then:

1. Copy .env.example to .env
2. Run ```composer install```
3. Run ```vendor/bin/homestead make```
4. Run ```vagrant up```


Vagrant will provision Homestead virtual machine and setup application skeleton.

## What comes bundled with this repository

* Oauth2 server provided by laravel/passport.
* Basic API calls for managing session
* Basic User model

## Session API

### POST /api/token

__Request:__

__Headers:__

* Accept : application/json

__Body:__
```
{
    "email" : " ... USER EMAIL ... ",
    "password" : " ... USER PASSWORD ... "
}
```

__Response:__

__Status Code:__ 200 (OK) or 401 (Unauthorized)

__Body:__

*200 OK*

```
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": " ... ACCESS_TOKEN_STRING ... ",
    "refresh_token": " ... REFRESH_TOKEN_STRING ... "
}
```

*401 Unauthorized*

```
{
    "error" : "invalid_credentials",
    "error_description" : "The user credentials were incorrect",
    "message" : "The user credentials were incorrect"
}
```

### DELETE /api/token

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 204 (NO CONTENT) or 401 (Unauthorized)

__Body:__

*401 Unauthorized*

```
{
    "error" : "unauthorized"
}
```

### POST /api/token/refresh

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json

__Body:__

```
{
    "refresh_token" : " ... REFRESH_TOKEN_STRING ... "
}
```

__Response:__

__Status Code:__ 200 (OK) or 422 (Unprocessable Entity)

__Body:__

*200 OK*

```
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": " ... ACCESS_TOKEN_STRING ... ",
    "refresh_token": " ... REFRESH_TOKEN_STRING ... "
}
```

### GET /api/user

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK) or 401 (Unauthorized)

__Body:__

*200 OK*

```
{
    "id": Integer,
    "name": String,
    "email": String,
    "email_verified_at": Date,
    "created_at": Date,
    "updated_at": Date
}
```
