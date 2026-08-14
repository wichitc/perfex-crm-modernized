<?php

namespace Perfexcrm\EInvoice\Data;

use BackedEnum;

class Data
{
    public static function addCustomFieldsToPlaceholders(array $placeholders, string|array $type): array
    {
        foreach ((array) $type as $t) {
            foreach (get_custom_fields($t) as $customField) {
                $placeholders[] = '{{' . (static::customFieldValueKey($customField['slug'])) . '}}';
            }
        }

        return $placeholders;
    }

    public static function addCustomFieldsToValues(array $values, string $type, $relId): array
    {
        foreach (get_custom_fields($type) as $field) {
            $slug  = static::customFieldValueKey($field['slug']);
            $value = get_custom_field_value($relId, $field['id'], $type) ?: $field['default_value'];

            if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
                $value = to_sql_date($value, $field['type'] == 'date_picker_time');
            }

            $values[$slug] = static::encodeForXml($value, false);
        }

        return $values;
    }

    protected static function customFieldValueKey(string $slug): string
    {
        return 'CF_' . strtoupper($slug);
    }

    public static function encodeForXml($value, $doubleEncode = true): string
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1, 'UTF-8', $doubleEncode);
    }
}
