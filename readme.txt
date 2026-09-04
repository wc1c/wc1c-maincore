=== WC1C ===
Contributors: WC1C, Frescoref
Tags: commerceml, 1c, cml, odata, 1c-enterprise
Requires at least: 5.3
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.24.4
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://wc1c.info/market

Integration between WooCommerce and 1C products (via multiple connections, formats, plugin extensions, and protocols).

== Description ==
Seamlessly integrate WooCommerce with 1C products via CommerceML and other protocols, ensuring flexible data exchange for all business data.
Designed to handle complex synchronization scenarios while maintaining high performance.

> The plugin is absolutely free for everyone under the GPLv3 license or later version.
> This is a free full-featured version that is maintained by the WC1C team and contributors.
> The WordPress.org support forum is not affiliated with the WC1C team and is not visited by them; for communication and productive work with the WC1C team, use their services.
> The WC1C team develops this free plugin based on the needs of users of their services and is the main contributor.

* **Flexibility and Adaptability** > Allows configuring data transformation, filtering, and mapping rules to fit specific business needs, supporting multiple exchange logics within a single installation.
* **Reliability** > Built-in error handling, retry mechanisms, and data validation ensure synchronization integrity even under unstable connections or partial failures.
* **Optimization and Performance** > Batch processing, asynchronous operations, and efficient memory management minimize latency and ensure stable operation with catalogs of any size, including on budget hosting.
* **Multiple Supported Protocols** > Out-of-the-box support for popular exchange formats (CommerceML, OData, and others), enabling integration with different 1C versions and external systems without rewriting the core.
* **Extensibility** > Architecture with hooks and integration points allows external extensions to add any additional logic (e.g., order processing, stock export, price synchronization) without modifying the base code, simplifying updates and customization.

Explore all sorted features: [https://wc1c.info/features](https://wc1c.info/features)

= How it works =
1. Install and activate WC1C.
2. Create a new configuration for your 1C connection.
3. Select the required exchange schema.
4. Configure mapping, filtering, and transformation rules if needed.
5. Make settings in 1C products on the exchange scheme you have selected on the website.
6. Monitor the result and adjust settings before regular synchronization.

= Protocols and formats =
Out of the box, WC1C supports:
* CommerceML;
The plugin architecture also allows extending exchange support to custom protocols. Actual compatibility depends on your 1C configuration and exchange schema.

= Extensibility for developers =
The plugin architecture provides integration points for custom development:
* use WordPress actions and filters;
* add external extensions without modifying the core;
* extend exchange protocols;
* implement custom order, stock, price, or catalog logic where required.
This approach keeps the core stable and makes updates safer.

= Trademarks =
"WordPress","Woo", "WooCommerce" and "1C" are trademarks of their respective owners.
This project is not affiliated with, endorsed by, or sponsored by the trademark holders unless otherwise stated.

== Translations ==
* English (Default)
* Russian (Built-in)

Want to help translate WC1C into your language? You can contribute via the [WordPress Translation Platform](https://translate.wordpress.org/projects/wp-plugins/wc1c-maincore).

== Installation ==
1. Extract the archive and upload the `wc1c-maincore` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the plugin settings to configure your first 1C integration.

== Frequently Asked Questions ==

= Compatibility and requirements =

= What versions of 1C are supported? =
The plugin supports any version of 1C:Enterprise that features integration capabilities with online stores (e.g., CommerceML or OData). The exact level of support depends on the specific exchange scheme selected on the website and the underlying 1C configuration.

= Does the plugin work with 1C:Uniftrade (UNF), 1C:Trade Management (UT), 1C:Accounting, or 1C:ERP? =
Yes, the plugin works with any 1C configuration that provides a standard exchange scheme for online stores. Compatibility depends on the schema version exposed by your 1C installation, not on the configuration name.

= Is the plugin compatible with WooCommerce High-Performance Order Storage (HPOS)? =
Yes. WC1C fully supports HPOS in order exchange schemas provided by the WC1C team and is regularly tested against recent WooCommerce versions.

= Does the plugin work on shared hosting? =
It can, but stability depends on your hosting's PHP limits (memory_limit, max_execution_time, max_input_time). For catalogs over several thousand products, we recommend VPS or dedicated hosting with tuned PHP settings.

= What PHP settings affect exchange stability? =
The most important are `memory_limit` (256 MB or higher recommended), `max_execution_time` (at least 120 s for large catalogs), `post_max_size`, `upload_max_filesize`, and available RAM. With excessively low limits, exchange with large catalogs may silently fail.

= Data synchronization scenarios =

= Are product images synchronized? =
Image synchronization depends on the exchange schema. Some schemas deliver images out of the box, others require extensions or custom field mapping.

= Are product variations and attributes supported? =
Yes, variations and attributes can be processed, but support depends on how your 1C configuration exposes them through the selected schema. Complex catalogs may require additional extensions or custom mapping rules.

= What happens to products that were deleted in 1C? =
This behavior is configurable. By default, products imported from 1C are not automatically deleted from WooCommerce to prevent accidental data loss. Cleanup logic can be enabled via exchange schema settings or cleanup extensions.

= Can I sync only prices or only stock levels without re-importing the whole catalog? =
Yes. Depending on the exchange schema and installed extensions, you can perform selective updates. For example, you can update only prices or only stock levels, as well as apply conditional logic to update specific product attributes.

= What happens to existing WooCommerce products during the first import? =
By default, products are matched by their 1C identifier. If a matching identifier exists, the product is updated; otherwise, a new product is created. Mapping rules can be adjusted per configuration.
For advanced matching of existing products, a dedicated extension is available.

= Reliability and troubleshooting =

= Where can I view exchange logs? =
Exchange logs are accessible via FTP on the server. They show each step of the synchronization, errors, and performance statistics.
For more convenient log viewing without FTP manipulation, you can use the corresponding extension that displays logs in the WordPress admin panel.

= What should I do if the exchange fails or hangs? =
Check the logs for error messages. The most common causes are PHP limit issues, unstable connection to 1C, or malformed data from the 1C schema. Most failures can be resolved by adjusting PHP limits or fixing the source data in 1C.

= Does the plugin retry failed exchanges? =
Yes. The plugin includes built-in retry mechanisms for transient failures, such as connection drops or temporary errors on the 1C side. Persistent errors are logged for manual review.

= Can I resume an interrupted exchange? =
Yes. The plugin supports resumable exchanges where the schema allows. Partial imports are tracked, so restarting does not require reprocessing the entire catalog (depending on the schema used).

= Core scope and extensions =

= Does this plugin support order synchronization? =
Order processing is not included in the core but can be added via external extensions using the built-in extension capabilities of the core.

= Can I export products from WooCommerce to 1C? =
The core currently supports one-way import from 1C to WooCommerce. Reverse export is available through extensions. Support for this functionality is planned for future core versions.

= Which exchange protocols are supported? =
CommerceML is supported out of the box, with the ability to extend to custom protocols. Support for additional protocols is planned for future versions.

= Is the plugin really free? Where is the catch? =
WC1C is 100% free under GPLv3. The core plugin is fully functional and does not require paid add-ons. The WC1C team offers paid services, extensions, and support for users who need professional help or advanced scenarios.

= Missing a feature? How can I add it? =
First, check the catalog of ready-made extensions, for example, on the WC1C team's website. If no suitable add-on exists, you can develop a custom solution or order professional services from any provider.

= Where can I get professional support or custom development? =
The WordPress.org support forum is not affiliated with the WC1C team and is not monitored by them. For direct communication with the core developers, paid support, custom integration, or audit services, use the official website: https://wc1c.info

== Screenshots ==

1. Configuration list: manage multiple 1C connections from one place.
2. Tools dashboard: monitor plugin operations and service data.
3. Global settings: control common plugin behavior.
4. Creating a new configuration: set up a new 1C exchange scenario.
5. Editing a configuration: adjust protocol, mapping, and exchange rules.
6. Advanced settings: fine-tune behavior for complex catalogs.

== Upgrade Notice ==

= 0.24.0 =
This version requires PHP 7.4+ and WooCommerce 4.5+. Please verify your environment before updating.

== Changelog ==
A summary of major changes. [View the full changelog here.](https://wc1c.info/changelogs)

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

= 0.23.0 =
* Improved: Language phrases and localization.
* Improved: Admin interface design.
* Improved: Full compatibility with WooCommerce High-Performance Order Storage (HPOS).
* Improved: Removed redundant log settings for the receiver.
* Tested: WordPress up to v6.4 & v6.5.
* Tested: WooCommerce up to v8.8.
* Added: Support for PHP 8.3.
* Updated: Bootstrap to v5.3.2.
* Updated: Admin UI styles.
* Updated: Default level for main events set to 250.
* Updated: ProductsCML to v0.15.0.
* Fixed: 1C information column display in the new HPOS orders list.
* Fixed: Configuration sorting logic.
* Fixed: Miscellaneous bugs.

= 0.22.0 =
* Tested: WordPress up to v6.3.
* Tested: WooCommerce up to v7.9 & v8.0.
* Updated: ProductsCML to v0.14.0.
* Updated: Translation files.
* Updated: Core CML library.
* Fixed: Miscellaneous bugs.

= 0.21.1 =
* Fixed: Miscellaneous bugs.

= 0.21.0 =
* Tested: WooCommerce up to v7.6, 7.7, 7.8.
* Updated: ProductsCML to v0.13.0.
* Updated: Bootstrap to v5.3.0.
* Updated: Translation files.
* Fixed: Miscellaneous bugs.

= 0.20.0 =
* Tested: WordPress up to v6.2.
* Updated: ProductsCML to v0.12.0.
* Updated: Translation files.
* Updated: Core CML library.
* Improved: Promotional banner's logic.
* Added: Woplucore framework integration.
* Fixed: Miscellaneous bugs.

= 0.19.2 =
* Updated: ProductsCML to v0.11.2.

= 0.19.1 =
* Updated: ProductsCML to v0.11.1.

= 0.19.0 =
* Tested: WooCommerce up to v7.5.
* Updated: ProductsCML to v0.11.0.
* Added: Woplucore framework integration.
* Updated: Woplucore to the latest version.
* Fixed: Miscellaneous bugs.

= 0.18.2 =
* Updated: ProductsCML to v0.10.2.
* Updated: Core CML library.
* Fixed: Miscellaneous bugs.

= 0.18.1 =
* Updated: ProductsCML to v0.10.1.

= 0.18.0 =
* Updated: ProductsCML to v0.10.0.
* Updated: Translation files.
* Updated: Core CML library.
* Updated: Woplucore to the latest version.
* Fixed: Miscellaneous bugs.

= 0.17.0 =
* Tested: PHP up to v8.2.
* Tested: WooCommerce up to v7.4.
* Updated: ProductsCML to v0.9.0.
* Updated: ProductsCleanerCML to v0.4.0.
* Updated: Translation files.
* Updated: Bootstrap to v5.2.3.
* Updated: Woplucore to the latest version.
* Updated: Core CML library.
* Updated: Wotices to the latest version.
* Fixed: Miscellaneous bugs.

= 0.16.0 =
* Updated: ProductsCML to v0.8.0.
* Updated: Translation files.
* Improved: Woplucore integration.
* Improved: Status indicators for configurations.
* Improved: Custom receiver support in schemas.
* Improved: Configuration sorting by date.
* Fixed: Miscellaneous bugs.

= 0.15.1 =
* Updated: Translation files.
* Fixed: Critical error.

= 0.15.0 =
* Tested: WooCommerce up to v7.3.
* Updated: ProductsCML to v0.7.0.
* Updated: Translation files.
* Fixed: Miscellaneous bugs.

= 0.14.14 =
* Updated: Translation files.
* Fixed: Miscellaneous bugs.

= 0.14.13 =
* Updated: Translation files.
* Fixed: Miscellaneous bugs.

= 0.14.12 =
* Updated: Translation files.
* Fixed: Miscellaneous bugs.

= 0.14.11 =
* Fixed: Miscellaneous bugs.

= 0.14.10 =
* Fixed: Miscellaneous bugs.

= 0.14.9 =
* Updated: ProductsCML to v0.6.0.
* Updated: ProductsCleanerCML to v0.3.0.
* Updated: Translation files.
* Fixed: Miscellaneous bugs.

= 0.14.8 =
* Fixed: Miscellaneous bugs.

= 0.14.7 =
* Fixed: Miscellaneous bugs.

= 0.14.6 =
* Fixed: Miscellaneous bugs.

= 0.14.5 =
* Fixed: Miscellaneous bugs.

= 0.14.4 =
* Fixed: Miscellaneous bugs.

= 0.14.3 =
* Fixed: Miscellaneous bugs.

= 0.14.2 =
* Fixed: Miscellaneous bugs.

= 0.14.1 =
* Fixed: Miscellaneous bugs.

= 0.14.0 =
* Renamed: Plugin slug from `wc1c` to `wc1c-maincore`.
* Fixed: Miscellaneous bugs.

= 0.13.1 =
* Fixed: Composer dependency issues.

= 0.13.0 =
* Updated: ProductsCML to v0.4.0.
* Updated: Translation files.
* Tested: WooCommerce up to v7.2.
* Fixed: Miscellaneous bugs.

= 0.12.0 =
* Updated: ProductsCML to v0.3.0.
* Updated: Translation files.
* Fixed: Miscellaneous bugs.

= 0.11.1 =
* Fixed: Miscellaneous bugs.

= 0.11.0 =
* Requirement: Minimum PHP version is now 7.0.
* Requirement: Minimum WooCommerce version is now 4.3.
* Requirement: Minimum WordPress version is now 5.2.
* Tested: WordPress up to v6.1.
* Tested: WooCommerce up to v7.1, 7.0, 6.9.
* Updated: Core CML & WC libraries.
* Fixed: Miscellaneous bugs.

= 0.10.0 =
* Tested: WooCommerce up to v6.8.
* Added: Advanced product settings in ProductsCML.
* Fixed: Miscellaneous bugs.

= 0.9.0 =
* Tested: WooCommerce up to v6.7.
* Refactored: Moved WC and CML libraries from `src` to `vendor`.
* Fixed: Miscellaneous bugs.

= 0.8.3 =
* Fixed: Miscellaneous bugs.

= 0.8.2 =
* Fixed: Infinite loop (`while`) error.

= 0.8.1 =
* Fixed: JSON decoder error on PHP 7 environments.

= 0.8.0 =
* Tested: WordPress up to v6.0.
* Tested: WooCommerce up to v6.6.
* Various improvements and bug fixes.

= 0.7.0 =
* Initial Public Release.

= 0.1.0 =
* Initial Alpha Release.