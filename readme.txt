=== WC1C ===
Contributors: WC1C, Frescoref
Tags: commerceml, 1c, 1с, odata, woocommerce
Requires at least: 5.3
Tested up to: 7.1
Requires PHP: 7.4
Requires Plugins: woocommerce
Stable tag: 0.24.6
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://wc1c.info/market

Integration between WooCommerce and 1C products (via multiple connections, formats, plugin extensions, and protocols).

== Description ==
Seamlessly integrate WooCommerce with 1C products via CommerceML and other protocols, ensuring flexible data exchange for all business data.
Designed to handle complex synchronization scenarios while maintaining high performance.

* The plugin is absolutely free for everyone under the GPLv3 license or later version.
* This is a free full-featured version that is maintained by the WC1C team and contributors.
* The WordPress.org support forum is not affiliated with the WC1C team and is not visited by them; for communication and productive work with the WC1C team, use their services.
* The WC1C team develops this free plugin based on the needs of users of their services and is the main contributor.

* **Flexibility and Adaptability** — Allows configuring data transformation, filtering, and mapping rules to fit specific business needs, supporting multiple exchange logics within a single installation.
* **Reliability** — Built-in error handling, retry mechanisms, and data validation ensure synchronization integrity even under unstable connections or partial failures.
* **Optimization and Performance** — Batch processing, asynchronous operations, and efficient memory management minimize latency and ensure stable operation with catalogs of any size, including on budget hosting.
* **Multiple Supported Protocols** — Out-of-the-box support for popular exchange formats (CommerceML, OData, and others), enabling integration with different 1C versions and external systems without rewriting the core.
* **Extensibility** — Architecture with hooks and integration points allows external extensions to add any additional logic (e.g., order processing, stock export, price synchronization) without modifying the base code, simplifying updates and customization.

Explore all sorted features on [wc1c.info/features](https://wc1c.info/features) and planned changes on [wc1c.info/changes/wc1c-maincore/roadmap](https://wc1c.info/changes/wc1c-maincore/roadmap)

= How it works =
1. Install and activate WC1C.
2. Create a new configuration for your 1C connection.
3. Select the required exchange schema.
4. Configure mapping, filtering, and transformation rules if needed.
5. Make settings in 1C products on the exchange scheme you have selected on the website.
6. Monitor the result and adjust settings before regular synchronization.

= Protocols and formats =
Out of the box, WC1C supports:

* CommerceML — the standard exchange format for online stores.
* OData — planned for future versions.

The plugin architecture also allows extending exchange support to custom protocols. Actual compatibility depends on your 1C configuration and exchange schema.

= Extensibility for developers =
The plugin architecture provides integration points for custom development:

* use WordPress actions and filters;
* add external extensions without modifying the core;
* extend exchange protocols;
* implement custom order, stock, price, or catalog logic where required.

This approach keeps the core stable and makes updates safer.

= Trademarks =
"WordPress", "Woo", "WooCommerce" and "1C" are trademarks of their respective owners.
This project is not affiliated with, endorsed by, or sponsored by the trademark holders unless otherwise stated.

== Privacy Policy ==
This plugin does not collect, transmit, or store any data on external servers. All store data (products, orders, configurations, and exchange logs) remains entirely on the end user's server. The core does not include any telemetry, tracking, cookies, or remote data processing. Exchange logs are stored locally on the server filesystem (accessible via FTP) and are not sent to the WC1C team. If you use third-party extensions, please refer to their respective privacy policies.

== Translations ==
* English (Default)
* Russian (Built-in)

Want to help translate WC1C into your language? You can contribute via the [WordPress Translation Platform](https://translate.wordpress.org/projects/wp-plugins/wc1c-maincore).

== Installation ==
1. Ensure WooCommerce is installed and activated (Required Plugin). The server must meet requirements: PHP 7.4+, WordPress 5.3+, PHP extensions `SimpleXML` and `XMLReader` enabled.
2. Install via WordPress admin: go to `Plugins` → `Add New` → `Upload Plugin` and select the `wc1c-maincore.zip` archive, or extract the archive and upload the `wc1c-maincore` folder to the `/wp-content/plugins/` directory via FTP.
3. Activate the plugin through the `Plugins` menu in WordPress.
4. Navigate to `WC1C` → `Configurations` to create your first 1C integration. Configure PHP limits (`memory_limit` 256M+, `max_execution_time` 120s+) for stable exchange with large catalogs.

== Frequently Asked Questions ==

= What versions of 1C are supported? =
The plugin supports 1C:Enterprise and newer that provide standard online-store exchange (CommerceML). The exact level of support depends on the exchange schema selected on the website and your 1C configuration.

= Does the plugin work with 1C:UNF, 1C:UT, 1C:Accounting, or 1C:ERP? =
Yes. The plugin works with any 1C configuration that provides a standard online-store exchange schema. Compatibility depends on the schema version exposed by your 1C installation, not on the configuration name.

= Is the plugin compatible with WooCommerce High-Performance Order Storage (HPOS)? =
Yes. WC1C fully supports HPOS in order exchange schemas and is regularly tested against recent WooCommerce versions (up to 11.0). WooCommerce is a required plugin.

= Does the plugin work on shared hosting? =
It can, but stability depends on your hosting's PHP limits (`memory_limit`, `max_execution_time`, `max_input_time`). For catalogs over several thousand products we recommend VPS or dedicated hosting with tuned PHP settings.

= What PHP settings affect exchange stability? =
The most important are `memory_limit` (256 MB or higher recommended), `max_execution_time` (at least 120 s for large catalogs), `post_max_size`, `upload_max_filesize` (for large CommerceML files), and available RAM. With excessively low limits, exchange may silently fail.

= Are product images synchronized? =
Image synchronization depends on the exchange schema. Some schemas deliver images out of the box, others require extensions or custom field mapping.

= Are product variations and attributes supported? =
Yes, variations and attributes (categories, properties) can be processed, but support depends on how your 1C configuration exposes them through the selected schema. Complex catalogs may require additional extensions or custom mapping rules.

= What happens to products deleted in 1C? =
This behavior is configurable. By default, products imported from 1C are not automatically deleted from WooCommerce to prevent accidental data loss. Cleanup logic can be enabled via exchange schema settings or cleanup extensions.

= Can I sync only prices or only stock levels? =
Yes. Depending on the exchange schema and installed extensions, you can perform selective updates. For example, update only prices or only stock levels, as well as apply conditional logic to specific product attributes.

= How are existing products matched during import? =
By default, products are matched by their 1C identifier. If a matching identifier exists, the product is updated; otherwise, a new product is created. Mapping rules can be adjusted per configuration. For advanced matching, a dedicated extension is available.

= What CommerceML versions are supported? =
The core handles CommerceML 2.03 and compatible variants, depending on the exchange schema used. The `ProductsCML` schema is included in the core.

= Which exchange protocols are supported? =
CommerceML is supported out of the box. OData is planned for future versions. The architecture also allows extending support to custom protocols.

= Where can I view exchange logs? =
Logs are stored on the server in the WC1C directory and accessible via FTP. They show each step of the synchronization, errors, and performance statistics. A dedicated extension can display logs directly in the WordPress admin panel.

= What should I do if the exchange fails or hangs? =
Check the logs for error messages. The most common causes are PHP limit issues, unstable connection to 1C, or malformed data from the 1C schema. Most failures are resolved by adjusting PHP limits or fixing source data in 1C. The plugin also includes built-in retry and resume mechanisms for transient failures.

= Does the plugin support order synchronization? =
Order processing is not included in the core but can be added via external extensions using the built-in extension capabilities of the core.

= Can I export products from WooCommerce to 1C? =
The core currently supports one-way import from 1C to WooCommerce. Reverse export is available through extensions. Support for this functionality is planned for future core versions.

= Is the plugin really free? Where is the catch? =
WC1C is 100% free under GPLv3. The core plugin is fully functional and does not require paid add-ons. The WC1C team offers paid services, extensions, and support for users who need professional help or advanced scenarios.

= Missing a feature? How can I add it? =
First, check the [catalog of ready-made extensions](https://wc1c.info/market). If no suitable add-on exists, you can develop a custom solution or order professional services from any provider.

= Where can I get professional support or custom development? =
The WordPress.org support forum is not affiliated with the WC1C team and is not monitored by them. For direct communication with the core developers, paid support, custom integration, or audit services, use the [official website](https://wc1c.info).

== Screenshots ==

1. Configuration list — manage multiple 1C connections from one place, track status and last sync.
2. Tools dashboard — monitor plugin operations, service data, and exchange statistics.
3. Global settings — control common plugin behavior and default exchange options.
4. Creating a new configuration — set up a new 1C exchange scenario with schema selection.
5. Editing a configuration — adjust protocol, mapping, filtering, and transformation rules.
6. Advanced settings — fine-tune behavior for complex catalogs and large data volumes.

== Upgrade Notice ==

= 0.24.6 =
Stability improvements and bug fixes. No breaking changes. Recommended to update.

= 0.24.5 =
Stability improvements and bug fixes. No breaking changes. Recommended to update.

= 0.24.4 =
Stability improvements, readme formatting updates. Tested up to WordPress 7.1.

= 0.24.3 =
Stability improvements and translation updates. Tested up to WooCommerce 11.0.

= 0.24.2 =
Removed promotional materials, updated ProductsCML to 0.16.2. No breaking changes.

= 0.24.1 =
Updated dependencies (monolog, psr/log, psr/http-message), ProductsCML to 0.16.1. No breaking changes.

= 0.24.0 =
This version requires PHP 7.4+ and WooCommerce 4.5+. Please verify your environment before updating.

== Changelog ==
A summary of major changes. View the full changelog on [wc1c.info/changes](https://wc1c.info/changes)

= 0.24.6 =
* Updated: `readme.txt`.
* Fixed: Miscellaneous bugs and stability improvements.

= 0.24.5 =
* Updated: `readme.txt`.
* Fixed: Miscellaneous bugs and stability improvements.

= 0.24.4 =
* Updated: `readme.txt` formatting.
* Fixed: Miscellaneous bugs and stability improvements.
* Tested: WordPress up to 7.1.

= 0.24.3 =
* Fixed: Miscellaneous bugs and stability improvements.
* Tested: WooCommerce up to 11.0.
* Updated: Translation files.

= 0.24.2 =
* Removed: Promotional materials.
* Updated: ProductsCML library to v0.16.2.
* Updated: ProductsCleanerCML library to v0.5.2.
* Fixed: Miscellaneous bugs and stability improvements.
* Updated: `readme.txt` formatting.

= 0.24.1 =
* Updated: ProductsCML to v0.16.1.
* Updated: ProductsCleanerCML to v0.5.1.
* Updated: `monolog/monolog` dependency (1.27.1 => 2.11.0).
* Updated: `psr/http-message` dependency (1.0.1 => 2.0).
* Updated: `psr/log` dependency (1.1.4 => 2.0.0).
* Updated: `readme.txt` formatting.
* Removed: `tecodes/client` dependency.
* Fixed: Inline form rendering issues.

= 0.24.0 =
* Requirement: Minimum PHP version is now 7.4.
* Added: Support for PHP 8.4 & 8.5.
* Requirement: Minimum WooCommerce version is now 4.5.
* Requirement: Minimum WordPress version is now 5.3.
* Tested: WordPress up to v6.6, 6.7, 6.8, 6.9, 7.0.
* Tested: WooCommerce up to v8.x, 9.x, 10.9.
* Added: New core transliterator engine.
* Updated: Bootstrap to v5.3.8.
* Updated: ProductsCML to v0.16.0.
* Updated: ProductsCleanerCML to v0.5.0.
* Updated: Admin UI styles.
* Updated: Translation files.
* Fixed: Miscellaneous bugs.