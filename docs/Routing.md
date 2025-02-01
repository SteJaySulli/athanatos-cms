# Routing

Athanatos CMS uses a simple routing system that allows for easy, internationalised routing through to any article.

## Routing Configuration

The configuration file provides a few different options for routing:

```php
[
    'base_uri' => '/',
    'admin_uri' => '/admin',
    'middleware' => [AthanatosL10nMiddleware::class,'web'],
    'use_languageless_urls' => true,
    'redirect_to_language' => true,

]
```

The `base_uri` directive sets the prefix to the routes the CMS will use; if, for example, you set this to `/docs`, the CMS will only respond to requests that start with `/docs`. This will typically be immediately followed by either a language code, the `admin_uri` or (where configured) an article uri (see the `redirect_to_language` directive below).

The `admin_uri` is appended to the `base_uri` and the CMS admin panel's routes will be prefixed with this. This will typically be immediately followed by a language code.

The `middleware` directive is an array of middleware that will be applied to all routes. The first middleware in the array will be the first to be applied. The `AthanatosL10nMiddleware` middleware is required for the CMS to function correctly; this sets up the language for the request and ensures that the correct language is used for the response.

The `use_languageless_urls` directive, when set to `true`, will allow the CMS to respond to requests that do not include a language code. This is useful for SEO purposes, as it allows for a single URL to be used for an article in multiple languages. When this is set to `true`, the CMS will either redirect to a language-specific URL, or display the language-specific article without redirecting to a canonical URL.

The `redirect_to_language` directive, when set to `true`, will redirect requests that do not include a language code to a language-specific URL. Disabling this feature means the URL will not be redirected, but the resulting article will still be the same.

## Routing to Articles

In order to make the routing of articles as simple as possible, the `AthanatosCms` facade provides a simple method you can use to set up all of the appropriate routes. This should be added to your routes file, typically `web.php`:

```php
AthanatosCms::routes();
```

This will set up all of the routes required for the CMS to function correctly.
