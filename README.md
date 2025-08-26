<img src="/Logo_3.0.0.png" alt="Filter & Grids Logo" width="250" />

# YMC Filter Grids 

**Filter & Grids** is a powerful and flexible WordPress plugin that allows you to easily filter and display your posts, custom post types, and other content in beautifully designed grid layouts.  
With an intuitive interface and customizable filters, you can create dynamic, responsive, and visually appealing content grids without touching a single line of code.

### Key Features
- **Advanced filtering** — filter posts by categories, tags, taxonomies, custom fields, and more.
- **Multiple grid layouts** — choose from a variety of pre-designed layouts or create your own.
- **Responsive design** — works perfectly on desktop, tablet, and mobile devices.
- **Ajax-powered loading** — load filtered results without reloading the page.
- **Easy integration** — insert grids anywhere with a shortcode or block editor.

Whether you’re building a portfolio, a product catalog, or a news feed, **Filter & Grids** gives you full control over how your content is displayed and how your visitors interact with it.

---

# Filter & Grids

[![Version](https://img.shields.io/badge/version-3.0.0-blue.svg)](UPGRADE-NOTICE.md)

**Filter & Grids** is a powerful and flexible WordPress plugin that allows you to easily filter and display posts, custom post types, and other content in beautifully designed grid layouts.

---

## 📢 Upgrade Notice
⚠️ **Important:** Before updating to the latest version, please read the [Upgrade Notice](UPGRADE-NOTICE.md) for important information about compatibility, migration steps, and potential changes that might affect your site.

---

## Key Features
- Advanced filtering — filter posts by categories, tags, taxonomies, custom fields, and more.
- Multiple grid layouts — choose from pre-designed templates or create your own.
- Responsive design — works perfectly on desktop, tablet, and mobile devices.
- Ajax-powered loading — load filtered results without reloading the page.
- Easy integration — insert grids anywhere with a shortcode or block editor.

---

## Installation
1. Download the plugin.
2. Upload it to your WordPress plugins directory.
3. Activate the plugin through the **Plugins** menu in WordPress.

---

## License
This plugin is licensed under the [GPL v2 or later](LICENSE).





# 🎛️ Developer Hooks

This documentation describes available **WordPress filter hooks** for customizing the output and behavior of the **YMC Filter Grids** plugin.


## 🧭 Table of Contents

- [Introduction](#introduction)
- [Usage](#usage)
- [Filter Hook Categories](#filter-hook-categories)
    - [Post Layout Filters](#post-layout-filters)
    - [Post Layout Actions](#post-layout-actions)
    - [Grid Layout Actions](#grid-layout-actions)
    - [Filter Layout Actions](#filter-layout-actions)
    - [Pagination Filters](#pagination-filters)
    - [Search Filters](#search-filters)
    - [Popup Layout Filters](#popup-layout-filters)
    - [Custom Filter Layout](#custom-filter-layout)
    - [Custom Post Layout](#custom-post-layout)
    - [JavaScript Integration Hooks](#javascript-integration-hooks)
    - [Advanced Developer Hooks](#advanced-developer-hooks)
- [YMCFilterGrid: Global Object API](#ymcfiltergrid-global-object-api)
- [Changelog](#changelog)

---

## 📘 Introduction

YMC Filter Grids provides flexible rendering of post filters with options to extend, override, or manipulate components using WordPress native `apply_filters()` functionality.

This document helps developers understand where and how to hook into the plugin's logic.

---

## ⚙️ Usage

To use a filter hook, add the following to your theme or plugin:

- Activate Plugin or upload the entire 'ymc-smart-filter' folder to the '/wp-content/plugins/' directory.
- Add new Filter & Grids
- Copy Filter & Grids shortcode and paste to any page or post
- Set setting for each post


## ⚙️ Filter Hook Categories

Add code to `functions.php` to your theme

- `$filter_id` unique ID of the filter (e.g., 72)  Shortcode tab [ymc_filter id='72']
- `$instance_index` instance number of this filter on the page


### Post Layout Filters

Customize the date format used in post layout.
```php
apply_filters('ymc/post/layout/date_format', $date_format);
apply_filters('ymc/post/layout/date_format_{filter_id}', $date_format);
apply_filters('ymc/post/layout/date_format_{filter_id}_{$instance_index}', $date_format);
```
Usage Example:
```php
add_filter('ymc/post/layout/date_format_72', function ($date_format) {
    return 'F j, Y';
});
```

### Post Layout Actions
Insert Hooks.
Use this hook to insert custom content before or after each post item in the grid layout.
These hooks are triggered for each post in the loop, and you can attach your custom render logic via add_action()

Parameters:

- `$post_number` (int): Sequence number of the post in the loop (starting from 1).
- `$post_id` (int): The WordPress post ID.
- `$paged` (int): Current page number (useful for paginated grids).
- `$per_page` (int): Number of posts per page.

#### Before Post Layout
```php
do_action('ymc/post/layout/before/post_item', $post_number, $post_id, $paged, $per_page);
do_action('ymc/post/layout/before/post_item_{filter_id}', $post_number, $post_id, $paged, $per_page);
do_action('ymc/post/layout/before/post_item_{filter_id}_{$instance_index}', $post_number, $post_id, $paged, $per_page);
```
#### After Post Layout
```php
do_action('ymc/post/layout/after/post_item', $post_number, $post_id, $paged, $per_page);
do_action('ymc/post/layout/after/post_item_{filter_id}', $post_number, $post_id, $paged, $per_page);
do_action('ymc/post/layout/after/post_item_{filter_id}_{$instance_index}', $post_number, $post_id, $paged, $per_page);
```
Usage Example:
```php
add_action( "ymc/post/layout/before/post_item_72", function($post_number, $post_id, $paged, $per_page) {
  if($post_number === 2) {
      echo "<div>Insert post number {$post_number}. Post ID: {$post_id}. Paged: {$paged}. Per page: {$per_page}</div>";
  }
}, 10, 4);
```

### Grid Layout Actions
#### Before Post Layout
```php
do_action('ymc/grid/before_post_layout');
do_action('ymc/grid/before_post_layout_{filter_id}');
do_action('ymc/grid/before_post_layout_{filter_id}_{$instance_index}');
```

#### After Post Layout
```php
do_action('ymc/grid/after_post_layout');
do_action('ymc/grid/after_post_layout_{filter_id}');
do_action('ymc/grid/after_post_layout_{filter_id}_{$instance_index}');
```

### Filter Layout Actions
#### Top Position
```php
do_action('ymc/filter/layout/top/before');
do_action('ymc/filter/layout/top/before_{filter_id}');
do_action('ymc/filter/layout/top/before_{filter_id}_{$instance_index}');

do_action('ymc/filter/layout/top/after');
do_action('ymc/filter/layout/top/after_{filter_id}');
do_action('ymc/filter/layout/top/after_{filter_id}_{$instance_index}');
```

#### Left Position
```php
do_action('ymc/filter/layout/left/before');
do_action('ymc/filter/layout/left/before_{filter_id}');
do_action('ymc/filter/layout/left/before_{filter_id}_{$instance_index}'x);

do_action('ymc/filter/layout/left/after');
do_action('ymc/filter/layout/left/after_{filter_id}');
do_action('ymc/filter/layout/left/after_{filter_id}_{$instance_index}');
```

#### Right Position
```php
do_action('ymc/filter/layout/right/before');
do_action('ymc/filter/layout/right/before_{filter_id}');
do_action('ymc/filter/layout/right/before_{filter_id}_{$instance_index}');

do_action('ymc/filter/layout/right/after');
do_action('ymc/filter/layout/right/after_{filter_id}');
do_action('ymc/filter/layout/right/after_{filter_id}_{$instance_index}');
```


### Pagination Filters
`ymc/pagination/prev_text`
Filter the "Previous" button text in pagination.
```php
apply_filters('ymc/pagination/prev_text', $prev_button_text);
apply_filters('ymc/pagination/prev_text_{filter_id}', $prev_button_text);
apply_filters('ymc/pagination/prev_text_{filter_id}_{$instance_index}', $prev_button_text);
```
`ymc/pagination/next_text`
Filter the "Next" button text in pagination.
```php
apply_filters('ymc/pagination/next_text', $next_button_text);
apply_filters('ymc/pagination/next_text_{filter_id}', $next_button_text);
apply_filters('ymc/pagination/next_text_{filter_id}_{$instance_index}', $next_button_text);
```
`ymc/pagination/load_more_text`
```php
apply_filters('ymc/pagination/load_more_text', $load_more_text);
apply_filters('ymc/pagination/load_more_text_{filter_id}', $load_more_text);
apply_filters('ymc/pagination/load_more_text_{filter_id}_{$instance_index}', $load_more_text);
```

Usage Example:
```php
add_filter('ymc/pagination/prev_text_72', function ($prev_button_text) {
    return 'Previous Page';
});
add_filter('ymc/pagination/next_text_72_1', function ($next_button_text) {
    return 'Next Page';
});
add_filter('ymc/pagination/load_more_text_72', function ($load_more_text) {
    return 'Custom Load More Text';
});
```

### Search Filters
`ymc/search/results_found_text`
Filter the "Previous" button text in pagination.
```php
apply_filters('ymc/search/results_found_text', $results_found_text);
apply_filters('ymc/search/results_found_text_{filter_id}', $results_found_text);
apply_filters('ymc/search/results_found_text_{filter_id}_{$instance_index}', $results_found_text);
```
Usage Example:
```php
add_filter('ymc/search/results_found_text_72', function ($results_found_text) {
    return 'Custom Results Found Text';
  });
```

### Popup Layout Filters
`ymc/popup/custom_layout` 

Inject or override popup content layout.

```php
apply_filters('ymc/popup/custom_layout', $content, $post_id);
apply_filters('ymc/popup/custom_layout_{filter_id}', $content, $post_id);
apply_filters('ymc/popup/custom_layout_{filter_id}_{$instance_index}', $content, $post_id);
```
Parameters:
- `string $content`: The content to be displayed in the popup.
- `int $post_id`: The ID of the post for which the popup is being displayed.

Usage Example:
```php
add_filter('ymc/popup/custom_layout_72', function ($content, $post_id) {
	$content = 'Custom Layout HTML';
    return $content;
}, 10, 2);
```

### Custom Filter Layout
Inject or override custom filter layout.
```php
apply_filters('ymc/filter/layout/{placement}/custom', $output, $filter_id, $tax_name, $term_settings, $container_class);
apply_filters('ymc/filter/layout/{placement}/custom_' . $filter_id, $output, $filter_id, $tax_name, $term_settings, $container_class);
apply_filters('ymc/filter/layout/{placement}/custom_' . $filter_id . '_' . $instance_index, $output, $filter_id, $tax_name, $term_settings, $container_class);
```
`{placement}` can be: `left`, `right`, `top`

Parameters:
- `string $output`: The content to be displayed in the custom filter layout.
- `int $filter_id`: The ID of the custom filter.
- `string $tax_name`: The name of the taxonomy associated with the custom filter.
- `array $term_settings`: An array of term settings for the custom filter.
- `string $container_class`:  CSS Filter container classes.

Array $term_settings is an array of term settings for the custom filter.
Array structure:
```php
$term_settings = array(
    'taxonomy' => 'category',
    'terms' => array(
        $term_id => array(
            'term_id' => $term_id, // int The ID of the term.
            'term_name' => $term_name, // string The name of the term.
            'term_slug' => $term_slug, // string The slug of the term.
            'term_background' => $term_background, // string The background color of the term.
            'term_color' => $term_color, // string The text color of the term.
            'term_class' => $term_class, // string The CSS class for styling the term.
            'term_default' => $term_default, // bool The default state of the term.
            'term_visible' => $term_visible, // bool The visibility state of the term.
            'term_checked' => $term_checked, // bool The checked state of the term.
            'term_icon_class' => $term_icon_class, // string The icon class for the term.
            'term_icon' => $term_icon, // string The icon for the term.
            'term_icon_position' => $term_icon_position, // string The position of the icon within the term.
            'term_icon_url' => $term_icon_position, // string The relative URL of the term's URL icon uploaded.
        ),
    ),
);  
```

Usage Example:
```php
add_filter('ymc/filter/layout/left/custom_72_1', function ($output, $filter_id, $taxonomies, $term_settings, $container_class) {
    $terms = '';
    foreach ($taxonomies as $taxonomy) {
      foreach ($term_settings[$taxonomy]['terms'] as $term_id => $term_info) {
        // For example, output all visible terms
        if ($term_info['term_visible']) {
          $terms .= "<div class=\"{$term_info['term_class']}\">{$term_info['term_name']}</div>";
         }
      }
   }
    return $terms;
}, 10, 5);
```

### Custom Post Layout
Inject or override custom post layout.
```php
apply_filters('ymc/post/layout/custom', $output, $post_id, $filter_id, $popup_class, $post_term_settings);
apply_filters('ymc/post/layout/custom_{filter_id}', $output, $post_id, $filter_id, $popup_class, $post_term_settings);
apply_filters('ymc/post/layout/custom_{filter_id}_{$instance_index}', $output, $post_id, $filter_id, $popup_class, $post_term_settings);
```
Parameters:
- `string $output`: The content to be displayed in the custom post layout.
- `int $post_id`: The ID of the post.
- `int $filter_id`: The ID of the custom filter.
- `string $popup_class`: The CSS class for styling the popup.
- `array $post_term_settings`: An array of term settings for the post.

Array $term_settings is an array of term settings for the custom post.
Array structure:
```php
$term_settings = array(     
    $term_id => array(
        'term_id' => $term_id, // int The ID of the term.
        'term_name' => $term_name, // string The name of the term.
        'term_slug' => $term_slug, // string The slug of the term.
        'term_background' => $term_background, // string The background color of the term.
        'term_color' => $term_color, // string The text color of the term.
        'term_class' => $term_class, // string The CSS class for styling the term.
        'term_default' => $term_default, // bool The default state of the term.
        'term_visible' => $term_visible, // bool The visibility state of the term.
        'term_checked' => $term_checked, // bool The checked state of the term.
        'term_icon_class' => $term_icon_class, // string The icon class for the term.
        'term_icon' => $term_icon, // string The icon for the term.
        'term_icon_position' => $term_icon_position, // string The position of the icon within the term.
        'term_icon_url' => $term_icon_position, // string The relative URL of the term's URL icon uploaded.
    ),   
); 
```

Usage Example:
```php
add_filter('ymc/post/layout/custom_72', function($output, $post_id, $filter_id, $popup_class, $term_settings) {
$output  = '<h2>'.get_the_title($post_id).'</h2>';
$tags = '';

foreach ($term_settings as $term_info) {
  if ($term_info['term_visible'] === 'true') {
      $style_color = !empty($term_info['term_color']) ? 'style="color: '.$term_info['term_color'] .'"' : '';
      $tags .= '<span class="tag" '.$style_color. '> '. esc_html($term_info['term_name']) .'</span>';
  }
}
$output .= $tags;
$output .= '<p>'.wp_trim_words(get_the_content($post_id), 30).'</p>';
$output .= '<a href="'.get_the_permalink($post_id).'">'.esc_html('Read More').'</a>';
// if needed you can add a popup trigger
// data-counter="1" is $instance_index
$output .= '<a class="'.esc_attr($popup_class).'" data-grid-id="'.esc_attr($filter_id).'" data-post-id="'.esc_attr($post_id).'" data-counter="1" href="#">'.esc_html('Open Popup').'</a>';

return $output;
}, 10, 5);
```

Inject or override custom carousel layout.
```php
apply_filters('ymc/post/carousel/content/custom', $output, $post_id, $filter_id, $popup_class, $post_term_settings);
apply_filters('ymc/post/carousel/content/custom_{filter_id}', $output, $post_id, $filter_id, $popup_class, $post_term_settings);
apply_filters('ymc/post/carousel/content/custom_{filter_id}_{$instance_index}', $output, $post_id, $filter_id, $popup_class, $post_term_settings);
```
Parameters:
- `string $output`: The content to be displayed in the custom post layout.
- `int $post_id`: The ID of the post.
- `int $filter_id`: The ID of the custom filter.
- `string $popup_class`: The CSS class for styling the popup.
- `array $post_term_settings`: An array of term settings for the post.

Array $term_settings is an array of term settings for the custom post. The structure is the same as for custom post layout.

Usage Example:
```php
add_filter('ymc/post/carousel/content/custom_72', function($output, $post_id, $filter_id, $popup_class, $term_settings) {
$output  = '<h2>'.get_the_title($post_id).'</h2>';
$tags = '';

foreach ($term_settings as $term_info) {
  if ($term_info['term_visible'] === 'true') {
      $style_color = !empty($term_info['term_color']) ? 'style="color: '.$term_info['term_color'] .'"' : '';
      $tags .= '<span class="tag" '.$style_color. '> '. esc_html($term_info['term_name']) .'</span>';
  }
}
$output .= $tags;
$output .= '<p>'.wp_trim_words(get_the_content($post_id), 30).'</p>';
$output .= '<a href="'.get_the_permalink($post_id).'">'.esc_html('Read More').'</a>';
// if needed you can add a popup trigger
// data-counter="1" is $instance_index
$output .= '<a class="'.esc_attr($popup_class).'" data-grid-id="'.esc_attr($filter_id).'" data-post-id="'.esc_attr($post_id).'" data-counter="1" href="#">'.esc_html('Open Popup').'</a>';

return $output;
}, 10, 5);
```





### JavaScript Integration Hooks

#### Grid Lifecycle Events
These hooks allow you to integrate custom logic at different stages of the grid’s fetch/update process.

`filter_id`: unique ID of the filter (e.g., 72)  Shortcode tab [ymc_filter id='72'].

`instance_index`: instance number of this filter on the page.

- `ymcHooks.doAction('ymc/grid/cancel_fetch', filter);` 

Triggered before a fetch request is made. Useful to cancel or modify the behavior.

Parameters:
- `filter (object)`: The current filter configuration object.

Usage Example:
```js
ymcHooks.addAction('ymc/grid/cancel_fetch', function(filter) {
  if (filter.classList.contains('ymc-filter-72')) {
    target.dataset.loadingEnabled = 'false';
  }
});
```

`
ymcHooks.doAction('ymc/grid/before_update', container);

ymcHooks.doAction('ymc/grid/before_update_filter_id', container);

ymcHooks.doAction('ymc/grid/before_update_filter_id_instance_index', container);
`


Fires just before grid data is rendered into the DOM.

Parameters:
- `container (HTMLElement)`: The DOM element that contains the grid content.

Usage Example:
```js
ymcHooks.addAction('ymc/grid/before_update_72', function(container) {
    console.log('Hook works!', container);  
});
```

- `ymcHooks.doAction('ymc/grid/after_update', data, container);`
- `ymcHooks.doAction('ymc/grid/after_update_filter_id', data, container);`
- `ymcHooks.doAction('ymc/grid/after_update_filter_id_instance_index', data, container);`

Triggered immediately after the DOM has been updated.

Parameters:
- `container` (HTMLElement). The DOM element that contains the grid content.
- `data`: Server response with loaded data (posts, pagination, etc.).

Usage Example:
```js
ymcHooks.addAction('ymc/grid/after_update', function(data, container) {
  console.log('Posts are inserted into the DOM:', container);
  console.log('Server response data:', data);
});
```

- `ymcHooks.doAction('ymc/grid/after_complete', response.status, container);`
- `ymcHooks.doAction('ymc/grid/after_complete_filter_id', response.status, container);`
- `ymcHooks.doAction('ymc/grid/after_complete_filter_id_instance_index', response.status, container);`

Fires when the full grid update cycle is complete.

Parameters:
- `container (HTMLElement)`: The DOM element that contains the grid content.
- `response.status`: The HTTP status code of the response.

Usage Example:
```js
ymcHooks.addAction('ymc/grid/after_complete', function(status, container) {
  if (status === 200) {
    console.log('The request was completed successfully.');
  }
});
```

#### Popup
- `ymcHooks.doAction('ymc/popup/before_open', popup);`
- `ymcHooks.doAction('ymc/popup/before_open_filter_id, popup);`
- `ymcHooks.doAction('ymc/popup/after_open', popup, data);`
- `ymcHooks.doAction('ymc/popup/after_open_filter_id, popup, data);`

These hooks are triggered during the lifecycle of opening a popup in the system. They allow you to run custom code at specific moments before and after the popup opens.

- `ymcHooks.doAction('ymc/popup/before_open', popup);`

Fires before the popup starts opening, passing the popup DOM element as an argument.

- `ymcHooks.doAction('ymc/popup/after_open', popup, data);`

Fires immediately after the popup has been opened and content has been loaded.

Usage Example:
```js
// Before open
ymcHooks.addAction('ymc/popup/before_open_72', function(popup) {
  console.log('Popup before open', popup);
});
// After open
ymcHooks.addAction('ymc/popup/after_open_72', function(popup, data) {
  console.log('Popup after open', popup, data);
});
```

#### Preloader
- `ymcHooks.applyFilters('ymc/grid/preloader', defaultPreloader);`
- `ymcHooks.applyFilters('ymc/grid/preloader_filter_id', defaultPreloader);`

This filter allows you to customize the loading spinner image used during AJAX content loading in the plugin.
Returns a string containing the full path or URL to the custom preloader image.

Parameters:
- `defaultPreloader` (string) The default preloader image path.

Usage Example:
```js
ymcHooks.addFilter('ymc/grid/preloader_72', function(preloader) {
  return 'https://example.com/preloaders/default-spinner.svg';
});
```

#### MagicGrid

To build post cards in Masonry form, use the 'ymc/grid/after_update_{filter_id}' hooks and the Masonry mini library MagicGrid. To do this, you need to use the following code: The MagicGrid object has the following settings:
- `container: "#container", // Required. Can be a class, id, or an HTMLElement.`
- `static: false, // Required for static content. Default: false.`
- `items: 30, // Required for dynamic content. Initial number of items in the container.`
- `gutter: 30, // Optional. Space between items. Default: 25(px).`
- `maxColumns: 5, // Optional. Maximum number of columns. Default: Infinite.`
- `useMin: true, // Optional. Prioritize shorter columns when positioning items? Default: false.`
- `useTransform: true, // Optional. Position items using CSS transform? Default: True.`
- `animate: true, // Optional. Animate item positioning? Default: false.`
- `center: true, //Optional. Center the grid items? Default: true.`

Usage Example:
```js
ymcHooks.addAction('ymc/grid/after_update_72', function(data, container) {
  const grid = new MagicGrid({
      container: container,
      static: false,
      items: data.posts_count,
      gutter: 30,
      maxColumns: 5,
      useMin: true,
      useTransform: true,
      animate: true,
      center: true
     });
  });
});
```
To correctly display the grid, set styles for the post card, for example:
```css
.posts-grid--masonry .post-card {
    width: 320px;
}
```
List of Filters and Actions to override default settings of the Masonry Grid

#### Static content
- `ymcHooks.applyFilters('ymc/grid/masonry/staticContent', staticContent);`
- `ymcHooks.applyFilters('ymc/grid/masonry/staticContent_filter_id', staticContent);`
#### Space between items
- `ymcHooks.applyFilters('ymc/grid/masonry/gutter', gutter);`
- `ymcHooks.applyFilters('ymc/grid/masonry/gutter_filter_id', gutter);`
#### Maximum number of columns
- `ymcHooks.applyFilters('ymc/grid/masonry/maxColumns', maxColumns);`
- `ymcHooks.applyFilters('ymc/grid/masonry/maxColumns_filter_id', maxColumns);`
#### Prioritize shorter columns when positioning items
- `ymcHooks.applyFilters('ymc/grid/masonry/useMin', useMin);`
- `ymcHooks.applyFilters('ymc/grid/masonry/useMin_filter_id', useMin);`
#### Position items using CSS transform
- `ymcHooks.applyFilters('ymc/grid/masonry/useTransform', useTransform);`
- `ymcHooks.applyFilters('ymc/grid/masonry/useTransform_filter_id', useTransform);`
#### Animate item positioning
- `ymcHooks.applyFilters('ymc/grid/masonry/animate', animate);`
- `ymcHooks.applyFilters('ymc/grid/masonry/animate_filter_id', animate);`
#### Center the grid items
- `ymcHooks.applyFilters('ymc/grid/masonry/center', center);`
- `ymcHooks.applyFilters('ymc/grid/masonry/center_filter_id', center);`
#### Adds a listener that executes a function once the grid is ready.
- `ymcHooks.doAction('ymc/grid/masonry/magicGrid_ready', container);`
- `ymcHooks.doAction('ymc/grid/masonry/magicGrid_ready_filter_id', container);`
#### Adds a listener that executes a function whenever positionItems is called. Note: positionItems is called during initial setup and whenever the container or window is resized
- `ymcHooks.doAction('ymc/grid/masonry/magicGrid_position_complete', container);`
- `ymcHooks.doAction('ymc/grid/masonry/magicGrid_position_complete_filter_id', container);`

Usage Example:
```js
ymcHooks.addAction('ymc/grid/after_update_72', function(data, container) {
    ymcHooks.addFilter('ymc/grid/masonry/maxColumns_72', function(maxColumns) {
      maxColumns = 3;
      return maxColumns;
    });
    ymcHooks.addFilter('ymc/grid/masonry/center_72', function(center) {
      center = true;
      return center;
    });
    ymcHooks.addAction('ymc/grid/masonry/magicGrid_ready_72', function(container) {
      console.log('Magic Grid Ready', container);
    });
});

```


### YMCFilterGrid: Global Object API

The `YMCFilterGrid` global object provides core methods to interact with the filtered post grid system in real-time via JavaScript.
To use this object, enable this option in the plugin settings: Appearance - API JS Settings

#### Available Methods

`.init(container)`

Initializes the YMCFilterGrid object with the specified container selector.

Parameters:
- `container (string)`: The selector for the container element containing the filtered post grid.

Usage Example:
```js
YMCFilterGrid.init('#ymc-filter-1');
```

`YMCFilterGrid.filterByTerm(taxonomy, termId, sendRequest = true)`

Filters the grid by a specific taxonomy and term ID.

Parameters:
- `taxonomy (string)`: The name of the taxonomy to filter by.
- `termId (string)`: The ID of the term to filter by.
- `sendRequest (boolean, optional)`: Whether to send the filter request to the server. Default is true.

Usage Example:
```js
YMCFilterGrid.filterByTerm('category, post_tag', '12,34');
```

`YMCFilterGrid.filterByPostStatus(status, sendRequest = true)`

Filters the grid by a specific post status.

Parameters:
- `status (string)`: The post status to filter by.
- `sendRequest (boolean, optional)`: Whether to send the filter request to the server. Default is true.

Usage Example:
```js
YMCFilterGrid.filterByPostStatus('publish');
```

`YMCFilterGrid.sortPosts(orderBy, orderDirection = 'desc', options = {})`

Sorts posts by various criteria, including standard fields (date, title, etc.), custom fields (meta_key), and multiple fields (multiple_fields).

Parameters:
- `orderBy (string)`: Sorting key: `'date'`, `'title'`, `'meta_key'`, `'multiple_fields'`, etc.
- `orderDirection (string)`: Sort direction: `'asc'` or `'desc'` (default is `'asc'`)
- `options (object)`:  Additional parameters for specific sort types
- `options.metaKey`:  The name of the `meta_key` to sort by
- `options.metaValue` Sorting method by meta value: `'meta_value'`, `'meta_value_num'`, etc.
- `options.multipleFields` Array of objects of the form `{ field_name: string, order_type: string }`
- `options.sendRequest` Whether to send the request immediately. Defaults to `true`

Usage Example:
```js
// Sort by date:
YMCFilterGrid.sortPosts('date', 'desc');

// Sorting by meta key (meta_key):
YMCFilterGrid.sortPosts('meta_key', 'asc', {
  metaKey: 'rating',
  metaValue: 'meta_value_num'
});

// Sorting by multiple fields (multiple_fields):
YMCFilterGrid.sortPosts('multiple_fields', null, {
  multipleFields: [
    { field_name: 'menu_order', order_type: 'asc' },
    { field_name: 'date', order_type: 'desc' }
  ]
});
```

`YMCFilterGrid.filterByMeta(metaQuery, relation = 'AND', sendRequest = true)`

Filters posts by custom fields (postmeta) using WordPress meta_query. The method allows you to specify an array of conditions by which posts on the server will be selected, as well as to set a logical connection between these conditions (AND or OR).

Parameters:
- `metaQuery (array)`: An array of meta_query conditions. Array of conditions for filtering. Each element of the array is an object with fields:
  - `key (string)`: Meta field key
  - `value (mixed)`: Meta field value
  - `compare (string)`: Meta field comparison operator. (optional, defaults to "=") — comparison operator (=, !=, <, >, IN, LIKE, etc.)
  - `type (string)`: Meta field type. (optional, defaults to "CHAR") - data type (CHAR, NUMERIC, DATE, etc.)
- `relation (string)`: Logical connection between meta_query conditions: `'AND'` or `'OR'` (default is `'AND'`)
- `sendRequest (boolean)`: Whether to send the filter request to the server. Default is true.

Usage Example:
```js
// Will select posts with a meta field color equal to blue and a price less than or equal to 100.
YMCFilterGrid.filterByMeta([
  {
    key: 'color',
    value: 'blue',
    compare: '=',
    type: 'CHAR'
  },
  {
    key: 'price',
    value: 100,
    compare: '<=',
    type: 'NUMERIC'
  }
], 'AND');
```

`YMCFilterGrid.filterByDate(dateQuery, sendRequest = true)`

The filterByDate() method is used to filter posts by publication date using the WordPress date_query parameters.

Parameters:
- `dateQuery (object)`: WP_Query compatible `date_query` options array
- `sendRequest (boolean)`: Whether to send the filter request to the server. Default is true.

Please note that date_query must be passed from the frontend in a format compatible with WP_Query, for example:

`
[
'after'     => '2023-01-01',
'before'    => '2023-12-31',
'inclusive' => true,
]`

Usage Example:
```js
// Range between two dates (string format):
YMCFilterGrid.filterByDate({
  after: '2023-01-01',
  before: '2023-12-31',
  inclusive: true
});
```
```js
// By specific date with object:
YMCFilterGrid.filterByDate({
  after: { year: 2024, month: 6, day: 1 },
  before: { year: 2024, month: 6, day: 14 },
  inclusive: true
});
```
```js
// Only after a certain date:
YMCFilterGrid.filterByDate({
  after: '2024-01-01'
});
```
```js
// Example using relation: 'OR'
// It will return posts that were either: published in January 2024, or published in May 2024.
YMCFilterGrid.filterByDate({
  relation: 'OR',
  0: {
    after: '2024-01-01',
    before: '2024-01-31',
    inclusive: true
  },
  1: {
    after: '2024-05-01',
    before: '2024-05-31',
    inclusive: true
  }
});
```
```js
// Example using relation: 'AND'
// Searches for posts that: were published after January 1, 2024, and modified before June 1, 2024.
YMCFilterGrid.filterByDate({
  relation: 'AND',
  0: {
    column: 'post_date_gmt',
    after: '2024-01-01',
    inclusive: true
  },
  1: {
    column: 'post_modified',
    before: '2024-06-01',
    inclusive: true
  }
});
```
`YMCFilterGrid.search(keyword, termIds = [], sendRequest = true)`

Performs a post search by keyword, optionally filtering by specific term IDs.

Parameters:
- `keyword (string)`: Search keyword or phrase.
- `termIds (Array | string)`: Array of term IDs or a comma-separated string of IDs. If empty, term filtering will not be applied.
- `sendRequest (boolean)`: Whether to send the search request to the server. Default is true.

Usage Example:

```js
// Search by keyword only
YMCFilterGrid.search('news');

// Search by keyword and an array of term IDs
YMCFilterGrid.search('sports', [12, 15, 18]);

// Search by keyword and term IDs as a string
YMCFilterGrid.search('music', '5, 9, 22');

// Set search filters without sending a request
YMCFilterGrid.search('technology', [7, 14], false);
```

`YMCFilterGrid.choicePosts(selectedPosts = [], excludedPosts = 'no', sendRequest = true)`

Selects or excludes specific posts from the grid by their IDs

Parameters:
- `selectedPosts (Array | string)`: Array of post IDs or a comma-separated string of IDs to include or exclude.
- `excludedPosts (string)`: 'no' – include only the selected posts (post__in); 'yes' – exclude the selected posts (post__not_in).
- `sendRequest (boolean)`: Whether to send the search request to the server. Default is true.

Usage Example:

```js
// Include only specific posts
YMCFilterGrid.choicePosts([101, 102, 103], 'no');

// Exclude specific posts
YMCFilterGrid.choicePosts('201, 202', 'yes');

// Set filters without sending a request
YMCFilterGrid.choicePosts([401], 'no', [], false);
```

`YMCFilterGrid.getPosts()`

Sends a request to fetch posts using all previously configured filters without modifying them.
This method is useful when you want to configure multiple filters first (with sendRequest = false) and then send them to the server in a single request.

Usage Flow
Configure filters using methods like filterByTerm(), filterByMeta(), filterByDate(), search(), choicePosts(), etc., with the sendRequest parameter set to false.

Call getPosts() to send all accumulated filter settings to the server.

```js
// Step 1: Set filters without sending a request
YMCFilterGrid.filterByTerm('category', '5,7', false);
YMCFilterGrid.filterByPostStatus('publish', false);
YMCFilterGrid.sortPosts('date', 'desc', { sendRequest: false });

// Step 2: Send all filters in one request
YMCFilterGrid.getPosts();
```

`YMCFilterGrid.pageUpdated(page)`

Loads posts from a specific page in the grid while preserving all previously configured filters.

Parameters:
- `page (number)`: The page number to load.

Usage Example:

```js
// Go to page 3
YMCFilterGrid.pageUpdated(3);

// Go to the first page (equivalent to resetting pagination)
YMCFilterGrid.pageUpdated(1);
```

`YMCFilterGrid.openFilterPopup(postId)`

Opens the filter popup for a specific post in the grid by its post ID.

Parameters:
- `postId (string | number)`: The ID of the post for which the popup should be opened.

Usage Example:

```js
// Open the filter popup for post with ID 55
YMCFilterGrid.openFilterPopup(55);
```




### Advanced Developer Hooks

When using a plugin that displays different posts based on different criteria, there are built-in settings to control which posts are displayed, so you can choose how many to display, include or exclude terms, change the order, etc. However, if you need more complex queries, The plugin offers an "Advanced" query type that allows you to return exactly the arguments you need for your query. To do this, you will need to enable the ability to use your own query based on the global WP_query object. Go to the Advanced -> Advanced Query tab and turn the slider to "ON". Once this setting is enabled, you will see a new field called Query Type. From the drop-down list, select one of two ways to build a query:

- Advanced
- Callback

Query String
A query string is a string that contains parameters which looks something like this:

`posts_per_page=-1&post_type=portfolio&post_status=publish&orderby=title&tax_query[0][taxonomy]=portfolio_category&tax_query[0][field]=slug&tax_query[0][terms][]=inspiration`

Callback Function

`ymc/filter/query/wp/allowed_callbacks`

Allows you to modify the list of approved callback functions that can be used to filter or modify WP_Query parameters. This is typically used to whitelist safe callbacks when building dynamic or user-defined queries.
```php
apply_filters( 'ymc/filter/query/wp/allowed_callbacks', $callbacks );
apply_filters( 'ymc/filter/query/wp/allowed_callbacks_{filter_id}', $callbacks );
```
Parameters:
- `array $content`: Array of callback functions.
- `int $filter_id`: The ID of the filter.

Usage Example:
```php
add_filter('ymc/filter/query/wp/allowed_callbacks', function($callbacks) {
	$callbacks[] = 'custom_query_modifier';
	$callbacks[] = 'custom_query_modifier_2';
	return $callbacks;
});

/**
 * Callback function to modify WP_Query arguments.
 *
 * @param array $args {
 *     An associative array of context data passed to the callback.
 *
 *     @type array  $post_type List of post types to query.
 *     @type array  $taxonomy  List of taxonomy slugs relevant to the query.
 *     @type array  $terms     List of term IDs to filter by.
 *     @type int    $page_id   Current page ID where the query is being executed.
 * }
 *
 * @return array Modified or extended WP_Query arguments.
 */
function custom_query_modifier( $args ) {
    return [
        'post_type'       => ['post', 'book'],
        'posts_per_page'  => 10,
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => [6, 7, 15]
            ]
        ]		
    ];
}
```

### Changelog

### 3.0.0 — 2025-09-10

- Complete redesign of plugin architecture for better performance and scalability.
- Added **combined filter constructor** for building complex filters in grids.
- Improved UI/UX for filter management and grid customization.
- Added **rollback option** to restore previous plugin version after update.
- Old settings are preserved during update (no data loss).
- Optimized rendering engine for faster loading of grids.
- Extended developer API (new JS hooks, WP Query integration, custom templates).
- Improved compatibility with WPML and other multilingual plugins.
- Fixed multiple bugs from previous versions (pagination, sliders, masonry layout).  


