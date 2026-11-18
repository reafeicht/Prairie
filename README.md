<p align="center"><img width="200" src="https://github.com/reafeicht/prairie/raw/main/art/prairie.png"></p>

<p align="center">
    <a href="https://packagist.org/packages/reafeicht/prairie">
        <img src="https://img.shields.io/packagist/dt/reafeicht/prairie?cacheSeconds=60">
    </a>
    <a href="https://packagist.org/packages/reafeicht/prairie">
        <img src="https://img.shields.io/packagist/v/reafeicht/prairie?label=stable&color=blue&cacheSeconds=60">
    </a>
    <a href="https://packagist.org/packages/reafeicht/prairie">
        <img src="https://poser.pugx.org/reafeicht/prairie/license.svg">
    </a>
</p>

## Introduction

The prairieUI package is a set of Gorgeous UI components for Livewire powered by daisyUI and Tailwind.

## Official Documentation

You can read the official documentation on the [prairieUI website](https://github.com/reafeicht/prairie).

## Sponsor

Let's keep pushing it, [sponsor me](https://github.com/sponsors/reafeicht) ❤️

## Discord

Come to say hello on [prairieUI Discord](https://discord.gg/c2Dv8T2X2s)

## Follow me

[@reafeicht](https://twitter.com/reafeicht)

## Contributing

Clone the repository into your project root.

```bash
git clone git@github.com:reafeicht/prairie.git packages/prairie
```

Add the local repository to composer config.

```bash
composer config repositories.local '{"type": "path", "url": "packages/prairie"}'
```

Require the package again for local symlink.

```bash
composer require reafeicht/prairie:@dev
```

Start the dev server.

```bash
yarn dev
```

You can roll back to the stable version by removing the local repository and requiring the package again.

```bash
composer config --unset repositories.local
composer require reafeicht/prairie
```

## License

<a name="license"></a>

PrairieUI is open-sourced software licensed under the [MIT license](/license.md).
