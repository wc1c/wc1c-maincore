=== WC1C-Maincore ===
Contributors: WC1C, Frescoref
Tags: commerceml, 1c, cml, wc1c, 1c-enterprise
Requires at least: 5.3
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.24.4
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://wc1c.info/market

Seamlessly integrate WooCommerce with 1C products via CommerceML and other protocols, ensuring flexible data exchange for all business data.

== Description ==
A highly flexible, robust, and optimized plugin for data exchange, bridging 1C:Enterprise and WooCommerce. Designed to handle complex synchronization scenarios while maintaining high performance.

= Key Features =
* **Flexibility and Adaptability** – Allows configuring data transformation, filtering, and mapping rules to fit specific business needs, supporting multiple exchange logics within a single installation.
* **Reliability** – Built-in error handling, retry mechanisms, and data validation ensure synchronization integrity even under unstable connections or partial failures.
* **Optimization and Performance** – Batch processing, asynchronous operations, and efficient memory management minimize latency and ensure stable operation with catalogs of any size, including on budget hosting.
* **Multiple Supported Protocols** – Out-of-the-box support for popular exchange formats (CommerceML, OData, and others), enabling integration with different 1С versions and external systems without rewriting the core.
* **Extensibility** – Architecture with hooks and integration points allows external extensions to add any additional logic (e.g., order processing, stock export, price synchronization) without modifying the base code, simplifying updates and customization.

Explore all features: [https://wc1c.info/features](https://wc1c.info/features)

== Translations ==
* English (Default)
* Russian (Built-in)

== Installation ==
1. Extract the archive and upload the `wc1c-maincore` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the plugin settings to configure your first 1C integration.

== Frequently Asked Questions ==

= What versions of 1C are supported? =
The plugin supports any version of 1C:Enterprise that features online store integration capabilities (e.g., CommerceML). The exact level of support depends on the specific exchange schema and the underlying 1C configuration.

= Is it possible to update only specific product data? =
Yes. Depending on the exchange schema and installed extensions, you can perform selective updates. For example, you can update only prices or stock levels, or apply conditional logic to update specific product attributes.

= Does this plugin support order synchronization? =
Order processing is not included in the core but can be added via external extensions using the plugin's hooks and filters.

= Can I export products from WooCommerce to 1C? =
The core currently handles one-way import from 1C to WooCommerce. Reverse export is available through extensions.

= Which exchange protocols are supported? =
CommerceML and OData are supported out of the box, with the ability to extend to custom protocols.

= Missing a feature? How can I add it? =
First, check the official website's extensions directory. If no existing add-on fits your needs, you can develop a custom solution or request professional services. Alternatively, you can submit a feature request for the core plugin or implement custom logic using WordPress actions and filters (the plugin architecture is highly extensible).

== Screenshots ==

1. Configuration List
2. Tools Dashboard
3. Global Settings
4. Creating a New Configuration
5. Editing a Configuration
6. Advanced Configuration Settings

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