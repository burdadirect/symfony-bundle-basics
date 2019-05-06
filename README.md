# HBM Basics

## Team

### Developers
Christian Puchinger - christian.puchinger@burda.com

## Installation

### Step1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require burdanews/symfony-bundle-basics
```

### Step 2: Enable the Bundle

With Symfony 4 the bundle is enabled automatically for all environments (see `config/bundles.php`). 


### Step 3: Configuration

```yml
hbm_basics
  confirm:
    template: partials/confirm.html.twig
    navi: default
```
