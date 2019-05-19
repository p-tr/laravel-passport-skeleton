# Laravel + Passport Skeleton

## Working from this skeleton

You should fork this repository before working with it.

You'll need [Vagrant](https://vagrantup.com)


Once forked and cloned on your local development machine, run ```composer install```

Whenever you start working on your project : ```vagrant up```

When done : ```vagrant halt```


## What comes bundled with this repository

* Oauth2 server provided by laravel/passport.
* Simple Session API built on top of Oauth2 server
* Basic User model
* Custom composer scripts

## Composer scripts

All scripts must run inside Homestead or production environment:

* ```composer run-script test```  : run unit & feature tests
* ```composer run-script setup``` : setup application, install passport
* ```composer run-script reset``` : reset application, migrate fresh database, run tests

## Automatic Homestead.yaml generation

Outside Homestead, each time you run ```composer install```, Homestead.yaml is
generated automagically and "post-root-package-install" script is run.

## Session API

### POST /api/token

Login user against Oauth2 server.

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

__Status Code:__ 200 (OK) | 401 (Unauthorized) | 422 (Unprocessable Entity)

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

Delete authorization token provided in "Authorization" header, effectively logging out current user.

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

Refresh a given authorization token, using Oauth2 server.

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

Return back to caller a JSON representation of current user, given an authorization token.

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
