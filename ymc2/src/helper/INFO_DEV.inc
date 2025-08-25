<!-- === СЛУЖЕБНАЯ ИНФОРМАЦИЯ === -->

<?php
    // Структура данных для храненияв БД атрибутов таксономий
    // 5 атрибута для таксономии
    $tax_attrs = [
        [
            'name'       => 'category',
            'background' => '#1f1f1f',
            'color'      => '#1f1f1f',
            'label'      => 'Custom Tax',
            'status'     => 'changed'
        ],
	    [
		    'name'       => 'author_book',
		    'background' => '#81d742',
		    'color'      => '#dd9933',
		    'label'      => 'Custom Author',
		    'status'     => 'changed'
	    ]
    ];
    //update_post_meta( 72, 'ymc_fg_tax_attrs', $tax_attrs );

    // Структура данных для храненияв БД атрибутов термов
    // 13 атрибутов для терма
    $term_attrs = [
        [
            'term_id'         => 10,
            'term_slug'       => 'grant',
            'term_name_orig ' => 'Grant',
            'term_background' => '#1f1f1f',
            'term_color'      => '#1f1f1f',
            'term_class'      => 'my-class',
            'term_default'    => 'true',
            'term_visible'    => 'true',
            'term_name'       => 'Title Term',
            'term_checked'    => 'true',
            'term_status'     => 'changed',
            'icon_class'      => 'fas-cars',
            'icon_alignment'  => 'right',
            'icon_color'      => '#ccc'
        ],
	    [
		    'term_id'         => 105,
		    'term_slug'       => 'audi-a4',
		    'term_name_orig'  => 'A4',
		    'term_background' => '#1f1f1f',
		    'term_color'      => '#1f1f1f',
		    'term_class'      => 'my-class',
		    'term_default'    => 'true',
		    'term_visible'    => 'false',
		    'term_name'       => 'My Title Term',
		    'term_checked'    => 'false',
		    'term_status'     => 'changed',
		    'icon_class'      => 'fas-users',
		    'icon_alignment'  => 'left',
		    'icon_color'      => '#ccc'
	    ]
    ];
    //update_post_meta( 72, 'ymc_fg_term_attrs', $term_attrs );

    // Ручная сортировка таксономий. Структура данных. Slug Taxonomy
    $tax_sort = [
	    'post_format',
        'helth',
        'post_tag',
        'author_book',
        'category'
    ];
    //update_post_meta( 72, 'ymc_fg_tax_sort', $tax_sort );

    // Ручная сортировка термов. Структура данных. Term ID
    $term_sort = [
	    "101",
        "105",
	    "123",
        "104",
        "103",
        "93",
        "97",
        "107",
        "90",
        "106",
        "88",
        "102",
        "99",
        "19",
        "87",
        "122",
        "95",
        "98",
        "1",
        "5",
        "89",
        "108",
	    "92",
        "14",
        "12",
        "13",
        "125"
    ];
    //update_post_meta( 72, 'ymc_fg_term_sort', $term_sort );

    // Структура данных для храненияв БД атрибутов фильтров
    $types_filters = [
	    [
		    'tax_name' => ['category'],
		    'filter_type' => 'default',
		    'placement' => 'top' // top | left | right
        ],
	    [
		    'tax_name' => ['post_tag'],
		    'filter_type' => 'default',
		    'placement' => 'left' // top | left | right
	    ],
	    [
		    'tax_name' => ['author_book'],
		    'filter_type' => 'dropdown',
		    'placement' => 'left'
	    ]
    ];
    //update_post_meta( 72, 'ymc_fg_filter_options', $types_filters );




?>




