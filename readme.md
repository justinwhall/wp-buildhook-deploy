**👋 This plugin [has been forked](https://github.com/staticfuse/gatsby-toolkit) and is now maintained by [Static Fuse](https://staticfuse.com).**

======================================================================================

Look for improvments and more great WordPress + Gatsby workflow support. Questions? Submit an issue at the new repo or find me on twitter --> [@justinwhall](https://twitter.com/justinwhall)


# WP Buildhook Deploy ("LittleBot Netlify")

**This plugin was formerly know as "LittleBot Netlify". It was renamed to avoid confusion as it can be used to trigger build hooks at, say, AWS Amplify, GitHub or any service that provides a WebHook – _not_ just Netlify.**

Connect your WordPress website to [Netlify](https://www.netlify.com/) (or any service that provides a buildhook) by triggering stage and or production build hooks on post save and or update. This plugin is not tied to Netlify, you can connect other CI systems with webhooks enabled like CircleCI, Travis, AWS Amplify, etc.

## Installation

- Download or clone repository
- Move `wp-buildhook-deploy` to your plugins directory or zip and upload
- Activate plugin
- Add at least one buildhook URL to the setings page `Settings > WP BuildHook Deploy`

### Using Netlify? (It's awesome BTW)

- Create at least one site at Netlify
- [Create a build hook](https://www.netlify.com/docs/webhooks/) for each site (or just one if you're just using one site)
- Add build hook to the `Settings > WP BuildHook Deploy`
- Your WordPress site will call your build hook(s) when publishing, updating or deleting a post

## Gatsby + WordPress + Netlify Starter

[Gatsby + WordPress + Netlify Starter](https://github.com/justinwhall/gatsby-wordpress-netlify-starter) is a plug and play starter to get up and running with continuous deployment from your WordPress site to Netlify with Gatsby.

## Gatsty + WordPress + Live Previews

Checkout [this Gatsby theme](https://github.com/justinwhall/wordpress-gatsby-preview-starter). This could also be used with this plugin to publish to Netlify, AWS Amplify etc when publishing/updating/deleting/etc WordPress Posts/pages.

## Q & A

`Q` **Do you need two sites at Netlify?**

`A` No. This plugin will call your build hook and build your Gatsby (or whatever) site no matter what. The starter mentioned above facilitates a _two_ environment Gatsby set up but other than that, this plugin is totally _front end agnostic_ and you could just as easy trigger one build hook by only adding one build hook URL.

`Q` **Does this plugin support Gutenberg?**

`A` This plugin supports both GutenLOVERS and GutenHATERS. How? It supports Gutenberg as that is what the WordPress editing experience is now. Don't like Gutenberg? This plugin also supports the Classic Editor.

`Q` **Can I use this plugin with other similar system to Netlify like for example AWS Amplify?**

`A` YES. You can use a CI like Amplify, Circle, Travis etc. Depending on what you are trying to do, the plugin may still work as it just calls a webhook URL with some logic around various publishing hooks.
