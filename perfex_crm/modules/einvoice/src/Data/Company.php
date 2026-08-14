<?php

namespace Perfexcrm\EInvoice\Data;

class Company extends Data
{
    private static array $placeholders;

    public static function getPlaceholders(): array
    {
        if (! isset(self::$placeholders)) {
            $placeholders = [
                '{{COMPANY_NAME}}',
                '{{COMPANY_ADDRESS}}',
                '{{COMPANY_CITY}}',
                '{{COMPANY_STATE}}',
                '{{COMPANY_COUNTRY_NAME}}',
                '{{COMPANY_COUNTRY_ISO2}}',
                '{{COMPANY_COUNTRY_ISO3}}',
                '{{COMPANY_ZIP_CODE}}',
                '{{COMPANY_PHONE}}',
                '{{COMPANY_VAT_NUMBER}}',
            ];

            $placeholders = self::addCustomFieldsToPlaceholders($placeholders, 'company');

            self::$placeholders = $placeholders;
        }

        return hooks()->apply_filters('before_get_einvoice_company_placeholders', self::$placeholders);
    }

    public function getPlaceHolderValues(): array
    {
        $country = $this->findCountry();
        $values  = [
            'COMPANY_NAME'         => static::encodeForXml(get_option('invoice_company_name')),
            'COMPANY_ADDRESS'      => static::encodeForXml(get_option('invoice_company_address'), false),
            'COMPANY_CITY'         => static::encodeForXml(get_option('invoice_company_city')),
            'COMPANY_STATE'        => static::encodeForXml(get_option('company_state')),
            'COMPANY_COUNTRY_NAME' => static::encodeForXml($country?->iso2, false),
            'COMPANY_COUNTRY_ISO2' => static::encodeForXml($country?->iso3, false),
            'COMPANY_COUNTRY_ISO3' => static::encodeForXml($country?->short_name ?? get_option('invoice_company_country_code'), false),
            'COMPANY_ZIP_CODE'     => static::encodeForXml(get_option('invoice_company_postal_code'), false),
            'COMPANY_PHONE'        => static::encodeForXml(get_option('invoice_company_phonenumber'), false),
            'COMPANY_VAT_NUMBER'   => static::encodeForXml(get_option('company_vat'), false),
        ];

        foreach (get_company_custom_fields() as $field) {
            $values[static::customFieldValueKey($field['slug'])] = $field['value'];
        }

        return hooks()->apply_filters('before_get_einvoice_company_placeholder_values', $values);
    }

    private function findCountry(): object|null
    {
        $search = get_option('invoice_company_country_code');
        if ($search === null || $search === '') {
            return null;
        }

        $CI      = &get_instance();
        $country = $CI->app_object_cache->get('db-country-' . $search);
        if (! $country) {
            $CI->db->or_where('iso2', $search);
            $CI->db->or_where('iso3', $search);
            $CI->db->or_where('short_name', $search);
            $CI->db->or_where('long_name', $search);
            $country = $CI->db->get(db_prefix() . 'countries')->row();
            $CI->app_object_cache->add('db-country-' . $search, $country);
        }

        return $country;
    }
}
