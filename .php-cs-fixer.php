<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

$config = new PhpCsFixer\Config();
$config->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect());

return $config->setFinder($finder)
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS' => true,
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@autoPHPMigration' => true,
        'attribute_empty_parentheses' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_strict_types' => false,
        'escape_implicit_backslashes' => false,
        'general_phpdoc_annotation_remove' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'increment_style' => [
            'style' => 'post',
        ],
        'is_null' => true,
        'list_syntax' => true,
        'logical_operators' => true,
        'modernize_strpos' => true,
        'modernize_types_casting' => true,
        'multiline_promoted_properties' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'native_constant_invocation' => true,
        'native_function_invocation' => [
            'include' => [
                '@all',
            ],
        ],
        'no_null_property_initialization' => false,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => false,
            'allow_unused_params' => false,
            'remove_inheritdoc' => true,
        ],
        'no_trailing_whitespace_in_string' => true,
        'no_unused_imports' => true,
        'no_useless_sprintf' => true,
        'nullable_type_declaration' => [
            'syntax' => 'union',
        ],
        'nullable_type_declaration_for_default_null_value' => [
            'use_nullable_type_declaration' => true,
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_private',
                'constant_protected',
                'constant_public',
                'property_private',
                'property_protected',
                'property_public',
                'construct',
                'destruct',
                'magic',
            ],
            'sort_algorithm' => 'none',
        ],
        'ordered_imports' => [
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'ordered_traits' => true,
        'phpdoc_add_missing_param_annotation' => false,
        'phpdoc_line_span' => [
            'property' => 'multi',
            'method' => 'multi',
        ],
        'phpdoc_order' => [
            'order' => [
                'param',
                'throws',
                'return',
            ],
        ],
        'phpdoc_order_by_value' => [
            'annotations' => [
                'throws',
            ],
        ],
        'phpdoc_separation' => [
            'groups' => [
                [
                    'psalm-import-type',
                    'psalm-type',
                ],
                [
                    'var',
                    'psalm-var',
                ],
                [
                    'param',
                    'psalm-param',
                ],
                [
                    'return',
                    'psalm-return',
                ],
            ],
        ],
        'phpdoc_to_comment' => false,
        'php_unit_attributes' => true,
        'php_unit_construct' => true,
        'php_unit_data_provider_method_order' => [
            'placement' => 'after',
        ],
        'php_unit_data_provider_static' => true,
        'php_unit_dedicate_assert' => true,
        'php_unit_dedicate_assert_internal_type' => true,
        'php_unit_expectation' => true,
        'php_unit_internal_class' => false,
        'php_unit_mock_short_will_return' => true,
        'php_unit_set_up_tear_down_visibility' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'this',
        ],
        'php_unit_test_class_requires_covers' => false,
        'return_assignment' => [
            'skip_named_var_tags' => true,
        ],
        'self_accessor' => true,
        'string_implicit_backslashes' => false,
        'single_line_empty_body' => false,
        'single_line_throw' => false,
        'string_length_to_empty' => true,
        'trailing_comma_in_multiline' => [
            'after_heredoc' => true,
            'elements' => [
                'arguments',
                'arrays',
                'array_destructuring',
                'match',
                'parameters',
            ],
        ],
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    ]);
