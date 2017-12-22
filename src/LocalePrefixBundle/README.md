# Ruwork Routing Locale Prefix Bundle

## Description

This bundle hacks the Symfony router and prefixes all routes with an optional `/{_locale}` section.

## Configuration

```yaml
ruwork_locale_prefix:
    locales: [ru, en]
    default_locale: ru
```

```yaml
# config/routes.yaml
controllers:
    resource: ../src/Controller/
    type: annotation
    options:
        locale_prefixed: true
```
